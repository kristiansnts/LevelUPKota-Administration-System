<?php

namespace App\Filament\Resources\QRGenerator\QRSignerResource\Pages;

use App\Filament\Resources\QRGenerator\QRSignerResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListQRSigners extends ListRecords
{
    protected static string $resource = QRSignerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Tambah Penanda Tangan'),
        ];
    }
}
