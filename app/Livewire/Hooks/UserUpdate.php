<?php

namespace App\Livewire\Hooks;

use App\Filament\Shared\Services\ModelQueryService;
use Spatie\Permission\Models\Role;

class UserUpdate
{
    /**
     * @param  array<string, mixed>  $data
     */
    public static function updateFields(array $data): void
    {
        /**
         * @var \App\Models\User
         */
        $user = ModelQueryService::getUserModel();

        /**
         * @var array<string, mixed>
         */
        $updateData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'phone_number' => $data['phone_number'],
            'province_id' => $data['province_id'],
            'city_id' => $data['city_id'],
            'district_id' => $data['district_id'],
        ];

        if (isset($data['password'])) {
            /**
             * @var string
             */
            $password = $data['password'];

            $updateData['password'] = bcrypt($password);
        }

        $user->update($updateData);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public static function updateRole(array $data): void
    {
        /**
         * @var \Spatie\Permission\Models\Role
         */
        $role = Role::find($data['jabatan']);

        /**
         * @var \App\Models\User
         */
        $user = ModelQueryService::getUserModel();

        $user->syncRoles([$role->id]);
    }
}
