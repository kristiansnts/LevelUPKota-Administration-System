<?php

namespace App\Enums;

enum FinanceType: string
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
