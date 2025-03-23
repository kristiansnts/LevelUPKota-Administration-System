<?php

namespace App\Filament\Resources\Finance\ReportResource\Pages;

use App\Filament\Resources\Finance\ReportResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateReport extends CreateRecord
{
    protected static string $resource = ReportResource::class;

    protected function getFormActions(): array
    {
        return [
            $this->getCreateFormAction()->label('Simpan'),
            $this->getCancelFormAction(),
        ];
    }
}
