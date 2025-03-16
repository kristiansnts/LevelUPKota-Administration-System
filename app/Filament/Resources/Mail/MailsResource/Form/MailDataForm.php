<?php

namespace App\Filament\Resources\Mail\MailsResource\Form;

use App\Enums\MailTypeEnum;
use Filament\Forms;

class MailDataForm
{
    /**
     * @return array<int, \Filament\Forms\Components\Component>
     */
    public static function getFormSchema(): array
    {
        return [
            Forms\Components\TextInput::make('mail_code')
                ->label('Nomor Surat'),
            Forms\Components\TextInput::make('link')
                ->label('Upload Link Surat'),
            Forms\Components\Hidden::make('type')
                ->default(MailTypeEnum::IN->value),
        ];
    }
}
