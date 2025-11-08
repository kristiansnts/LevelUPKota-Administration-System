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
            'signatures' => $allSigners->map(function ($signer) use ($document) {
                $phoneNumber = $signer->qrSigner->phone_number ?? null;
                $whatsappUrl = null;
                
                // Only show WhatsApp button if status is draft
                if ($phoneNumber && $signer->status === 'draft') {
                    // Format phone number
                    $phone = preg_replace('/[^0-9]/', '', $phoneNumber);
                    if (substr($phone, 0, 1) === '0') {
                        $phone = '62' . substr($phone, 1);
                    }
                    
                    // Create WhatsApp message with qr_generator_qr_signer_id in URL
                    $description = $document->description ?? 'surat';
                    $approvalUrl = url('/approval/' . $signer->qr_generator_qr_signer_id);
                    $message = "Shalom Bapak/Ibu, kami dari ALK LevelUP memohon izin meminta tanda tangan untuk persetujuan {$description}. Apabila berkenan bisa klik link dibawah ini terimakasih.\n\n{$approvalUrl}";
                    
                    $whatsappUrl = 'https://wa.me/' . $phone . '?text=' . urlencode($message);
                }
                
                // Determine status display
                $statusDisplay = 'Belum ditandatangani';
                $statusEn = 'not signed';
                
                if ($signer->status === 'approved') {
                    $statusDisplay = 'Telah ditandatangani';
                    $statusEn = 'signed';
                } elseif ($signer->status === 'rejected') {
                    $statusDisplay = 'Ditolak';
                    $statusEn = 'rejected';
                }
                
                return [
                    'name' => $signer->qrSigner->signer_name ?? '-',
                    'position' => $signer->qrSigner->signer_position ?? '-',
                    'status' => $statusDisplay,
                    'status_en' => $statusEn,
                    'is_signed' => $signer->status === 'approved',
                    'has_rejected' => $signer->status === 'rejected',
                    'phone_number' => $phoneNumber,
                    'whatsapp_url' => $whatsappUrl,
                ];
            })->toArray(),
        ];

        return view('public.qr-document', $data);
    }

    private function getPublicUrl($qrGeneratorId)
    {
        // Use the configured app URL
        $baseUrl = config('app.url');
        
        // If app URL is set and not localhost, use it
        if (!empty($baseUrl) && !str_contains($baseUrl, 'localhost') && !str_contains($baseUrl, '127.0.0.1')) {
            return $baseUrl . '/qr-document/' . $qrGeneratorId;
        }
        
        // Fallback to route helper
        return route('qr.document.show', ['qrGeneratorId' => $qrGeneratorId]);
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
            logoResizeToWidth: 140,
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
            logoResizeToWidth: 140,
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
} 