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

        $transaction->balance = $this->calculateNewBalance($transaction, $previousBalance);
        $transaction->save();
    }

    /**
     * Calculate the new balance based on transaction type and amount
     */
    protected function calculateNewBalance(Finance $transaction, float $previousBalance): float
    {
        if ($transaction->transactionCategory &&
            $transaction->transactionCategory->transaction_type === FinanceTypeEnum::INCOME->value) {
            return $previousBalance + $transaction->amount;
        }

        return $previousBalance - $transaction->amount;
    }
}
