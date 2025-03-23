<?php

namespace App\Filament\Resources\Finance\TransactionResource\Pages;

use App\Enums\FinanceTypeEnum;
use App\Filament\Resources\Finance\TransactionResource;
use App\Models\Transaction;
use Filament\Resources\Pages\CreateRecord;
use Filament\Actions;

class CreateTransaction extends CreateRecord
{
    protected static string $resource = TransactionResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['report_id'] = $this->data['report_id'];
        return $data;
    }

    protected function afterCreate(): void
    {
        /**
         * @var Transaction $transaction
         */
        $transaction = $this->record;
        $transactionId = $transaction->getKey();

        $lastTransaction = Transaction::where('id', '<', $transactionId)
            ->latest('id')
            ->first();

        $previousBalance = $lastTransaction ? $lastTransaction->balance : 0;

        $transaction->balance = $this->calculateNewBalance($transaction, $previousBalance);
        $transaction->save();
    }

    /**
     * Calculate the new balance based on transaction type and amount
     */
    protected function calculateNewBalance(Transaction $transaction, float $previousBalance): float
    {
        if ($transaction->transactionCategory &&
            $transaction->transactionCategory->transaction_type === FinanceTypeEnum::INCOME->value) {
            return $previousBalance + $transaction->amount;
        }

        return $previousBalance - $transaction->amount;
    }

    public function getRedirectUrl(): string
    {
        return $this->previousUrl;
    }
}
