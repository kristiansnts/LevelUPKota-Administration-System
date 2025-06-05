<?php

namespace App\Filament\Resources\QRGenerator\QRGeneratorResource\Pages;

use App\Filament\Resources\QRGenerator\QRGeneratorResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListQRGenerators extends ListRecords
{
    protected static string $resource = QRGeneratorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
