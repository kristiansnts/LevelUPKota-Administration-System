<?php

namespace App\Livewire\Components;

use App\Filament\Shared\Services\ModelQueryService;
use Filament\Forms;
use Filament\Forms\Components\Component;

class AddressForm
{
    public static function make(): Component
    {
        /**
         * @var \App\Models\User
         */
        $user = ModelQueryService::getUserModel();

        return Forms\Components\Section::make('Alamat')
            ->aside()
            ->description('Silahkan isi LevelUp Kota Anda')
            ->schema([
                Forms\Components\Select::make('province_id')
                    ->label('Provinsi')
                    ->options(fn (): array => ModelQueryService::getProvinceOptions())
                    ->default($user->province_id)
                    ->searchable()
                    ->required()
                    ->validationMessages([
                        'required' => 'Provinsi tidak boleh kosong',
                    ]),
                Forms\Components\Select::make('city_id')
                    ->label('Kota')
                    ->options(fn (): array => ModelQueryService::getCityOptions())
                    ->default($user->city_id)
                    ->searchable()
                    ->required()
                    ->validationMessages([
                        'required' => 'Kota tidak boleh kosong',
                    ]),
                Forms\Components\Select::make('district_id')
                    ->label('Kecamatan')
                    ->options(fn (): array => ModelQueryService::getDistrictOptions())
                    ->default($user->district_id)
                    ->searchable()
                    ->helperText('Hanya diisi jika LevelUp Kota mencangkup Kecamatan'),
            ]);
    }
}
