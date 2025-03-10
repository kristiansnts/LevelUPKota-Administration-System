<?php

namespace App\Filament\Resources\Finance\TransactionTypeResource\Pages;

use App\Filament\Resources\Finance\TransactionTypeResource;
use App\Filament\Shared\Services\ModelQueryService;
use App\Models\TransactionTypeUser;
use Filament\Resources\Pages\CreateRecord;

class CreateTransactionType extends CreateRecord
{
    protected static string $resource = TransactionTypeResource::class;

    protected function afterCreate(): void
    {
        if (! $this->record instanceof \App\Models\TransactionType) {
            return;
        }

        $user = ModelQueryService::getUserModel();

        TransactionTypeUser::create([
            'transaction_type_id' => $this->record->id,
            'city_id' => $user->city_id,
            'district_id' => $user->district_id ?? null,
        ]);
    }
}
