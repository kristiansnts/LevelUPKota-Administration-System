package handler

import (
	"archive/tar"
	"bytes"
	"compress/gzip"
	"encoding/binary"
	"fmt"
	"io"
	"net"
	"net/http"
	"os"
	"os/exec"
	"path/filepath"
	"strconv"
	"strings"
	"sync"
	"text/template"
	"time"
)

// ---------------------------------------------------------------------------
// PHP-FPM config template (embedded as a string constant, no file needed)
// ---------------------------------------------------------------------------

const phpFpmConfTmpl = `
[global]
error_log = /dev/stderr
log_level = warning

[www]
listen = {{.SocketPath}}
listen.mode = 0666

pm = dynamic
pm.max_children = 5
pm.start_servers = 1
pm.min_spare_servers = 1
pm.max_spare_servers = 3
pm.max_requests = 500

; Pass all Lambda env vars (APP_KEY, DB_*, etc.) through to PHP workers
clear_env = no

php_admin_value[memory_limit] = 256M
php_admin_value[error_log] = /dev/stderr
php_admin_flag[log_errors] = on
php_admin_value[display_errors] = Off
php_admin_value[expose_php] = Off
`

// ---------------------------------------------------------------------------
// Global state (survives warm Vercel container reuse)
// ---------------------------------------------------------------------------

var (
	once             sync.Once
	initErr          error
	socketPath       = "/tmp/php-fpm.sock"
	appRoot          string
	laravelEntryPath = "/tmp/laravel-entry.php"
)

func envOrDefault(key, def string) string {
	if v := os.Getenv(key); v != "" {
		return v
	}
	return def
}

// ---------------------------------------------------------------------------
// Vercel serverless entry point
// ---------------------------------------------------------------------------

// Handler is called by the Vercel Go runtime on every HTTP request.
func Handler(w http.ResponseWriter, r *http.Request) {
	once.Do(func() { initErr = bootstrap() })
	if initErr != nil {
		http.Error(w, fmt.Sprintf("bootstrap error: %v", initErr), http.StatusInternalServerError)
		return
	}
	if err := serveFastCGI(w, r); err != nil {
		http.Error(w, fmt.Sprintf("fastcgi error: %v", err), http.StatusBadGateway)
	}
}

// ---------------------------------------------------------------------------
// Cold-start bootstrap — downloads PHP-FPM + vendor, starts php-fpm
// ---------------------------------------------------------------------------

