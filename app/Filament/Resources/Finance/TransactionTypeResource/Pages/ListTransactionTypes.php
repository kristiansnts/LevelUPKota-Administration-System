<?php

namespace App\Filament\Resources\Finance\TransactionTypeResource\Pages;

use App\Filament\Resources\Finance\TransactionTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTransactionTypes extends ListRecords
{
    protected static string $resource = TransactionTypeResource::class;

    protected function getHeaderActions(): array
    {
        /**
         * @var string
         */
        $createLabel = config('constants.resources.transactionType.create');

        return [
            Actions\CreateAction::make()
                ->label($createLabel),
        ];
    }
}
