<?php

namespace App\Filament\Resources\Finance\TransactionResource\Pages;

use App\Filament\Resources\Finance\TransactionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTransactions extends ListRecords
{
    protected static string $resource = TransactionResource::class;

    protected function getHeaderActions(): array
    {
        /**
         * @var string
         */
        $createLabel = config('constants.resources.transaction.create');

        return [

        ];
    }
}