func bootstrap() error {
	// Laravel files are copied to api/laravel/ by buildCommand, so they land at /var/task/laravel
	appRoot = envOrDefault("VERCEL_APP_ROOT", "/var/task/laravel")

	phpFpmDst := "/tmp/php-fpm-bin"

	// 1. Download and extract PHP-FPM binary (if not already in /tmp)
	if _, err := os.Stat(phpFpmDst); os.IsNotExist(err) {
		phpFpmURL := envOrDefault("PHP_FPM_URL",
			"https://dl.static-php.dev/static-php-cli/bulk/php-8.4.18-fpm-linux-x86_64.tar.gz")
		if err := downloadAndExtractFile(phpFpmURL, phpFpmDst, "php-fpm"); err != nil {
			return fmt.Errorf("download php-fpm: %w", err)
		}
		if err := os.Chmod(phpFpmDst, 0755); err != nil {
			return err
		}
	}

	// 2. Extract vendor — prefer bundled vendor.tar.gz (no cold-start download needed),
	//    fall back to VENDOR_URL if not bundled.
	if _, err := os.Stat("/tmp/vendor"); os.IsNotExist(err) {
		bundled := "/var/task/vendor.tar.gz"
		if _, err := os.Stat(bundled); err == nil {
			if err := extractTarGzFile(bundled, "/tmp"); err != nil {
				return fmt.Errorf("extract bundled vendor: %w", err)
			}
		} else {
			vendorURL := os.Getenv("VENDOR_URL")
			if vendorURL == "" {
				return fmt.Errorf("VENDOR_URL required (no bundled vendor.tar.gz found)")
			}
			if err := downloadAndExtractTarGz(vendorURL, "/tmp"); err != nil {
				return fmt.Errorf("download vendor: %w", err)
			}
		}
	}

	if err := os.MkdirAll("/tmp/views", 0755); err != nil {
		return err
	}

	// Create writable storage directories (basePath/storage is read-only on Vercel).
	for _, dir := range []string{
		"/tmp/storage/logs",
		"/tmp/storage/framework/sessions",
		"/tmp/storage/framework/views",
		"/tmp/storage/framework/cache/data",
		"/tmp/storage/app/public",
	} {
		if err := os.MkdirAll(dir, 0755); err != nil {
			return fmt.Errorf("mkdir %s: %w", dir, err)
		}
	}

	// Composer autoloader resolves paths as /tmp/vendor/composer/../../{dir} = /tmp/{dir}.
	// Create symlinks so App\, Database\, etc. namespaces resolve to the real source files.
	for _, dir := range []string{"app", "config", "database", "resources", "routes"} {
		dst := "/tmp/" + dir
		if _, err := os.Lstat(dst); os.IsNotExist(err) {
			if err := os.Symlink(appRoot+"/"+dir, dst); err != nil {
				return fmt.Errorf("symlink %s: %w", dir, err)
			}
		}
	}

	// 3. Write a custom PHP entry that loads vendor from /tmp/vendor
	//    (/var/task is read-only so we can't symlink vendor there)
	entryPHP := fmt.Sprintf(`<?php
use Illuminate\Foundation\Application;
use Illuminate\Foundation\PackageManifest;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

if (file_exists($maintenance = '%s/storage/framework/maintenance.php')) {
    require $maintenance;
}

require '/tmp/vendor/autoload.php';

/** @var Application $app */
$app = require_once '%s/bootstrap/app.php';

// Redirect storage to /tmp/storage (basePath/storage is read-only on Vercel).
$app->useStoragePath('/tmp/storage');

// PackageManifest defaults vendorPath to basePath/vendor, but vendor is at /tmp/vendor.
$manifest = new PackageManifest(new Filesystem, $app->basePath(), $app->getCachedPackagesPath());
$manifest->vendorPath = '/tmp/vendor';
$app->instance(PackageManifest::class, $manifest);

try {
    $app->handleRequest(Request::capture());
} catch (\Throwable $e) {
    http_response_code(500);
    header('Content-Type: text/plain');
    echo get_class($e) . ': ' . $e->getMessage() . "\n";
    echo "in " . $e->getFile() . ':' . $e->getLine() . "\n\n";
    echo $e->getTraceAsString();
    $prev = $e->getPrevious();
    while ($prev) {
        echo "\n\nCaused by: " . get_class($prev) . ': ' . $prev->getMessage() . "\n";
        echo $prev->getTraceAsString();
        $prev = $prev->getPrevious();
    }
}
`, appRoot, appRoot)
	if err := os.WriteFile(laravelEntryPath, []byte(entryPHP), 0644); err != nil {
		return fmt.Errorf("write laravel entry: %w", err)
	}

	// 4. Write php-fpm.conf
	confPath := "/tmp/php-fpm.conf"
	if err := writeFpmConf(confPath); err != nil {
		return fmt.Errorf("write php-fpm.conf: %w", err)
	}

	// 5. Start php-fpm
	cmd := exec.Command(phpFpmDst, "--nodaemonize", "--fpm-config", confPath)
	cmd.Stdout = os.Stderr
	cmd.Stderr = os.Stderr
	if err := cmd.Start(); err != nil {
		return fmt.Errorf("start php-fpm: %w", err)
	}

	return waitForSocket(socketPath, 15*time.Second)
}

func writeFpmConf(dst string) error {
	t, err := template.New("fpm").Parse(phpFpmConfTmpl)
	if err != nil {
		return err
	}
	f, err := os.Create(dst)
	if err != nil {
		return err
	}
	defer f.Close()
	return t.Execute(f, map[string]string{"SocketPath": socketPath})
}

func waitForSocket(path string, timeout time.Duration) error {
	deadline := time.Now().Add(timeout)
	for time.Now().Before(deadline) {
		if conn, err := net.Dial("unix", path); err == nil {
			conn.Close()
			return nil
		}
		time.Sleep(100 * time.Millisecond)
	}
	return fmt.Errorf("php-fpm socket %q not ready after %s", path, timeout)
}

// downloadAndExtractFile downloads a .tar.gz from url, finds the file named
// binaryName inside it, and writes it to dst.
func downloadAndExtractFile(url, dst, binaryName string) error {
	resp, err := http.Get(url)
	if err != nil {
		return err
	}
	defer resp.Body.Close()
	if resp.StatusCode != http.StatusOK {
		return fmt.Errorf("GET %s: %s", url, resp.Status)
	}
	gr, err := gzip.NewReader(resp.Body)
	if err != nil {
		return err
	}
	defer gr.Close()
	tr := tar.NewReader(gr)
	for {
		hdr, err := tr.Next()
		if err == io.EOF {
			break
		}
		if err != nil {
			return err
		}
		if filepath.Base(hdr.Name) == binaryName && hdr.Typeflag == tar.TypeReg {
			if err := os.MkdirAll(filepath.Dir(dst), 0755); err != nil {
				return err
			}
			f, err := os.OpenFile(dst, os.O_CREATE|os.O_WRONLY|os.O_TRUNC, 0755)
			if err != nil {
				return err
			}
			_, copyErr := io.Copy(f, tr)
			f.Close()
			return copyErr
		}
	}
	return fmt.Errorf("binary %q not found in archive %s", binaryName, url)
}

