<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin.levelup@gmail.com',
            'password' => Hash::make('password'),
        ]);

        $role = Role::create([
            'name' => config('constants.roles.super_admin'),
        ]);

        $user->assignRole($role);

        Role::create([
            'name' => config('constants.roles.guest'),
        ]);

    }
}
