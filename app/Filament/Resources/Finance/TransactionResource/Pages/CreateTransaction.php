<?php

namespace App\Filament\Resources\Finance\TransactionResource\Pages;

use App\Filament\Resources\Finance\TransactionResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTransaction extends CreateRecord
{
    protected static string $resource = TransactionResource::class;
}
