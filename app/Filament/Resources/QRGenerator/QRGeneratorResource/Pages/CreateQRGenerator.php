<?php

namespace App\Filament\Resources\QRGenerator\QRGeneratorResource\Pages;

use App\Filament\Resources\QRGenerator\QRGeneratorResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateQRGenerator extends CreateRecord
{
    protected static string $resource = QRGeneratorResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
