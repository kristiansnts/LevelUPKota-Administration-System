<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum FinanceTypeEnum: string implements HasLabel
{
    case INCOME = 'income';
    case EXPENSE = 'expense';

    public function getLabel(): string
    {
        return match ($this) {
            self::INCOME => 'Pemasukan',
            self::EXPENSE => 'Pengeluaran',
        };
    }
}
