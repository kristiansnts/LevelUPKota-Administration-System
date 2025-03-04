<?php

namespace App\Filament\Resources\Mail\MailsResource\Pages;

use App\Filament\Resources\Mail\MailsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMails extends EditRecord
{
    protected static string $resource = MailsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
