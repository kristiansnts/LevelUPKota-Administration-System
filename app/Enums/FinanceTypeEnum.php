<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum FinanceTypeEnum: string implements HasLabel
{
    case INCOME = 'income';
    case OUTCOME = 'outcome';

    public function getLabel(): string
    {
        return match ($this) {
            self::INCOME => 'Pemasukan',
            self::OUTCOME => 'Pengeluaran',
        };
    }
}
