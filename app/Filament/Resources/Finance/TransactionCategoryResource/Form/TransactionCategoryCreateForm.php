<?php

namespace App\Filament\Resources\Finance\TransactionCategoryResource\Form;

use App\Enums\FinanceTypeEnum;
use Filament\Forms;

class TransactionCategoryCreateForm
{
    /**
     * Get the form schema
     *
     * @return array<int, Forms\Components\Component>
     */
    public static function getFormSchema(): array
    {
        return [
            Forms\Components\TextInput::make('name')
                ->label('Nama')
                ->placeholder('Masukkan nama kategori transaksi co: Persembahan Kasih, Donasi, ...')
                ->required()
                ->maxLength(255),
            Forms\Components\Textarea::make('description')
                ->label('Deskripsi')
                ->placeholder('Masukkan detail kategori transaksi')
                ->required()
                ->rows(3),
            Forms\Components\Select::make('transaction_type')
                ->label('Tipe')
                ->options(FinanceTypeEnum::class)
                ->required(),
        ];
    }
}
