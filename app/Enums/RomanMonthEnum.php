<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use InvalidArgumentException;

enum RomanMonthEnum: string implements HasLabel
{
    case I = 'I';
    case II = 'II';
    case III = 'III';
    case IV = 'IV';
    case V = 'V';
    case VI = 'VI';
    case VII = 'VII';
    case VIII = 'VIII';
    case IX = 'IX';
    case X = 'X';
    case XI = 'XI';
    case XII = 'XII';

    public function getLabel(): string
    {
        return match ($this) {
            self::I => 'I',
            self::II => 'II',
            self::III => 'III',
            self::IV => 'IV',
            self::V => 'V',
            self::VI => 'VI',
            self::VII => 'VII',
            self::VIII => 'VIII',
            self::IX => 'IX',
            self::X => 'X',
            self::XI => 'XI',
            self::XII => 'XII',
        };
    }

    public static function fromNumber(int $month): self
    {
        if ($month < 1 || $month > 12) {
            throw new InvalidArgumentException('Month must be between 1 and 12');
        }

        return match ($month) {
            1 => self::I,
            2 => self::II,
            3 => self::III,
            4 => self::IV,
            5 => self::V,
            6 => self::VI,
            7 => self::VII,
            8 => self::VIII,
            9 => self::IX,
            10 => self::X,
            11 => self::XI,
            12 => self::XII,
        };
    }
}
