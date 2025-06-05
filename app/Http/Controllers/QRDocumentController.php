<?php

namespace App\Http\Controllers;

use App\Models\QrGeneratorSigner;
use App\Models\QRGenerator;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;

class QRDocumentController extends Controller
{
    public function show($qrGeneratorId)
    {
        // Find the QR Generator
        $qrGenerator = QRGenerator::with('document')->findOrFail($qrGeneratorId);
        $document = $qrGenerator->document;
        
        // Get all signers for this QR Generator
        $allSigners = QrGeneratorSigner::where('qr_generator_id', $qrGeneratorId)
            ->with('qrSigner')
            ->get();

        // Handle mail_date - convert to Carbon if it's a string
        $mailDate = null;
        if ($document->mail_date) {
            if (is_string($document->mail_date)) {
                try {
                    $mailDate = Carbon::parse($document->mail_date);
                } catch (\Exception $e) {
                    $mailDate = null;
                }
            } else {
                $mailDate = $document->mail_date;
            }
        }

        $data = [
            'document_number' => $document->mail_code ?? '-',
            'created_date' => $mailDate ? $mailDate->format('d F Y') : '-',
            'created_time' => $mailDate ? $mailDate->format('H.i') : '-',
            'document_title' => $document->description ?? '-',
            'producer_organization' => 'LEVELUP',
            'producer_location' => 'Ngawi',
            'producer_full' => 'LEVELUP Ngawi',
            'produced_date' => $document->created_at ? $document->created_at->format('d F Y') : '-',
            'produced_time' => $document->created_at ? $document->created_at->format('H.i') : '-',
            'organization_name' => 'LEVELUP KOTA',
            'organization_subtitle' => 'Administration System',
            'brand_name' => 'LEVELUP',
            'logo_icon' => 'LU',
            'signatures' => $allSigners->map(function ($signer) {
                return [
                    'name' => $signer->qrSigner->signer_name ?? '-',
                    'position' => $signer->qrSigner->signer_position ?? '-',
                    'status' => $signer->is_sign ? 'Telah ditandatangani' : 'Belum ditandatangani',
                    'status_en' => $signer->is_sign ? 'signed' : 'not signed',
                ];
            })->toArray(),
        ];

        return view('public.qr-document', $data);
    }

    private function getPublicUrl($qrGeneratorId)
    {
        // Check if we're in production or have a custom domain
        if (config('app.env') === 'production' || !empty(config('app.url'))) {
            return route('qr.document.show', ['qrGeneratorId' => $qrGeneratorId]);
        }
        
        // For development, you need to replace this with your actual domain or ngrok URL
        // Option 1: Use your actual domain
        // return 'https://yourdomain.com/qr-document/' . $qrGeneratorId;
        
        // Option 2: Use ngrok for testing (replace with your ngrok URL)
        // return 'https://0177-110-138-198-201.ngrok-free.app/qr-document/' . $qrGeneratorId;
        
        // Option 3: Use your local IP address (replace with your actual IP)
        return 'http://192.168.1.3:8000/qr-document/' . $qrGeneratorId;
        
        // Default fallback
        // return route('qr.document.show', ['qrGeneratorId' => $qrGeneratorId]);
    }

    public function downloadQrCode($qrGeneratorId)
    {
        // Verify that the QR Generator exists
        $qrGenerator = QRGenerator::findOrFail($qrGeneratorId);
        
        // Generate the full URL to the document
        $url = $this->getPublicUrl($qrGeneratorId);
        
        // Debug: Log the URL being generated
        \Log::info('QR Code URL: ' . $url);
        
        // Get logo path
        $logoPath = public_path('images/Logo_LUP.png');
        
        // Generate QR code using Builder with logo
        $builder = new Builder(
            writer: new PngWriter(),
            writerOptions: [],
            validateResult: false,
            data: $url,
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::Medium,
            size: 400,
            margin: 10,
            roundBlockSizeMode: RoundBlockSizeMode::Margin,
            logoPath: $logoPath,
            logoResizeToWidth: 200,
            logoResizeToHeight: 100,
            logoPunchoutBackground: true
        );
        
        $result = $builder->build();

        // Get document info for filename
        $document = $qrGenerator->document;
        $filename = 'QR_' . ($document->mail_code ?? 'Document') . '_' . date('Y-m-d') . '.png';

        return response($result->getString())
            ->header('Content-Type', 'image/png')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    public function generateQrCode($qrGeneratorId)
    {
        // Verify that the QR Generator exists
        $qrGenerator = QRGenerator::findOrFail($qrGeneratorId);
        
        // Generate the full URL to the document
        $url = $this->getPublicUrl($qrGeneratorId);
        
        // Get logo path
        $logoPath = public_path('images/Logo_LUP.png');
        
        // Generate QR code with logo
        $builder = new Builder(
            writer: new PngWriter(),
            writerOptions: [],
            validateResult: false,
            data: $url,
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::Medium,
            size: 400,
            margin: 10,
            roundBlockSizeMode: RoundBlockSizeMode::Margin,
            logoPath: $logoPath,
            logoResizeToWidth: 200,
            logoResizeToHeight: 100,
            logoPunchoutBackground: true
        );
        
        $result = $builder->build();

        return response($result->getString())
            ->header('Content-Type', 'image/png');
    }

    public function previewQrCode($qrGeneratorId)
    {
        // Verify that the QR Generator exists
        $qrGenerator = QRGenerator::findOrFail($qrGeneratorId);
        
        return view('public.qr-preview', [
            'qrGeneratorId' => $qrGeneratorId
        ]);
    }

    // Add this method for debugging
    public function debugUrl($qrGeneratorId)
    {
        $url = $this->getPublicUrl($qrGeneratorId);
        return response()->json(['url' => $url]);
    }
} 