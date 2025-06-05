<?php

namespace App\Http\Controllers;

use App\Models\QrGeneratorSigner;
use App\Models\QRGenerator;
use Illuminate\Http\Request;
use Carbon\Carbon;

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
            'producer_organization' => $document->sender_name ?? '-',
            'producer_full' => $document->sender_name ?? '-',
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
} 