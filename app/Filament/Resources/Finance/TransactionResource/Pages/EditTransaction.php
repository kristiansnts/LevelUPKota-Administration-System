<?php

namespace App\Filament\Resources\Finance\TransactionResource\Pages;

use App\Filament\Resources\Finance\TransactionResource;
use App\Models\Transaction;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\Finance\TransactionResource\Shared\Services\TransactionService;

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

        (new TransactionService())->updateTransaction($transaction, $transactionId);
    }

    public function getRedirectUrl(): string
    {
        return $this->previousUrl;
    }
}
