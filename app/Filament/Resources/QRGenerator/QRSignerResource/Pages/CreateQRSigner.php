<?php

namespace App\Filament\Resources\QRGenerator\QRSignerResource\Pages;

use App\Filament\Resources\QRGenerator\QRSignerResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateQRSigner extends CreateRecord
{
    protected static string $resource = QRSignerResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
