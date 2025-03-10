<?php

namespace App\Filament\Resources\Finance\TransactionCategoryResource\Pages;

use App\Filament\Resources\Finance\TransactionCategoryResource;
use App\Filament\Shared\Services\ModelQueryService;
use App\Models\TransactionCategoryUser;
use Filament\Resources\Pages\CreateRecord;

class CreateTransactionCategory extends CreateRecord
{
    protected static string $resource = TransactionCategoryResource::class;

    protected function afterCreate(): void
    {
        if (! $this->record instanceof \App\Models\TransactionCategory) {
            return;
        }

        $user = ModelQueryService::getUserModel();

        TransactionCategoryUser::create([
            'transaction_category_id' => $this->record->id,
            'city_id' => $user->city_id,
            'district_id' => $user->district_id ?? null,
        ]);
    }
}
