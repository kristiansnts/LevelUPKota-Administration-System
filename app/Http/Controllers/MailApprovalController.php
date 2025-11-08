<?php

namespace App\Http\Controllers;

use App\Helpers\StringHelper;
use App\Models\Mail;
use Illuminate\Http\Request;

class MailApprovalController extends Controller
{
    public function show($qr_generator_qr_signer_id)
    {
        // Find the specific signer record
        $qrGeneratorSigner = \App\Models\QrGeneratorSigner::with(['qrSigner', 'qrGenerator.document'])
            ->findOrFail($qr_generator_qr_signer_id);
        
        $mail = $qrGeneratorSigner->qrGenerator->document;

        // Use StringHelper to get the mail link
        $fileUrl = StringHelper::getMailLink($mail->file_name);
        $fileExists = !empty($fileUrl);
        $isGoogleDrive = !empty($fileUrl);

        // Get signer info
        $selectedSigner = [
            'id' => $qrGeneratorSigner->qr_signer_id,
            'name' => $qrGeneratorSigner->qrSigner->signer_name ?? '-',
            'position' => $qrGeneratorSigner->qrSigner->signer_position ?? '-',
        ];

        return view('public.mail-approval', [
            'mail' => $mail,
            'mail_code' => $mail->mail_code,
            'description' => $mail->description,
            'file_name' => $mail->file_name,
            'file_id' => $mail->file_id,
            'file_exists' => $fileExists,
            'file_url' => $fileUrl,
            'is_google_drive' => $isGoogleDrive,
            'qr_generator_qr_signer_id' => $qr_generator_qr_signer_id,
            'selected_signer' => $selectedSigner,
            'qr_generator_signer' => $qrGeneratorSigner,
        ]);
    }

    public function approve(Request $request, $qr_generator_qr_signer_id)
    {
        // Find and update the specific signer record
        $qrGeneratorSigner = \App\Models\QrGeneratorSigner::findOrFail($qr_generator_qr_signer_id);
        
        $qrGeneratorSigner->update(['status' => 'approved']);
        
        return redirect()->back()->with('success', 'Surat berhasil disetujui!');
    }

    public function reject(Request $request, $qr_generator_qr_signer_id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);

        // Find and update the specific signer record
        $qrGeneratorSigner = \App\Models\QrGeneratorSigner::findOrFail($qr_generator_qr_signer_id);
        
        $qrGeneratorSigner->update([
            'status' => 'rejected',
            'rejection_notes' => $request->rejection_reason,
        ]);
        
        return redirect()->back()->with('success', 'Surat berhasil ditolak!');
    }
}
