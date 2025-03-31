<?php

namespace App\Filament\Resources\Finance\TransactionResource\Pages;

use App\Enums\FinanceTypeEnum;
use App\Filament\Resources\Finance\TransactionResource;
use App\Models\Transaction;
use Filament\Resources\Pages\CreateRecord;
use Filament\Actions;
use App\Filament\Shared\Services\ModelQueryService;
use App\Models\TransactionUser;

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

        if (! $this->record instanceof \App\Models\Transaction) {
            return;
        }

        $user = ModelQueryService::getUserModel();

        TransactionUser::create([
            'transaction_id' => $this->record->id,
            'city_id' => $user->city_id,
            'district_id' => $user->district_id ?? null,
        ]);

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
