<?php

namespace App\Filament\Resources\Finance\TransactionResource\Pages;

use App\Filament\Resources\Finance\TransactionResource;
use App\Models\Transaction;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\Finance\TransactionResource\Shared\Services\TransactionService;
use App\Filament\Resources\Finance\TransactionResource\Shared\Services\CreatePivotService;

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
        if (! $this->record instanceof Transaction) {
            return;
        }
        /**
         * @var Transaction $transaction
         */
        $transaction = $this->record;
        $transactionId = $transaction->getKey();

        CreatePivotService::make()->createTransactionUserPivot($transactionId);

        (new TransactionService())->createTransaction($transaction, $transactionId);
    }

    public function getRedirectUrl(): string
    {
        return $this->previousUrl;
    }
}
