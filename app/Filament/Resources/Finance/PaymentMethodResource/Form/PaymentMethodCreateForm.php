<?php

namespace App\Filament\Resources\Finance\PaymentMethodResource\Form;

use Filament\Forms;

class PaymentMethodCreateForm
{
    /**
     * @return array<int, Forms\Components\Component>
     */
    public static function getFormSchema(): array
    {
        return [
            Forms\Components\TextInput::make('name')
                ->label('Metode Pembayaran')
                ->placeholder('Masukkan nama metode pembayaran co: Cash, QRIS, Transfer BCA a/n ...')
                ->required()
                ->maxLength(255),
            Forms\Components\Textarea::make('description')
                ->label('Deskripsi')
                ->placeholder('Masukkan detail metode pembayaran')
                ->required()
                ->rows(3),
        ];
    }
}
