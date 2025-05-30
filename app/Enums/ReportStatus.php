<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum ReportStatus: int implements HasLabel
{
    case DRAFT = 0;
    case SUBMITTED = 1;

    public function getLabel(): string
    {
        return match ($this) {
            self::DRAFT => 'Dalam Pengerjaan',
            self::SUBMITTED => 'Selesai',
        };
    }
}
