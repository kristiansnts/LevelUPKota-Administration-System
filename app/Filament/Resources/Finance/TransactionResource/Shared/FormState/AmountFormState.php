<?php

namespace App\Filament\Resources\Finance\TransactionResource\Shared\FormState;

use App\Enums\FinanceTypeEnum;
use App\Models\Transaction;

class AmountFormState
{
    /**
     * Get the amount in for a given transaction record
     */
    public static function getAmountIn(?Transaction $record): int
    {
        return self::getAmountByType($record, FinanceTypeEnum::INCOME);
    }

    /**
     * Get the amount out for a given transaction record
     */
    public static function getAmountOut(?Transaction $record): int
    {
        return self::getAmountByType($record, FinanceTypeEnum::EXPENSE);
    }

    /**
     * Get the amount for a given transaction record based on type
     */
    private static function getAmountByType(?Transaction $record, FinanceTypeEnum $type): int
    {
        if (! self::isValidTransactionRecord($record)) {
            return 0;
        }

        /** @var Transaction $record */
        return $record->transactionCategory?->transaction_type === $type->value ? (int) $record->amount : 0;
    }

    /**
     * Check if the finance record is valid for transaction calculations
     */
    private static function isValidTransactionRecord(?Transaction $record): bool
    {
        return $record instanceof Transaction
            && $record->transactionCategory !== null
            && $record->transactionCategory->transaction_type !== null
            && $record->amount !== null;
    }
}
