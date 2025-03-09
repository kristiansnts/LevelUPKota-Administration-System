<?php

declare(strict_types=1);

namespace App\Filament\Resources\Mail\MailsOutResource\Actions;

use App\Filament\Resources\Mail\MailsResource\Shared\UseCases\GeneratedMailUseCase;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Support\Enums\ActionSize;
use Livewire\Component as Livewire;

/**
 * @method void setUp()
 */
class MailCodeCreateAction extends Action
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->label('Buat Nomor Surat')
            ->button()
            ->disabled(fn (Get $get): bool => !$this->checkFormFilled($get))
            ->size(ActionSize::ExtraSmall)
            ->icon('heroicon-m-magnifying-glass')
            ->action(function (Get $get, Set $set, Livewire $livewire): void {
                $formData = [
                    'mail_date' => $get('mail_date'),
                    'mail_category_id' => $get('mail_category_id'),
                    'sender_name' => $get('sender_name'),
                    'receiver_name' => $get('receiver_name'),
                    'description' => $get('description'),
                ];

                $generateMailCodeUseCase = app(GeneratedMailUseCase::class);
                dump($generateMailCodeUseCase->generateMailCode($formData));
            });
    }

    private function checkFormFilled(Get $get): bool
    {
        $isFilled = $this->isDateFormFilled($get) &&
            $this->isCategoryFormFilled($get) &&
            $this->isSenderFormFilled($get) &&
            $this->isReceiverFormFilled($get) &&
            $this->isDescriptionFormFilled($get);

        return $isFilled;
    }

    private function isDateFormFilled(Get $get): bool
    {
        return !empty($get('mail_date') ?? null);
    }

    private function isCategoryFormFilled(Get $get): bool
    {
        return !empty($get('mail_category_id') ?? null);
    }

    private function isSenderFormFilled(Get $get): bool
    {
        return !empty($get('sender_name') ?? null);
    }

    private function isReceiverFormFilled(Get $get): bool
    {
        return !empty($get('receiver_name') ?? null);
    }

    private function isDescriptionFormFilled(Get $get): bool
    {
        return !empty($get('description') ?? null);
    }
}
