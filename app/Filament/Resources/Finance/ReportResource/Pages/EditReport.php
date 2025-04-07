<?php

namespace App\Filament\Resources\Finance\ReportResource\Pages;

use App\Filament\Resources\Finance\ReportResource;
use App\Models\Report;
use App\Enums\ReportStatus;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditReport extends EditRecord
{
    protected static string $resource = ReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->before(function (Report $record) {
                    $record->users()->detach();
                }),
            Actions\Action::make('submitReport')
                ->label('Laporan Selesai')
                ->action(function (Report $record) {
                    $record->is_done = ReportStatus::SUBMITTED;
                    $record->save();
                    
                    return redirect()->to(ReportResource::getUrl('index'));
                })
                ->color('success'),
        ];
    }

    public function mount($record): void
    {
        parent::mount($record);

        if ($this->record->is_done === ReportStatus::SUBMITTED) {
            $this->redirect(ReportResource::getUrl('preview', ['record' => $record]));
        }
    }
}
