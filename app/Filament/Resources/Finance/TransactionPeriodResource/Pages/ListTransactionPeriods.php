<?php

namespace App\Filament\Resources\Finance\TransactionPeriodResource\Pages;

use App\Filament\Resources\Finance\TransactionPeriodResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTransactionPeriods extends ListRecords
{
    protected static string $resource = TransactionPeriodResource::class;

    protected function getHeaderActions(): array
    {
        /** @var string $createActionLabel */
        $createActionLabel = config('constants.resources.transactionPeriod.create');

        return [
            Actions\CreateAction::make()
                ->label($createActionLabel),
        ];
    }
}
