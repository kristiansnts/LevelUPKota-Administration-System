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
            'phone_number' => '083125180658',
            'province_id' => 15,
            'city_id' => 241,
        ]);

        $role = Role::create([
            'name' => config('constants.roles.super_admin'),
        ]);

        $user->assignRole($role);

        Role::create([
            'name' => config('constants.roles.guest'),
        ]);

        Role::create([
            'name' => 'demo',
        ]);

        $demoUser = User::factory()->create([
            'name' => 'Akun Demo',
            'email' => 'akundemo@gmail.com',
            'password' => Hash::make('demo'),
            'phone_number' => '1234567890',
            'province_id' => 100,
            'city_id' => 1000,
        ]);

        $demoUser->assignRole('demo');
    }
}
