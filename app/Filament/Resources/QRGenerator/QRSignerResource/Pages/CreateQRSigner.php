<?php

namespace App\Filament\Resources\QRGenerator\QRSignerResource\Pages;

use App\Filament\Resources\QRGenerator\QRSignerResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateQRSigner extends CreateRecord
{
    protected static string $resource = QRSignerResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = auth()->user();
        
        // Auto-fill city_id and district_id from authenticated user
        $data['city_id'] = $user->city_id;
        $data['district_id'] = $user->district_id;
        
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
