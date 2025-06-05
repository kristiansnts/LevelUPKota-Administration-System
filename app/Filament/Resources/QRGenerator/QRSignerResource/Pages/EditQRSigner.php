<?php

namespace App\Filament\Resources\QRGenerator\QRSignerResource\Pages;

use App\Filament\Resources\QRGenerator\QRSignerResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditQRSigner extends EditRecord
{
    protected static string $resource = QRSignerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
