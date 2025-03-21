<?php

namespace App\Filament\Resources\Finance\TransactionCategoryResource\Pages;

use App\Filament\Resources\Finance\TransactionCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTransactionCategories extends ListRecords
{
    protected static string $resource = TransactionCategoryResource::class;

    protected function getHeaderActions(): array
    {
        /** @var string $createActionLabel */
        $createActionLabel = config('constants.resources.transactionCategory.create');

        return [
            Actions\CreateAction::make()
                ->label($createActionLabel),
        ];
    }
}
