<?php

namespace App\Filament\Resources\Finance;

use App\Filament\Resources\Finance\TransactionResource\Form\TransactionForm;
use App\Filament\Resources\Finance\TransactionResource\Pages;
use App\Filament\Resources\Finance\TransactionResource\Table\TransactionTable;
use App\Models\Transaction;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $modelLabel = 'Transaksi';

    protected static ?string $navigationGroup = 'Keuangan';

    protected static ?string $navigationLabel = 'Transaksi';

    protected static ?string $slug = 'transaksi';

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    public static function form(Form $form): Form
    {
        return TransactionForm::form($form);
    }

    public static function table(Table $table): Table
    {
        return TransactionTable::table($table)
            ->actions([
                Tables\Actions\EditAction::make()
                    ->icon('heroicon-o-pencil')
                    ->color('warning'),
                Tables\Actions\ViewAction::make('bukti_transaksi')
                    ->label('Bukti Transaksi')
                    ->icon('heroicon-o-eye')
                    ->color('info'),
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
            'index' => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }
}
