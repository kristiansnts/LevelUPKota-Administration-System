<?php

namespace App\Filament\Shared\Services;

use App\Models\City;
use App\Models\District;
use App\Models\MailCategory;
use App\Models\MailCategoryUser;
use App\Models\Province;
use App\Models\User;
use App\Models\TransactionCategory;
use App\Models\TransactionCategoryUser;
use App\Models\PaymentMethod;
use App\Models\PaymentMethodUser;

class ModelQueryService
{
    public static function getUserModel(): User
    {
        /**
         * @var \App\Models\User
         */
        return \Illuminate\Support\Facades\Auth::user();
    }

    /**
     * @return array<mixed>
     */
    public static function getProvinceOptions(): array
    {
        return Province::query()
            ->pluck('prov_name', 'prov_id')
            ->filter()
            ->toArray();
    }

    /**
     * @return array<mixed>
     */
    public static function getCityOptions(): array
    {
        return City::query()
            ->pluck('city_name', 'city_id')
            ->filter()
            ->toArray();
    }

    /**
     * @return array<mixed>
     */
    public static function getDistrictOptions(): array
    {
        return District::query()
            ->pluck('dis_name', 'dis_id')
            ->filter()
            ->toArray();
    }

    /**
     * @return array<mixed>
     */
    public static function getMailCategoryOptions(): array
    {
        /**
         * @var array<int, string>
         */
        $mailCategoryIds = ResourceScopeService::userScope(
            MailCategoryUser::query(),
            'mail_category_id'
        );

        return MailCategory::query()
            ->whereIn('id', $mailCategoryIds)
            ->pluck('description', 'id')
            ->toArray();
    }

    public static function getTransactionCategoryOptions(): array
    {
        /**
         * @var array<int, string>
         */
        $transactionCategoryIds = ResourceScopeService::userScope(
            TransactionCategoryUser::query(),
            'transaction_category_id'
        );

        return TransactionCategory::query()
            ->whereIn('id', $transactionCategoryIds)
            ->get()
            ->mapWithKeys(function ($category) {
                return [$category->id => $category->full_display];
            })
            ->toArray();
    }

    public static function getTransactionCategoryOptionsWithHtml(): array
    {
        /**
         * @var array<int, string>
         */
        $transactionCategoryIds = ResourceScopeService::userScope(
            TransactionCategoryUser::query(),
            'transaction_category_id'
        );

        return TransactionCategory::query()
            ->whereIn('id', $transactionCategoryIds)
            ->get()
            ->mapWithKeys(function ($category) {
                $html = '<strong>' . e($category->name_with_type) . '</strong>';
                if ($category->description) {
                    $html .= '<br><small>' . e($category->description) . '</small>';
                }
                return [$category->id => $html];
            })
            ->toArray();
    }

    public static function getPaymentMethodOptions(): array
    {
        /**
         * @var array<int, string>
         */
        $paymentMethodIds = ResourceScopeService::userScope(
            PaymentMethodUser::query(),
            'payment_method_id'
        );

        return PaymentMethod::query()
            ->whereIn('id', $paymentMethodIds)
            ->pluck('name', 'id')
            ->toArray();
    }
}