// downloadAndExtractTarGz downloads a .tar.gz from url and extracts it to dstDir.
func downloadAndExtractTarGz(url, dstDir string) error {
	resp, err := http.Get(url)
	if err != nil {
		return err
	}
	defer resp.Body.Close()
	if resp.StatusCode != http.StatusOK {
		return fmt.Errorf("GET %s: %s", url, resp.Status)
	}
	gr, err := gzip.NewReader(resp.Body)
	if err != nil {
		return err
	}
	defer gr.Close()
	return extractTar(tar.NewReader(gr), dstDir)
}

// extractTarGzFile extracts a local .tar.gz file to dstDir.
func extractTarGzFile(srcPath, dstDir string) error {
	f, err := os.Open(srcPath)
	if err != nil {
		return err
	}
	defer f.Close()
	gr, err := gzip.NewReader(f)
	if err != nil {
		return err
	}
	defer gr.Close()
	return extractTar(tar.NewReader(gr), dstDir)
}

func extractTar(tr *tar.Reader, dstDir string) error {
	for {
		hdr, err := tr.Next()
		if err == io.EOF {
			break
		}
		if err != nil {
			return err
		}
		target := filepath.Join(dstDir, hdr.Name)
		switch hdr.Typeflag {
		case tar.TypeDir:
			if err := os.MkdirAll(target, os.FileMode(hdr.Mode)); err != nil {
				return err
			}
		case tar.TypeReg:
			if err := os.MkdirAll(filepath.Dir(target), 0755); err != nil {
				return err
			}
			f, err := os.OpenFile(target, os.O_CREATE|os.O_WRONLY|os.O_TRUNC, os.FileMode(hdr.Mode))
			if err != nil {
				return err
			}
			_, copyErr := io.Copy(f, tr)
			f.Close()
			if copyErr != nil {
				return copyErr
			}
		}
	}
	return nil
}

// ---------------------------------------------------------------------------
// FastCGI client
// ---------------------------------------------------------------------------

const (
	fcgiVersion       = 1
	fcgiBeginRequest  = 1
	fcgiParams        = 4
	fcgiStdin         = 5
	fcgiStdout        = 6
	fcgiStderr        = 7
	fcgiEndRequest    = 3
	fcgiRoleResponder = 1
)

func serveFastCGI(w http.ResponseWriter, r *http.Request) error {
	conn, err := net.Dial("unix", socketPath)
	if err != nil {
		return fmt.Errorf("dial php-fpm: %w", err)
	}
	defer conn.Close()

	const reqID = 1

	if err := writeFcgiRecord(conn, fcgiBeginRequest, reqID, beginRequestBody(fcgiRoleResponder)); err != nil {
		return err
	}
	if err := writeFcgiRecord(conn, fcgiParams, reqID, encodeParams(buildParams(r))); err != nil {
		return err
	}
	if err := writeFcgiRecord(conn, fcgiParams, reqID, nil); err != nil {
		return err
	}
	if r.Body != nil {
		body, _ := io.ReadAll(r.Body)
		if len(body) > 0 {
			if err := writeFcgiRecord(conn, fcgiStdin, reqID, body); err != nil {
				return err
			}
		}
	}
	if err := writeFcgiRecord(conn, fcgiStdin, reqID, nil); err != nil {
		return err
	}

	var stdout bytes.Buffer
	if err := readFcgiResponse(conn, reqID, &stdout); err != nil {
		return err
	}
	return writeCGIResponse(w, &stdout)
}

