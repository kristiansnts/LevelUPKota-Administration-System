<?php

namespace App\Filament\Resources\QRGenerator\QRGeneratorResource\Pages;

use App\Filament\Resources\QRGenerator\QRGeneratorResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditQRGenerator extends EditRecord
{
    protected static string $resource = QRGeneratorResource::class;

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
