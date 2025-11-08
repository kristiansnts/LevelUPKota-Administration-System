<?php

namespace App\Filament\Resources\Finance\TransactionResource\Pages;

use App\Filament\Resources\Finance\TransactionResource;
use App\Filament\Resources\Finance\TransactionResource\Shared\Services\TransactionService;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTransactions extends ListRecords
{
    protected static string $resource = TransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\Action::make('recalculate_all_balances')
            //     ->label('Hitung Ulang Semua Saldo')
            //     ->icon('heroicon-o-calculator')
            //     ->color('info')
            //     ->requiresConfirmation()
            //     ->modalHeading('Hitung Ulang Semua Saldo')
            //     ->modalDescription('Apakah Anda yakin ingin menghitung ulang semua saldo transaksi? Ini akan memproses semua transaksi di sistem.')
            //     ->modalSubmitActionLabel('Ya, Hitung Ulang')
            //     ->action(function (): void {
            //         (new TransactionService())->recalculateAllBalances();
            //         \Filament\Notifications\Notification::make()
            //             ->title('Semua saldo berhasil dihitung ulang')
            //             ->success()
            //             ->send();
            //     }),
        ];
    }
}
