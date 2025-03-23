<?php

namespace App\Filament\Resources\Finance\ReportResource\Pages;

use App\Filament\Resources\Finance\ReportResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditReport extends EditRecord
{
    protected static string $resource = ReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\Action::make('submitReport')
                ->label('Laporan Selesai')
                ->action(function (Report $record) {
                    $record->is_done = ReportStatus::SUBMITTED;
                    $record->save();
                })
                ->color('success'),
        ];
    }
}
