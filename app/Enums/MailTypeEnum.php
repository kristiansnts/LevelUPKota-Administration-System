<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum MailTypeEnum: string implements HasLabel
{
    case IN = 'in';
    case OUT = 'out';

    public function getLabel(): string
    {
        return match ($this) {
            self::IN => 'Masuk',
            self::OUT => 'Keluar',
        };
    }
}
