<?php

namespace App\Filament\Resources\Finance\TransactionResource\Shared\UseCases;

use App\Filament\Resources\Finance\TransactionResource\Shared\Services\CreatePivotService;
use App\Helpers\NotificationHelper;
use Exception;

class CreateOptionUseCase
{
    public function __construct(
        private readonly CreatePivotService $createPivotService,
    ) {}

    /**
     * Create a new transaction category
     *
     * @param  array<string, mixed>  $data
     */
    public function createTransactionCategory(array $data): int
    {
        try {
            return $this->createPivotService->createTransactionCategoryPivot($data);
        } catch (Exception) {
            NotificationHelper::error('Gagal membuat kategori transaksi');

            return 0;
        }
    }

    /**
     * Create a new payment method
     *
     * @param  array<string, mixed>  $data
     */
    public function createPaymentMethod(array $data): int
    {
        try {
            return $this->createPivotService->createPaymentMethodPivot($data);
        } catch (Exception) {
            NotificationHelper::error('Gagal membuat metode pembayaran');

            return 0;
        }
    }
}
