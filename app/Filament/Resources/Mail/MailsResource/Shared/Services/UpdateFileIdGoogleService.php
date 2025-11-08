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
        
        try {
            // Check if file exists on Google Drive
            if (!Storage::disk('google')->exists($mail->file_name)) {
                return;
            }
            
            // Get the Google Drive adapter and file ID
            $adapter = Storage::disk('google')->getAdapter();
            
            // Get file ID from the adapter
            if (method_exists($adapter, 'getMetadata')) {
                $metadata = $adapter->getMetadata($mail->file_name);
                $fileId = $metadata['path'] ?? null;
            } else {
                // For newer versions, try to get the path directly
                // The file_name already contains the Google Drive file ID
                $fileId = $mail->file_name;
            }
            
            // Update the file_id and file_url fields
            if ($fileId) {
                $mail->file_id = $fileId;
                $mail->file_url = "https://drive.google.com/file/d/{$fileId}/preview";
                $mail->save();
            }
        } catch (\Exception $e) {
            // Log the error but don't fail the entire operation
            \Log::warning("Failed to update Google Drive file ID for mail {$mail->id}: " . $e->getMessage());
        }
    }
}