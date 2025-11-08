<?php

namespace App\Filament\Resources\QRGenerator\QRGenSigResource\Pages;

use App\Filament\Resources\QRGenerator\QRGenSigResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListQRGenSigs extends ListRecords
{
    protected static string $resource = QRGenSigResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make()->label('Buat Tanda Tangan QR'),
        ];
    }
}
