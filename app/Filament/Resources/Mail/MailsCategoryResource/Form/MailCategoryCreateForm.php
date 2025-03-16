<?php

namespace App\Filament\Resources\Mail\MailsCategoryResource\Form;

use Filament\Forms\Components\TextInput;

class MailCategoryCreateForm
{
    /**
     * @return array<int, \Filament\Forms\Components\Component>
     */
    public static function getFormSchema(): array
    {
        return [
            TextInput::make('name')
                ->required()
                ->maxLength(255),
            TextInput::make('description')
                ->required()
                ->maxLength(255),
        ];
    }
}
