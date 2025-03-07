<?php

namespace App\Livewire\Components;

use App\Filament\Shared\Services\ModelQueryService;
use App\Helpers\RolesHelper;
use Filament\Forms;
use Filament\Forms\Components\Component;
use Spatie\Permission\Models\Role;

class RoleForm
{
    /**
     * @return \Filament\Forms\Components\Section
     */
    public static function make(): Component
    {
        /**
         * @var \App\Models\User
         */
        $user = ModelQueryService::getUserModel();

        return Forms\Components\Section::make('Jabatan')
            ->aside()
            ->description('Silahkan pilih jabatan Anda di LevelUp Kota')
            ->schema([
                Forms\Components\Select::make('jabatan')
                    ->label('Jabatan')
                    ->searchable()
                    ->options(fn (): array => Role::query()
                        ->whereNotIn('name', ['super_admin', 'admin'])
                        ->pluck('name', 'id')
                        ->toArray())
                    ->default(function () use ($user) {
                        /** @var ?Role $role */
                        $role = $user->roles->first();

                        return $role?->name;
                    })
                    ->required(),
            ])->visible(RolesHelper::isGuest());
    }
}
