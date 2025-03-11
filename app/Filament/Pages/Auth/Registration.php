<?php

namespace App\Filament\Pages\Auth;

use Filament\Forms\Components\Component;
use Filament\Forms\Form;
use Filament\Pages\Auth\Register as BaseRegister;
use Illuminate\Database\Eloquent\Model;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;

class Registration extends BaseRegister
{
    public function form(Form $form): Form
    {
        return $form->schema([
            $this->getNameFormComponent(),
            $this->getEmailFormComponent(),
            $this->getPhoneNumberFormComponent(),
            $this->getPasswordFormComponent(),
            $this->getPasswordConfirmationFormComponent(),
        ]);
    }

    public function getPhoneNumberFormComponent(): Component
    {
        return PhoneInput::make('phone_number')
            ->label('Nomor Telepon')
            ->defaultCountry('ID')
            ->required();
    }

    public function handleRegistration(array $data): Model
    {
        $user = $this->getUserModel()::create($data);
        $user->assignRole('guest');

        return $user;
    }
}
