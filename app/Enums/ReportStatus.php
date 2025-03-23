<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum ReportStatus: string implements HasLabel
{
    case DRAFT = 'false';
    case SUBMITTED = 'true';

    public function getLabel(): string
    {
        return match ($this) {
            self::DRAFT => 'Dalam Pengerjaan',
            self::SUBMITTED => 'Selesai',
        };
    }
}
