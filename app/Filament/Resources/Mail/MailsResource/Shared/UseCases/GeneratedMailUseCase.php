<?php

namespace App\Filament\Resources\Mail\MailsResource\Shared\UseCases;

use App\Filament\Resources\Mail\MailsResource\Shared\Services\MailCodeService;
use App\Helpers\NotificationHelper;

class GeneratedMailUseCase
{
    public function __construct(
        private readonly MailCodeService $mailCodeService,
    ) {}

    /**
     * @param  array<string, mixed>  $mailData
     */
    public function generateMailCode(array $mailData): string
    {
        try {
            return $this->mailCodeService->generateMailCode($mailData);
        } catch (\Exception $e) {
            NotificationHelper::error('Gagal membuat nomor surat');
            throw $e;
        }
    }
}
