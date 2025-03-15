<?php

namespace App\Filament\Resources\Finance\TransactionResource\Pages;

use App\Enums\FinanceTypeEnum;
use App\Filament\Resources\Finance\TransactionResource;
use App\Models\Finance;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTransaction extends EditRecord
{
    protected static string $resource = TransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        /**
         * @var Finance $transaction
         */
        $transaction = $this->record;
        $transactionId = $transaction->getKey();

        $affectedTransactions = Finance::where('id', '>=', $transactionId)
            ->orderBy('id')
            ->get();

        $previousTransaction = Finance::where('id', '<', $transactionId)
            ->latest('id')
            ->first();

        $runningBalance = $previousTransaction ? $previousTransaction->balance : 0;

        foreach ($affectedTransactions as $affectedTransaction) {
            if ($affectedTransaction->transactionCategory && $affectedTransaction->transactionCategory->type === FinanceTypeEnum::INCOME->value) {
                $runningBalance += $affectedTransaction->amount;
            } else {
                $runningBalance -= $affectedTransaction->amount;
            }

            if ($affectedTransaction->balance !== $runningBalance) {
                $affectedTransaction->balance = $runningBalance;
                $affectedTransaction->saveQuietly();
            }
        }
    }
}