func buildParams(r *http.Request) map[string]string {
	uri := r.URL.RequestURI()
	queryString := ""
	if idx := strings.Index(uri, "?"); idx >= 0 {
		queryString = uri[idx+1:]
		uri = uri[:idx]
	}
	params := map[string]string{
		// Use custom entry so vendor loads from /tmp/vendor
		"SCRIPT_FILENAME":   laravelEntryPath,
		"SCRIPT_NAME":       "/index.php",
		"DOCUMENT_ROOT":     filepath.Join(appRoot, "public"),
		"REQUEST_METHOD":    r.Method,
		"REQUEST_URI":       r.URL.RequestURI(),
		"PATH_INFO":         uri,
		"QUERY_STRING":      queryString,
		"SERVER_PROTOCOL":   r.Proto,
		"SERVER_NAME":       r.Host,
		"SERVER_PORT":       "443",
		"SERVER_SOFTWARE":   "vercel-laravel-go",
		"GATEWAY_INTERFACE": "CGI/1.1",
		"REDIRECT_STATUS":   "200",
		"HTTPS":             "on",
		"HTTP_HOST":         r.Host,
	}
	for k, vals := range r.Header {
		key := "HTTP_" + strings.ToUpper(strings.ReplaceAll(k, "-", "_"))
		params[key] = strings.Join(vals, ", ")
	}
	if ct := r.Header.Get("Content-Type"); ct != "" {
		params["CONTENT_TYPE"] = ct
	}
	if r.ContentLength >= 0 {
		params["CONTENT_LENGTH"] = strconv.FormatInt(r.ContentLength, 10)
	}
	return params
}

func writeFcgiRecord(w io.Writer, recType uint8, reqID uint16, body []byte) error {
	header := make([]byte, 8)
	header[0] = fcgiVersion
	header[1] = recType
	binary.BigEndian.PutUint16(header[2:], reqID)
	binary.BigEndian.PutUint16(header[4:], uint16(len(body)))
	if _, err := w.Write(header); err != nil {
		return err
	}
	if len(body) > 0 {
		_, err := w.Write(body)
		return err
	}
	return nil
}

func beginRequestBody(role uint16) []byte {
	b := make([]byte, 8)
	binary.BigEndian.PutUint16(b[0:], role)
	return b
}

func encodeParams(params map[string]string) []byte {
	var buf bytes.Buffer
	for k, v := range params {
		writeNVLen(&buf, len(k))
		writeNVLen(&buf, len(v))
		buf.WriteString(k)
		buf.WriteString(v)
	}
	return buf.Bytes()
}

func writeNVLen(w *bytes.Buffer, n int) {
	if n < 128 {
		w.WriteByte(byte(n))
	} else {
		b := make([]byte, 4)
		binary.BigEndian.PutUint32(b, uint32(n)|0x80000000)
		w.Write(b)
	}
}

func readFcgiResponse(r io.Reader, reqID uint16, stdout *bytes.Buffer) error {
	hdr := make([]byte, 8)
	for {
		if _, err := io.ReadFull(r, hdr); err != nil {
			return fmt.Errorf("read fcgi header: %w", err)
		}
		recType := hdr[1]
		rID := binary.BigEndian.Uint16(hdr[2:4])
		contentLen := binary.BigEndian.Uint16(hdr[4:6])
		paddingLen := hdr[6]

		var content []byte
		if contentLen > 0 {
			content = make([]byte, contentLen)
			if _, err := io.ReadFull(r, content); err != nil {
				return fmt.Errorf("read fcgi body: %w", err)
			}
		}
		if paddingLen > 0 {
			if _, err := io.ReadFull(r, make([]byte, paddingLen)); err != nil {
				return fmt.Errorf("read fcgi padding: %w", err)
			}
		}
		if rID != reqID {
			continue
		}
		switch recType {
		case fcgiStdout:
			stdout.Write(content)
		case fcgiStderr:
			os.Stderr.Write(content)
		case fcgiEndRequest:
			return nil
		}
	}
}

func writeCGIResponse(w http.ResponseWriter, buf *bytes.Buffer) error {
	data := buf.String()
	headerEnd := strings.Index(data, "\r\n\r\n")
	sep := 4
	if headerEnd == -1 {
		headerEnd = strings.Index(data, "\n\n")
		sep = 2
		if headerEnd == -1 {
			return fmt.Errorf("no CGI header separator found")
		}
	}
	statusCode := 200
	for _, line := range strings.Split(data[:headerEnd], "\n") {
		line = strings.TrimRight(line, "\r")
		if line == "" {
			continue
		}
		idx := strings.Index(line, ":")
		if idx < 0 {
			continue
		}
		key := strings.TrimSpace(line[:idx])
		val := strings.TrimSpace(line[idx+1:])
		if strings.EqualFold(key, "Status") {
			fmt.Sscanf(val, "%d", &statusCode)
			continue
		}
		w.Header().Set(key, val)
	}
	w.WriteHeader(statusCode)
	_, err := io.WriteString(w, data[headerEnd+sep:])
	return err
}
