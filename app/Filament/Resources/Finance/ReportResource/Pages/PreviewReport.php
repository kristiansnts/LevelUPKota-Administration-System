<?php

namespace App\Filament\Resources\Finance\ReportResource\Pages;

use App\Filament\Resources\Finance\ReportResource;
use Filament\Resources\Pages\EditRecord;

class PreviewReport extends EditRecord
{
    protected static string $resource = ReportResource::class;

    protected static ?string $title = 'Lihat Laporan';


    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function getFormActions(): array
    {
        return [];
    }

}
