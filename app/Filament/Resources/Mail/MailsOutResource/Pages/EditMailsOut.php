<?php

namespace App\Filament\Resources\Mail\MailsOutResource\Pages;

use App\Filament\Resources\Mail\MailsOutResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMailsOut extends EditRecord
{
    protected static string $resource = MailsOutResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
