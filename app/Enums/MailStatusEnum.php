<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum MailStatusEnum: string implements HasLabel
{
    case DRAFT = 'draft';
    case UPLOADED = 'uploaded';

    public function getLabel(): string
    {
        return match ($this) {
            self::DRAFT => 'Surat Belum diupload',
            self::UPLOADED => 'Surat Sudah diupload',
        };
    }
}
