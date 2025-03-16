<?php

declare(strict_types=1);

namespace App\Filament\Resources\Mail\MailsOutResource\Actions;

use App\Filament\Resources\Mail\MailsResource\Shared\UseCases\GeneratedMailUseCase;
use App\Helpers\NotificationHelper;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Support\Enums\ActionSize;

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
            ->size(ActionSize::ExtraSmall)
            ->icon('heroicon-m-magnifying-glass')
            ->action(function (Get $get, Set $set): void {
                if ($this->checkRequiredFieldsEmpty($get)) {
                    NotificationHelper::error('Harap mengisi semua Informasi Surat');

                    return;
                }
                $formData = [
                    'mail_date' => $get('mail_date'),
                    'mail_category_id' => $get('mail_category_id'),
                    'sender_name' => $get('sender_name'),
                    'receiver_name' => $get('receiver_name'),
                    'description' => $get('description'),
                ];

                $generateMailCodeUseCase = app(GeneratedMailUseCase::class);
                /**
                 * @var string $mailCode
                 */
                $mailCode = $generateMailCodeUseCase->generateMailCode($formData);

                $set('mail_code', $mailCode);
            });
    }

    private function checkRequiredFieldsEmpty(Get $get): bool
    {
        $requiredFields = [
            'mail_date',
            'mail_category_id',
            'sender_name',
            'receiver_name',
            'description',
        ];

        foreach ($requiredFields as $field) {
            if (empty($get($field) ?? null)) {
                return true;
            }
        }

        return false;
    }
}
