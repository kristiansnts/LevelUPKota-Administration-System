<?php

namespace App\Filament\Resources\Finance\TransactionResource\Table;

use App\Filament\Resources\Finance\TransactionResource\Shared\FormState\AmountFormState;
use App\Models\Transaction;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;


class TransactionTable extends Tables\Table
{
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('transaction_date')
                    ->label('Tanggal Transaksi')
                    ->dateTime('l, d F Y'),
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
                        /** @var Transaction $record */
                        return AmountFormState::getAmountIn($record);
                    }),
                \Pelmered\FilamentMoneyField\Tables\Columns\MoneyColumn::make('amount_out')
                    ->label('Dana Keluar')
                    ->getStateUsing(function ($record): int {
                        /** @var Transaction $record */
                        return AmountFormState::getAmountOut($record);
                    }),
                Tables\Columns\TextColumn::make('invoice_code')
                    ->label('Nomor Kwitansi')
                    ->searchable(),
                Tables\Columns\TextColumn::make('report.title')
                    ->label('Laporan')
                    ->searchable(),
                \Pelmered\FilamentMoneyField\Tables\Columns\MoneyColumn::make('balance')
                    ->label('Saldo'),
            ])
            ->defaultSort('transaction_date', 'desc')
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->using(function (Collection $records) {
                            $records->each(function (Transaction $record) {
                                // Delete related TransactionUser records first
                                $record->transactionUsers()->delete();
                                $record->delete();
                            });
                        }),
                ]),
            ]);
    }
}
