<?php

namespace App\Filament\Resources\Finance\TransactionTypeResource\Pages;

use App\Filament\Resources\Finance\TransactionTypeResource;
use App\Filament\Shared\Services\ModelQueryService;
use App\Models\TransactionTypeUser;
use Filament\Resources\Pages\CreateRecord;

class CreateTransactionType extends CreateRecord
{
    protected static string $resource = TransactionTypeResource::class;

    public function getRecord(): ?\App\Models\TransactionType
    {
        /** @var \App\Models\TransactionType|null */
        return $this->record;
    }

    protected function afterCreate(): void
    {
        if (!$this->record) {
            return;
        }

        $user = ModelQueryService::getUserModel();

        TransactionTypeUser::create([
            'transaction_type_id' => $this->getRecord()?->id,
            'city_id' => $user->city_id,
            'district_id' => $user->district_id ?? null,
        ]);
    }
}
