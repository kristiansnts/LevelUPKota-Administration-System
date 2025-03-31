<?php

namespace App\Filament\Resources\Finance\ReportResource\Pages;

use App\Filament\Resources\Finance\ReportResource;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Shared\Services\ModelQueryService;
use App\Models\ReportUser;

class CreateReport extends CreateRecord
{
    protected static string $resource = ReportResource::class;

    protected function afterCreate(): void
    {
        if (! $this->record instanceof \App\Models\Report) {
            return;
        }

        $user = ModelQueryService::getUserModel();

        ReportUser::create([
            'report_id' => $this->record->id,
            'city_id' => $user->city_id,
            'district_id' => $user->district_id ?? null,
        ]);
    }

    protected function getFormActions(): array
    {
        return [
            $this->getCreateFormAction()->label('Simpan'),
            $this->getCancelFormAction(),
        ];
    }
}
