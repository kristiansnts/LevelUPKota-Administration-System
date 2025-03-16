<?php

namespace App\Filament\Resources\Mail\CityCodeResource\Pages;

use App\Filament\Resources\Mail\CityCodeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCityCode extends EditRecord
{
    protected static string $resource = CityCodeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
