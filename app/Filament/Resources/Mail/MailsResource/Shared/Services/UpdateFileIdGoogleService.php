<?php

namespace App\Filament\Resources\Mail\MailsResource\Shared\Services;

use App\Models\Mail;
use Illuminate\Support\Facades\Storage;

class UpdateFileIdGoogleService
{
    public function updateFileId(Mail $mail)
    {
        $adapter = Storage::disk('google')->getAdapter();
        $fileId = $adapter->getMetadata($mail->file_name);
        $mail->file_id = $fileId['extraMetadata']['id'];
        $mail->save();
    }
}