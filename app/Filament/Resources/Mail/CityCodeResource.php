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

    protected static ?string $modelLabel = 'Kode Surat';

    protected static ?string $navigationGroup = 'Surat';

    protected static ?string $navigationLabel = 'Kode Surat';

    protected static ?string $navigationIcon = 'heroicon-o-map-pin';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Kode Surat')
                    ->schema([
                        Forms\Components\Select::make('province_id')
                            ->relationship('province', 'provinsi_name')
                            ->label('Provinsi')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\Select::make('city_id')
                            ->relationship('city', 'kabupaten_name')
                            ->label('Kota')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\Select::make('district_id')
                            ->relationship('district', 'kecamatan_name')
                            ->label('Kecamatan')
                            ->searchable()
                            ->preload(),
                        Forms\Components\TextInput::make('code')
                            ->label('Kode Surat')
                            ->required(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('province.provinsi_name'),
                Tables\Columns\TextColumn::make('city.kabupaten_name'),
                Tables\Columns\TextColumn::make('district.kecamatan_name'),
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
