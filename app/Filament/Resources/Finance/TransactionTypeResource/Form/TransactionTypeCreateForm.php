<?php

namespace App\Filament\Resources\Finance\TransactionTypeResource\Form;

use Filament\Forms;

class TransactionTypeCreateForm
{
    /**
     * @return array<int, Forms\Components\Component>
     */
    public static function getFormSchema(): array
    {
        return [
            Forms\Components\TextInput::make('name')
                ->label('Nama')
                ->placeholder('Masukkan nama tipe transaksi co: Cash, QRIS, Transfer BCA a/n ...')
                ->required()
                ->maxLength(255),
            Forms\Components\Textarea::make('description')
                ->label('Deskripsi')
                ->placeholder('Masukkan detail tipe transaksi')
                ->required()
                ->rows(3),
        ];
    }
}
