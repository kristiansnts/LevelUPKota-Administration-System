<?php

namespace App\Filament\Resources\Finance\PaymentMethodResource\Pages;

use App\Filament\Resources\Finance\PaymentMethodResource;
use App\Filament\Shared\Services\ModelQueryService;
use App\Models\PaymentMethodUser;
use Filament\Resources\Pages\CreateRecord;

class CreatePaymentMethod extends CreateRecord
{
    protected static string $resource = PaymentMethodResource::class;

    protected function afterCreate(): void
    {
        if (! $this->record instanceof \App\Models\PaymentMethod) {
            return;
        }

        $user = ModelQueryService::getUserModel();

        PaymentMethodUser::create([
            'payment_method_id' => $this->record->id,
            'city_id' => $user->city_id,
            'district_id' => $user->district_id ?? null,
        ]);
    }
}
