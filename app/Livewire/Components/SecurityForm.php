<?php

namespace App\Livewire\Components;

use App\Helpers\RolesHelper;
use Filament\Forms;
use Filament\Forms\Components\Component;

class SecurityForm
{
    public static function make(): Component
    {
        return Forms\Components\Section::make('Keamanan')
            ->aside()
            ->description('Silahkan ubah kata sandi Anda')
            ->schema([
                Forms\Components\TextInput::make('current_password')
                    ->label('Kata Sandi Saat Ini')
                    ->password()
                    ->required()
                    ->visible(fn (): bool => ! RolesHelper::isGuest()),
                Forms\Components\TextInput::make('password')
                    ->label('Kata Sandi')
                    ->password(),
                Forms\Components\TextInput::make('password_confirmation')
                    ->label('Konfirmasi Kata Sandi')
                    ->password(),
            ]);
    }
}
