<?php

namespace App\Filament\Resources\Finance\PaymentMethodResource\Pages;

use App\Filament\Resources\Finance\PaymentMethodResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPaymentMethods extends ListRecords
{
    protected static string $resource = PaymentMethodResource::class;

    protected function getHeaderActions(): array
    {
        /**
         * @var string $createActionLabel
         */
        $createActionLabel = config('constants.resources.paymentMethod.create');

        return [
            Actions\CreateAction::make()
                ->label($createActionLabel),
        ];
    }
}
