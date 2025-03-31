<?php

namespace App\Filament\Resources\Mail\MailsResource\Shared\UseCases;

use App\Filament\Resources\Mail\MailsResource\Shared\Repositories\Eloquents\MailCategoryRepositoryImpl;
use App\Helpers\NotificationHelper;
use Exception;

class CreateOptionUseCase
{
    public function __construct(
        private readonly MailCategoryRepositoryImpl $mailCategoryRepository,
    ) {}

    public function createMailCategoryUser(array $data): int
    {
        try {
            $mailCategoryId = $this->mailCategoryRepository->createMailCategoryUser($data);

            return $mailCategoryId;
        } catch (Exception $e) {
            // Consider logging the exception here for debugging
            logger()->error($e->getMessage());
            
            NotificationHelper::error('Gagal membuat kategori surat');
            return 0;
        }
    }
}