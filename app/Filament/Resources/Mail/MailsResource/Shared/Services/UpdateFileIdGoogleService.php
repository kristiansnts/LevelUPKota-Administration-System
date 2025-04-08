<?php

namespace App\Filament\Resources\Mail\MailsResource\Shared\Services;

use App\Models\Mail;
use Illuminate\Support\Facades\Storage;

class UpdateFileIdGoogleService
{
    public function updateFileId(Mail $mail): void
    {
        // Skip if no file was uploaded
        if (empty($mail->file_name)) {
            return;
        }
        
        // Get metadata from Google Drive
        $metadata = Storage::disk('google')->getMetadata($mail->file_name);
        
        // Update the file_id field with the Google Drive file ID
        if (isset($metadata['path'])) {
            $mail->file_id = $metadata['path'];
            $mail->save();
        }
    }
}