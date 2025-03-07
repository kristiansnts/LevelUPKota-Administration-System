<?php

namespace App\Filament\Shared\UseCases;

use App\Filament\Shared\Services\ModelQueryService;
use Illuminate\Support\Facades\Hash;

class UserPasswordUseCase
{
    /**
     * @param  array<string, mixed>  $data
     */
    public static function checkCurrentPassword(array $data): bool
    {
        /**
         * @var \App\Models\User
         */
        $user = ModelQueryService::getUserModel();
        /**
         * @var string
         */
        $currentPassword = $data['current_password'] ?? '';
        /**
         * @var string
         */
        $userPassword = $user->password ?? '';

        return Hash::check($currentPassword, $userPassword);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public static function checkPasswordConfirmation(array $data): bool
    {
        /**
         * @var string
         */
        $password = $data['password'] ?? '';
        /**
         * @var string
         */
        $passwordConfirmation = $data['password_confirmation'] ?? '';

        return $password === $passwordConfirmation;
    }
}
