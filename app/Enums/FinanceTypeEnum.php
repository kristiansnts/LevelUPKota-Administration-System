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

    public function getDescription(): string
    {
        return match ($this) {
            self::INCOME => 'Dana yang diterima atau masuk ke kas',
            self::EXPENSE => 'Dana yang dikeluarkan atau keluar dari kas',
        };
    }

    public static function toSelectOptionsWithDescription(): array
    {
        $options = [];
        foreach (self::cases() as $case) {
            $options[$case->value] = $case->getLabel() . ' - ' . $case->value . "\n" . $case->getDescription();
        }
        return $options;
    }
}
