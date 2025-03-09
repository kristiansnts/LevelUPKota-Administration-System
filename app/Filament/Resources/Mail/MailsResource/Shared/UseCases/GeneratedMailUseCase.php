<?php

namespace App\Filament\Resources\Mail\MailsResource\Shared\UseCases;

use App\Filament\Resources\Mail\MailsResource\Shared\Services\MailCategoryService;
use App\Helpers\NotificationHelper;

class GeneratedMailUseCase
{
    public function __construct(
        private readonly MailCategoryService $mailCategoryService,
    ) {}

    /**
     * @param  array<string, mixed>  $mailData
     */
    public function generateMailCode(array $mailData): string
    {
        try {
            return $this->mailCategoryService->generateMailCode($mailData);
        } catch (\Exception $e) {
            NotificationHelper::error('Gagal membuat nomor surat');
            throw $e;
        }
    }
}
