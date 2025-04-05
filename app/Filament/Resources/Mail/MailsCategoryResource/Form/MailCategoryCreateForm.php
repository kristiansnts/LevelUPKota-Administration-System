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
                ->label('Nama')     
                ->placeholder('Masukkan nama kategori surat, contoh: SK, SP, dll.')
                ->required()
                ->maxLength(255),
            TextInput::make('description')
                ->label('Keterangan')
                ->placeholder('Masukkan keterangan kategori surat, contoh: Surat Keputusan, Surat Pemberitahuan, dll.')
                ->required()
                ->maxLength(255),
        ];
    }
}
