<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Approval Surat</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            padding: 20px;
        }
        
        .container {
            max-width: 900px;
            margin: 0 auto;
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .header {
            background-color: #c41e3a;
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        
        .header h1 {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .header p {
            font-size: 16px;
            opacity: 0.9;
        }
        
        .content {
            padding: 30px 20px;
        }
        
        .mail-info {
            margin-bottom: 30px;
        }
        
        .info-item {
            display: flex;
            flex-direction: column;
            margin-bottom: 20px;
            padding: 15px;
            background-color: #f9f9f9;
            border-radius: 5px;
        }
        
        .info-label {
            font-weight: bold;
            font-size: 14px;
            color: #666;
            margin-bottom: 8px;
            text-transform: uppercase;
        }
        
        .info-value {
            font-size: 16px;
            color: #333;
            line-height: 1.5;
        }
        
        .document-embed {
            margin-bottom: 30px;
            border: 1px solid #ddd;
            border-radius: 5px;
            overflow: hidden;
            background-color: #fff;
        }
        
        .document-embed iframe {
            width: 100%;
            height: 600px;
            border: none;
        }
        
        .document-placeholder {
            padding: 40px;
            text-align: center;
            color: #999;
            background-color: #f9f9f9;
        }
        
        .action-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 30px;
        }
        
        .btn {
            padding: 15px 40px;
            font-size: 16px;
            font-weight: bold;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-approve {
            background-color: #28a745;
            color: white;
        }
        
        .btn-approve:hover {
            background-color: #218838;
        }
        
        .btn-reject {
            background-color: #dc3545;
            color: white;
        }
        
        .btn-reject:hover {
            background-color: #c82333;
        }
        
        .alert {
            padding: 15px 20px;
            margin-bottom: 20px;
            border-radius: 5px;
            font-size: 14px;
        }
        
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>Approval Surat</h1>
            <p>Silakan periksa dan lakukan approval surat</p>
        </div>

        <!-- Content -->
        <div class="content">
            @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
            @endif

            @if(isset($selected_signer) && $selected_signer)
            <div style="background-color: #d1ecf1; border: 1px solid #bee5eb; border-radius: 5px; padding: 15px; margin-bottom: 20px;">
                <h3 style="color: #0c5460; margin-bottom: 5px; font-size: 16px;">👤 Diminta untuk:</h3>
                <div style="color: #0c5460; font-weight: bold;">
                    {{ $selected_signer['name'] }} - {{ $selected_signer['position'] }}
                </div>
            </div>
            @endif

            <!-- Mail Information -->
            <div class="mail-info">
                <div class="info-item">
                    <div class="info-label">Nomor Surat</div>
                    <div class="info-value">{{ $mail_code }}</div>
                </div>
                
                <div class="info-item">
                    <div class="info-label">Deskripsi</div>
                    <div class="info-value">{{ $description }}</div>
                </div>
            </div>

            <!-- Rejection History -->
            @php
                $qrGen = $mail->qrGenerators()->first();
                $rejectedSigners = [];
                if ($qrGen) {
                    foreach ($qrGen->qrGeneratorSigners as $signer) {
                        if ($signer->status === 'rejected' && !empty($signer->rejection_notes)) {
                            $rejectedSigners[] = [
                                'signer_name' => $signer->qrSigner->signer_name ?? '-',
                                'reason' => $signer->rejection_notes,
                                'rejected_at' => $signer->updated_at,
                            ];
                        }
                    }
                }
            @endphp

            @if(count($rejectedSigners) > 0)
            <div style="background-color: #fff3cd; border: 1px solid #ffc107; border-radius: 5px; padding: 15px; margin-bottom: 30px;">
                <h3 style="color: #856404; margin-bottom: 10px; font-size: 16px;">⚠️ Riwayat Penolakan</h3>
                @foreach($rejectedSigners as $rejection)
                <div style="background-color: white; padding: 10px; margin-bottom: 10px; border-radius: 3px; border-left: 3px solid #dc3545;">
                    <div style="font-weight: bold; color: #333; margin-bottom: 5px;">
                        {{ $rejection['signer_name'] }}
                    </div>
                    <div style="color: #666; font-size: 14px; margin-bottom: 5px;">
                        {{ $rejection['reason'] }}
                    </div>
                    <div style="color: #999; font-size: 12px;">
                        {{ $rejection['rejected_at']->format('d M Y, H:i') }}
                    </div>
                </div>
                @endforeach
            </div>
            @endif

            <!-- Document Embed -->
            <div class="document-embed">
                @if($file_exists && $file_url)
                    <!-- Google Drive Embedded Viewer -->
                    <iframe src="{{ $file_url }}" title="{{ $mail_code }}" allow="autoplay"></iframe>
                    <div style="padding: 10px; text-align: center; background: #f9f9f9; border-top: 1px solid #ddd;">
                        <small style="color: #666;">File: {{ basename($file_name ?? 'Document') }}</small>
                        <br>
                        @if($file_id)
                            <a href="https://drive.google.com/file/d/{{ $file_id }}/view" target="_blank" style="color: #007bff; text-decoration: none; font-size: 12px;">Buka di Google Drive</a>
                        @else
                            <a href="{{ $file_url }}" target="_blank" style="color: #007bff; text-decoration: none; font-size: 12px;">Buka di tab baru</a>
                        @endif
                    </div>
                @elseif($file_name && $file_exists && !$is_google_drive)
                    @php
                        $extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
                    @endphp
                    
                    @if(in_array($extension, ['pdf']))
                        <iframe src="{{ $file_url }}#view=FitH" title="{{ $mail_code }}"></iframe>
                    @elseif(in_array($extension, ['jpg', 'jpeg', 'png', 'gif']))
                        <img src="{{ $file_url }}" alt="{{ $mail_code }}" style="width: 100%; height: auto;">
                    @elseif(in_array($extension, ['doc', 'docx']))
                        <iframe src="https://docs.google.com/viewer?url={{ urlencode($file_url) }}&embedded=true" title="{{ $mail_code }}"></iframe>
                    @else
                        <div class="document-placeholder">
                            <p>File: {{ basename($file_name) }}</p>
                            <p>Tipe file tidak dapat ditampilkan di browser</p>
                            <a href="{{ $file_url }}" download class="btn btn-approve" style="margin-top: 15px; display: inline-block;">Download File</a>
                        </div>
                    @endif
                @elseif(($file_name || $file_id) && !$file_exists)
                    <div class="document-placeholder">
                        <p>File: {{ basename($file_name ?? 'Document') }}</p>
                        <p>⚠️ File tidak ditemukan atau tidak dapat diakses</p>
                        <p style="font-size: 12px; color: #999; margin-top: 10px;">Pastikan file telah diupload dengan benar</p>
                    </div>
                @else
                    <div class="document-placeholder">
                        <p>Tidak ada file yang dilampirkan</p>
                    </div>
                @endif
            </div>

            <!-- Action Buttons -->
            @if($qr_generator_signer->status === 'draft')
                <div class="action-buttons">
                    <form action="{{ route('mail.approval.approve', $qr_generator_qr_signer_id) }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-approve" onclick="return confirm('Apakah Anda yakin ingin menyetujui surat ini?')">
                            Setujui
                        </button>
                    </form>
                    
                    <button type="button" class="btn btn-reject" onclick="showRejectModal()">
                        Tolak
                    </button>
                </div>
            @else
                <div style="padding: 20px; text-align: center; background-color: #f8f9fa; border-radius: 5px; margin-top: 20px;">
                    @if($qr_generator_signer->status === 'approved')
                        <div style="color: #28a745; font-size: 18px; font-weight: bold;">
                            ✓ Surat Sudah Disetujui
                        </div>
                        <div style="color: #6c757d; font-size: 14px; margin-top: 10px;">
                            Surat ini telah disetujui oleh {{ $selected_signer['name'] }}
                        </div>
                    @elseif($qr_generator_signer->status === 'rejected')
                        <div style="color: #dc3545; font-size: 18px; font-weight: bold;">
                            ✗ Surat Ditolak
                        </div>
                        <div style="color: #6c757d; font-size: 14px; margin-top: 10px;">
                            Surat ini telah ditolak oleh {{ $selected_signer['name'] }}
                        </div>
                        @if($qr_generator_signer->rejection_notes)
                            <div style="background-color: white; padding: 15px; margin-top: 15px; border-radius: 5px; border-left: 3px solid #dc3545; text-align: left;">
                                <strong>Alasan Penolakan:</strong><br>
                                {{ $qr_generator_signer->rejection_notes }}
                            </div>
                        @endif
                    @endif
                </div>
            @endif

            <!-- Rejection Modal -->
            <div id="rejectModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
                <div style="background: white; padding: 30px; border-radius: 8px; max-width: 500px; width: 90%;">
                    <h3 style="margin-bottom: 20px; color: #333;">Alasan Penolakan</h3>
                    <form action="{{ route('mail.approval.reject', $qr_generator_qr_signer_id) }}" method="POST">
                        @csrf
                        
                        <!-- Show signer info (already bound to this record) -->
                        <div style="margin-bottom: 15px; padding: 10px; background-color: #f0f0f0; border-radius: 5px;">
                            <label style="display: block; margin-bottom: 5px; font-weight: bold; color: #333; font-size: 14px;">
                                Penanda Tangan yang Menolak
                            </label>
                            <div style="color: #333; font-size: 14px;">
                                {{ $selected_signer['name'] ?? '-' }} - {{ $selected_signer['position'] ?? '-' }}
                            </div>
                        </div>

                        <div style="margin-bottom: 15px;">
                            <label style="display: block; margin-bottom: 5px; font-weight: bold; color: #333; font-size: 14px;">
                                Alasan Penolakan
                            </label>
                            <textarea name="rejection_reason" 
                                      required 
                                      placeholder="Masukkan alasan penolakan..."
                                      style="width: 100%; min-height: 120px; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px; font-family: Arial, sans-serif; resize: vertical;"></textarea>
                            @error('rejection_reason')
                                <span style="color: #dc3545; font-size: 12px;">{{ $message }}</span>
                            @enderror
                        </div>

                        <div style="margin-top: 20px; display: flex; gap: 10px; justify-content: flex-end;">
                            <button type="button" onclick="hideRejectModal()" style="padding: 10px 20px; background: #6c757d; color: white; border: none; border-radius: 5px; cursor: pointer;">
                                Batal
                            </button>
                            <button type="submit" style="padding: 10px 20px; background: #dc3545; color: white; border: none; border-radius: 5px; cursor: pointer;">
                                Tolak Surat
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <script>
                function showRejectModal() {
                    document.getElementById('rejectModal').style.display = 'flex';
                }
                
                function hideRejectModal() {
                    document.getElementById('rejectModal').style.display = 'none';
                }

                // Close modal when clicking outside
                document.getElementById('rejectModal').addEventListener('click', function(e) {
                    if (e.target === this) {
                        hideRejectModal();
                    }
                });
            </script>
        </div>
    </div>
</body>
</html>
