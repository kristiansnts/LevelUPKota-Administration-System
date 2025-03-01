<?php

namespace App\Filament\Resources\Mail\MailsInResource\Pages;

use App\Filament\Resources\Mail\MailsInResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMailsIn extends EditRecord
{
    protected static string $resource = MailsInResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
