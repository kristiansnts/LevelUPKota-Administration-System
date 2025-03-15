<?php

namespace App\Filament\Resources\Finance;

use App\Filament\Resources\Finance\TransactionResource\Form\TransactionForm;
use App\Filament\Resources\Finance\TransactionResource\Pages;
use App\Filament\Resources\Finance\TransactionResource\Shared\FormState\AmountFormState;
use App\Models\Finance;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TransactionResource extends Resource
{
    protected static ?string $model = Finance::class;

    protected static ?string $modelLabel = 'Transaksi';

    protected static ?string $navigationGroup = 'Keuangan';

    protected static ?string $navigationLabel = 'Transaksi';

    protected static ?string $slug = 'transaksi';

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    public static function form(Form $form): Form
    {
        return TransactionForm::form($form);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('transactionPeriod.name')
                    ->label('Periode'),
                Tables\Columns\TextColumn::make('transaction_date')
                    ->label('Tanggal Transaksi'),
                Tables\Columns\TextColumn::make('transactionCategory.name')
                    ->label('Kategori Transaksi'),
                Tables\Columns\TextColumn::make('description')
                    ->label('Keterangan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('paymentMethod.name')
                    ->label('Metode Pembayaran')
                    ->searchable(),
                \Pelmered\FilamentMoneyField\Tables\Columns\MoneyColumn::make('amount_in')
                    ->label('Dana Masuk')
                    ->getStateUsing(function ($record): int {
                        /** @var Finance $record */
                        return AmountFormState::getAmountIn($record);
                    }),
                \Pelmered\FilamentMoneyField\Tables\Columns\MoneyColumn::make('amount_out')
                    ->label('Dana Keluar')
                    ->getStateUsing(function ($record): int {
                        /** @var Finance $record */
                        return AmountFormState::getAmountOut($record);
                    }),
                Tables\Columns\TextColumn::make('invoice_code')
                    ->label('Nomor Kwitansi')
                    ->searchable(),
                \Pelmered\FilamentMoneyField\Tables\Columns\MoneyColumn::make('balance')
                    ->label('Saldo'),
            ])
            ->defaultSort('transaction_date', 'desc')
            ->filters([

            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->icon('heroicon-o-pencil')
                    ->color('warning'),
                Tables\Actions\ViewAction::make('bukti_transaksi')
                    ->label('Bukti Transaksi')
                    ->icon('heroicon-o-eye')
                    ->color('info'),
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
            'index' => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }
}
