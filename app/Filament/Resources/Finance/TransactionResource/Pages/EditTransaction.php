<?php

namespace App\Filament\Resources\Finance\TransactionResource\Pages;

use App\Enums\FinanceTypeEnum;
use App\Filament\Resources\Finance\TransactionResource;
use App\Models\Transaction;
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

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['report_id'] = $this->record->report_id;
        return $data;
    }

    protected function afterSave(): void
    {
        /**
         * @var Transaction $transaction
         */
        $transaction = $this->record;
        $transactionId = $transaction->getKey();

        $affectedTransactions = Transaction::where('id', '>=', $transactionId)
            ->orderBy('id')
            ->get();

        $previousTransaction = Transaction::where('id', '<', $transactionId)
            ->latest('id')
            ->first();

        $runningBalance = $previousTransaction ? $previousTransaction->balance : 0;

        foreach ($affectedTransactions as $affectedTransaction) {
            if ($affectedTransaction->transactionCategory && $affectedTransaction->transactionCategory->transaction_type === FinanceTypeEnum::INCOME->value) {
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

    public function getRedirectUrl(): string
    {
        return $this->previousUrl;
    }
}
