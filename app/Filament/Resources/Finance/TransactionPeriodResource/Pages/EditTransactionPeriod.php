<?php

namespace App\Filament\Resources\Finance\TransactionPeriodResource\Pages;

use App\Filament\Resources\Finance\TransactionPeriodResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTransactionPeriod extends EditRecord
{
    protected static string $resource = TransactionPeriodResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
