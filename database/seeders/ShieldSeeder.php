<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use BezhanSalleh\FilamentShield\Support\Utils;
use Spatie\Permission\PermissionRegistrar;

class ShieldSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $rolesWithPermissions = '[{"name":"super_admin","guard_name":"web","permissions":["view_finance::payment::method","view_any_finance::payment::method","create_finance::payment::method","update_finance::payment::method","restore_finance::payment::method","restore_any_finance::payment::method","replicate_finance::payment::method","reorder_finance::payment::method","delete_finance::payment::method","delete_any_finance::payment::method","force_delete_finance::payment::method","force_delete_any_finance::payment::method","view_finance::report","view_any_finance::report","create_finance::report","update_finance::report","restore_finance::report","restore_any_finance::report","replicate_finance::report","reorder_finance::report","delete_finance::report","delete_any_finance::report","force_delete_finance::report","force_delete_any_finance::report","view_finance::transaction","view_any_finance::transaction","create_finance::transaction","update_finance::transaction","restore_finance::transaction","restore_any_finance::transaction","replicate_finance::transaction","reorder_finance::transaction","delete_finance::transaction","delete_any_finance::transaction","force_delete_finance::transaction","force_delete_any_finance::transaction","view_finance::transaction::category","view_any_finance::transaction::category","create_finance::transaction::category","update_finance::transaction::category","restore_finance::transaction::category","restore_any_finance::transaction::category","replicate_finance::transaction::category","reorder_finance::transaction::category","delete_finance::transaction::category","delete_any_finance::transaction::category","force_delete_finance::transaction::category","force_delete_any_finance::transaction::category","view_mail::city::code","view_any_mail::city::code","create_mail::city::code","update_mail::city::code","restore_mail::city::code","restore_any_mail::city::code","replicate_mail::city::code","reorder_mail::city::code","delete_mail::city::code","delete_any_mail::city::code","force_delete_mail::city::code","force_delete_any_mail::city::code","view_mail::mails","view_any_mail::mails","create_mail::mails","update_mail::mails","restore_mail::mails","restore_any_mail::mails","replicate_mail::mails","reorder_mail::mails","delete_mail::mails","delete_any_mail::mails","force_delete_mail::mails","force_delete_any_mail::mails","view_mail::mails::category","view_any_mail::mails::category","create_mail::mails::category","update_mail::mails::category","restore_mail::mails::category","restore_any_mail::mails::category","replicate_mail::mails::category","reorder_mail::mails::category","delete_mail::mails::category","delete_any_mail::mails::category","force_delete_mail::mails::category","force_delete_any_mail::mails::category","view_mail::mails::in","view_any_mail::mails::in","create_mail::mails::in","update_mail::mails::in","restore_mail::mails::in","restore_any_mail::mails::in","replicate_mail::mails::in","reorder_mail::mails::in","delete_mail::mails::in","delete_any_mail::mails::in","force_delete_mail::mails::in","force_delete_any_mail::mails::in","view_mail::mails::out","view_any_mail::mails::out","create_mail::mails::out","update_mail::mails::out","restore_mail::mails::out","restore_any_mail::mails::out","replicate_mail::mails::out","reorder_mail::mails::out","delete_mail::mails::out","delete_any_mail::mails::out","force_delete_mail::mails::out","force_delete_any_mail::mails::out","view_role","view_any_role","create_role","update_role","delete_role","delete_any_role","view_user::user","view_any_user::user","create_user::user","update_user::user","restore_user::user","restore_any_user::user","replicate_user::user","reorder_user::user","delete_user::user","delete_any_user::user","force_delete_user::user","force_delete_any_user::user","page_EditProfilePage"]}]';
        $directPermissions = '[]';

        static::makeRolesWithPermissions($rolesWithPermissions);
        static::makeDirectPermissions($directPermissions);

        $this->command->info('Shield Seeding Completed.');
    }

    protected static function makeRolesWithPermissions(string $rolesWithPermissions): void
    {
        if (! blank($rolePlusPermissions = json_decode($rolesWithPermissions, true))) {
            /** @var Model $roleModel */
            $roleModel = Utils::getRoleModel();
            /** @var Model $permissionModel */
            $permissionModel = Utils::getPermissionModel();

            foreach ($rolePlusPermissions as $rolePlusPermission) {
                $role = $roleModel::firstOrCreate([
                    'name' => $rolePlusPermission['name'],
                    'guard_name' => $rolePlusPermission['guard_name'],
                ]);

                if (! blank($rolePlusPermission['permissions'])) {
                    $permissionModels = collect($rolePlusPermission['permissions'])
                        ->map(fn ($permission) => $permissionModel::firstOrCreate([
                            'name' => $permission,
                            'guard_name' => $rolePlusPermission['guard_name'],
                        ]))
                        ->all();

                    $role->syncPermissions($permissionModels);
                }
            }
        }
    }

    public static function makeDirectPermissions(string $directPermissions): void
    {
        if (! blank($permissions = json_decode($directPermissions, true))) {
            /** @var Model $permissionModel */
            $permissionModel = Utils::getPermissionModel();

            foreach ($permissions as $permission) {
                if ($permissionModel::whereName($permission)->doesntExist()) {
                    $permissionModel::create([
                        'name' => $permission['name'],
                        'guard_name' => $permission['guard_name'],
                    ]);
                }
            }
        }
    }
}
