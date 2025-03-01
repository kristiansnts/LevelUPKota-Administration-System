<?php

declare(strict_types=1);

namespace App\Filament\Resources\Mail\MailsOutResource\Actions;

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
            ->disabled(fn (Get $get): bool => empty($get('mail_code') ?? null))
            ->size(ActionSize::ExtraSmall)
            ->icon('heroicon-m-magnifying-glass')
            ->action(function (Get $get, Set $set, Livewire $livewire): void {});
    }
}
