<?php

namespace App\Livewire\Components;

use App\Filament\Shared\Services\ModelQueryService;
use Filament\Forms;
use Filament\Forms\Components\Component;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;

class PersonalInfoForm
{
    public static function make(): Component
    {
        /**
         * @var \App\Models\User
         */
        $user = ModelQueryService::getUserModel();

        return Forms\Components\Section::make('Informasi Pribadi')
            ->aside()
            ->description('Silahkan isi informasi pribadi Anda')
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nama')
                    ->placeholder('Masukkan Nama Anda')
                    ->default($user->name)
                    ->required()
                    ->validationMessages([
                        'required' => 'Nama tidak boleh kosong',
                    ]),
                Forms\Components\TextInput::make('email')
                    ->label('Email')
                    ->placeholder('Masukkan Email Anda')
                    ->default($user->email)
                    ->required()
                    ->email()
                    ->validationMessages([
                        'required' => 'Email tidak boleh kosong',
                        'email' => 'Format email tidak valid',
                    ]),
                PhoneInput::make('phone_number')
                    ->label('Nomor Telepon')
                    ->placeholder('Masukkan Nomor Telepon Anda')
                    ->default($user->phone_number)
                    ->required()
                    ->validationMessages([
                        'required' => 'Nomor telepon tidak boleh kosong',
                    ]),
            ]);
    }
}
