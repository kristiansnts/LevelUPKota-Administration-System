<?php

namespace App\Filament\Resources\Finance\TransactionResource\Pages;

use App\Enums\FinanceTypeEnum;
use App\Filament\Resources\Finance\TransactionResource;
use App\Models\Finance;
use Filament\Resources\Pages\CreateRecord;

class CreateTransaction extends CreateRecord
{
    protected static string $resource = TransactionResource::class;

    protected function afterCreate(): void
    {
        /**
         * @var Finance $transaction
         */
        $transaction = $this->record;
        $transactionId = $transaction->getKey();

        $lastTransaction = Finance::where('id', '<', $transactionId)
            ->latest('id')
            ->first();

        $previousBalance = $lastTransaction ? $lastTransaction->balance : 0;

        if ($transaction->transactionCategory && $transaction->transactionCategory->type === FinanceTypeEnum::INCOME->value) {
            $transaction->balance = $previousBalance + $transaction->amount;
        } else {
            $transaction->balance = $previousBalance - $transaction->amount;
        }

        $transaction->save();
    }
}
