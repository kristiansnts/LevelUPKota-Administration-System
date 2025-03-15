<?php

namespace App\Filament\Resources\Finance\TransactionResource\Shared\Services;

use App\Filament\Shared\Services\ModelQueryService;
use App\Models\PaymentMethod;
use App\Models\PaymentMethodUser;
use App\Models\TransactionCategory;
use App\Models\TransactionCategoryUser;
use App\Models\User;

class CreatePivotService
{
    private readonly User $user;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->user = ModelQueryService::getUserModel();
    }

    /**
     * Create a new instance of the service
     */
    public static function make(): self
    {
        return new self;
    }

    /**
     * Create a transaction category pivot
     *
     * @param  array<string, mixed>  $data
     */
    public function createTransactionCategoryPivot(array $data): int
    {
        /**
         * @var TransactionCategory $transactionCategory
         */
        $transactionCategory = TransactionCategory::create($data);

        TransactionCategoryUser::create([
            'transaction_category_id' => $transactionCategory->id,
            'city_id' => $this->user->city_id,
            'district_id' => $this->user->district_id ?? null,
        ]);

        return $transactionCategory->id;
    }

    /**
     * Create a transaction type pivot
     *
     * @param  array<string, mixed>  $data
     */
    public function createPaymentMethodPivot(array $data): int
    {
        /**
         * @var PaymentMethod $paymentMethod
         */
        $paymentMethod = PaymentMethod::create($data);

        PaymentMethodUser::create([
            'payment_method_id' => $paymentMethod->id,
            'city_id' => $this->user->city_id,
            'district_id' => $this->user->district_id ?? null,
        ]);

        return $paymentMethod->id;
    }
}
