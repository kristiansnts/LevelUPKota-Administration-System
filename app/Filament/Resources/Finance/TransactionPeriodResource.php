<?php

namespace App\Filament\Resources\Finance;

use App\Filament\Resources\Finance\TransactionPeriodResource\Pages;
use App\Models\TransactionPeriod;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TransactionPeriodResource extends Resource
{
    protected static ?string $model = TransactionPeriod::class;

    protected static ?string $navigationGroup = 'Keuangan';

    protected static ?string $navigationLabel = 'Periode Transaksi';

    protected static ?string $slug = 'periode-transaksi';

    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Periode Transaksi')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->placeholder('Masukkan nama periode transaksi co: Periode 23-24')
                            ->maxLength(255),
                        Forms\Components\DatePicker::make('start_date')
                            ->label('Tanggal Mulai')
                            ->native(false)
                            ->required(),
                        Forms\Components\DatePicker::make('end_date')
                            ->label('Tanggal Akhir')
                            ->native(false)
                            ->required(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('start_date'),
                Tables\Columns\TextColumn::make('end_date'),
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
            'index' => Pages\ListTransactionPeriods::route('/'),
            'create' => Pages\CreateTransactionPeriod::route('/create'),
            'edit' => Pages\EditTransactionPeriod::route('/{record}/edit'),
        ];
    }
}
