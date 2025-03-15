<?php

namespace App\Filament\Resources\Finance\TransactionResource\Shared\UseCases;

use App\Filament\Resources\Finance\TransactionResource\Shared\Services\CreatePivotService;
use App\Helpers\NotificationHelper;

class CreateOptionUseCase
{
    public function __construct(
        private readonly CreatePivotService $createPivotService,
    ) {}

    /**
     * Create a new instance of the use case
     */
    public static function make(): self
    {
        return new self(CreatePivotService::make());
    }

    /**
     * Create a new transaction category
     *
     * @param  array<string, mixed>  $data
     */
    public function createTransactionCategory(array $data): int
    {
        try {
            return $this->createPivotService->createTransactionCategoryPivot($data);
        } catch (\Exception $e) {
            NotificationHelper::error('Failed to create transaction category');
            throw new \Exception('Failed to create transaction category', 0, $e);
        }
    }

    /**
     * Create a new transaction type
     *
     * @param  array<string, mixed>  $data
     */
    public function createTransactionType(array $data): int
    {
        try {
            return $this->createPivotService->createTransactionTypePivot($data);
        } catch (\Exception $e) {
            NotificationHelper::error('Failed to create transaction type');
            throw new \Exception('Failed to create transaction type', 0, $e);
        }
    }
}
