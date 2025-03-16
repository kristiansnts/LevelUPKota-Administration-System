<?php

namespace App\Filament\Resources\Mail;

use App\Filament\Resources\Mail\CityCodeResource\Pages;
use App\Models\CityCode;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CityCodeResource extends Resource
{
    protected static ?string $model = CityCode::class;

    protected static ?string $modelLabel = 'Kode Kota';

    protected static ?string $navigationGroup = 'Surat';

    protected static ?string $navigationLabel = 'Kode Kota';

    protected static ?string $navigationIcon = 'heroicon-o-map-pin';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('City Code')
                    ->schema([
                        Forms\Components\Select::make('province_id')
                            ->relationship('province', 'prov_name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\Select::make('city_id')
                            ->relationship('city', 'city_name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\Select::make('district_id')
                            ->relationship('district', 'dis_name')
                            ->searchable()
                            ->preload(),
                        Forms\Components\TextInput::make('code')
                            ->required(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('province.prov_name'),
                Tables\Columns\TextColumn::make('city.city_name'),
                Tables\Columns\TextColumn::make('district.dis_name'),
                Tables\Columns\TextColumn::make('code'),
            ])
            ->filters([

            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [

        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCityCodes::route('/'),
            'create' => Pages\CreateCityCode::route('/create'),
            'edit' => Pages\EditCityCode::route('/{record}/edit'),
        ];
    }
}
