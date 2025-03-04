<?php

namespace App\Filament\Resources\Finance\TransactionCategoryResource\Pages;

use App\Filament\Resources\Finance\TransactionCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTransactionCategory extends EditRecord
{
    protected static string $resource = TransactionCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
