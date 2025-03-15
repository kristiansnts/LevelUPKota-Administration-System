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
        return [
            Actions\CreateAction::make()
                ->label('Tambah Periode Transaksi'),
        ];
    }
}
