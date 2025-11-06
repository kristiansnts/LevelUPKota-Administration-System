<?php

namespace App\Filament\Resources\Finance\TransactionResource\Shared\Services;

use App\Models\Transaction;
use App\Enums\FinanceTypeEnum;

class TransactionService
{
    public function createTransaction(Transaction $transaction, int $transactionId): void
    {
        $query = Transaction::whereHas('transactionUsers', function ($query) use ($transaction) {
            $query->where('city_id', $transaction->transactionUsers->first()->city_id)
                  ->where('district_id', $transaction->transactionUsers->first()->district_id);
        });
        
        // If transaction has report_id, only consider transactions with the same report_id
        // If transaction has NO report_id, only consider transactions with NO report_id
        if ($transaction->report_id) {
            $query->where('report_id', $transaction->report_id);
        } else {
            $query->whereNull('report_id');
        }
        
        $lastTransaction = $query->where('id', '<', $transactionId)
            ->latest('id')
            ->first();

        $previousBalance = $lastTransaction ? $lastTransaction->balance : 0;

        $transaction->balance = $this->calculateNewBalance($transaction, $previousBalance);
        $transaction->save();
    }

    public function updateTransaction(Transaction $transaction, int $transactionId): void
    {
        $query = Transaction::whereHas('transactionUsers', function ($query) use ($transaction) {
            $query->where('city_id', $transaction->transactionUsers->first()->city_id)
                  ->where('district_id', $transaction->transactionUsers->first()->district_id);
        });
        
        // If transaction has report_id, only consider transactions with the same report_id
        // If transaction has NO report_id, only consider transactions with NO report_id
        if ($transaction->report_id) {
            $query->where('report_id', $transaction->report_id);
        } else {
            $query->whereNull('report_id');
        }
        
        $affectedTransactions = clone $query;
        $affectedTransactions = $affectedTransactions->where('id', '>=', $transactionId)
            ->orderBy('id')
            ->get();

        $previousTransaction = $query->where('id', '<', $transactionId)
            ->latest('id')
            ->first();

        $runningBalance = $previousTransaction ? $previousTransaction->balance : 0;

        foreach ($affectedTransactions as $affectedTransaction) {
            if ($affectedTransaction->transactionCategory && $affectedTransaction->transactionCategory->transaction_type === FinanceTypeEnum::INCOME->value) {
                $runningBalance += $affectedTransaction->amount;
            } else {
                $runningBalance -= $affectedTransaction->amount;
            }

            if ($affectedTransaction->balance !== $runningBalance) {
                $affectedTransaction->balance = $runningBalance;
                $affectedTransaction->saveQuietly();
            }
        }
    }

    public function deleteTransaction(Transaction $transaction): void
    {
        $transactionId = $transaction->getKey();
        
        $query = Transaction::whereHas('transactionUsers', function ($query) use ($transaction) {
            $query->where('city_id', $transaction->transactionUsers->first()->city_id)
                  ->where('district_id', $transaction->transactionUsers->first()->district_id);
        });
        
        // If transaction has report_id, only consider transactions with the same report_id
        // If transaction has NO report_id, only consider transactions with NO report_id
        if ($transaction->report_id) {
            $query->where('report_id', $transaction->report_id);
        } else {
            $query->whereNull('report_id');
        }
        
        $affectedTransactions = clone $query;
        $affectedTransactions = $affectedTransactions->where('id', '>', $transactionId)
            ->orderBy('id')
            ->get();

        $previousTransaction = $query->where('id', '<', $transactionId)
            ->latest('id')
            ->first();

        $runningBalance = $previousTransaction ? $previousTransaction->balance : 0;

        foreach ($affectedTransactions as $affectedTransaction) {
            if ($affectedTransaction->transactionCategory && $affectedTransaction->transactionCategory->transaction_type === FinanceTypeEnum::INCOME->value) {
                $runningBalance += $affectedTransaction->amount;
            } else {
                $runningBalance -= $affectedTransaction->amount;
            }

            if ($affectedTransaction->balance !== $runningBalance) {
                $affectedTransaction->balance = $runningBalance;
                $affectedTransaction->saveQuietly();
            }
        }
    }

    public function recalculateAllBalances(?int $reportId = null, ?int $cityId = null, ?int $districtId = null): void
    {
        $query = Transaction::query()->with(['transactionCategory', 'transactionUsers']);
        
        if ($reportId !== null) {
            $query->where('report_id', $reportId);
        } elseif ($reportId === null && $cityId === null && $districtId === null) {
            // If no filters provided, recalculate all transactions
            $query->whereNotNull('id');
        }
        
        if ($cityId !== null || $districtId !== null) {
            $query->whereHas('transactionUsers', function ($q) use ($cityId, $districtId) {
                if ($cityId !== null) {
                    $q->where('city_id', $cityId);
                }
                if ($districtId !== null) {
                    $q->where('district_id', $districtId);
                }
            });
        }
        
        $transactions = $query->orderBy('id')->get();
        
        $runningBalance = 0;
        
        foreach ($transactions as $transaction) {
            if ($transaction->transactionCategory && $transaction->transactionCategory->transaction_type === FinanceTypeEnum::INCOME->value) {
                $runningBalance += $transaction->amount;
            } else {
                $runningBalance -= $transaction->amount;
            }
            
            if ($transaction->balance !== $runningBalance) {
                $transaction->balance = $runningBalance;
                $transaction->saveQuietly();
            }
        }
    }

    /**
     * Calculate the new balance based on transaction type and amount
     */
    protected function calculateNewBalance(Transaction $transaction, float $previousBalance): float
    {
        if ($transaction->transactionCategory &&
            $transaction->transactionCategory->transaction_type === FinanceTypeEnum::INCOME->value) {
            return $previousBalance + $transaction->amount;
        }

        return $previousBalance - $transaction->amount;
    }
}