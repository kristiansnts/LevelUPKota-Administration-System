<?php

namespace App\Filament\Resources\Mail\CityCodeResource\Pages;

use App\Filament\Resources\Mail\CityCodeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCityCodes extends ListRecords
{
    protected static string $resource = CityCodeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Kode Kota')
                ->icon('heroicon-o-plus'),
        ];
    }
}
