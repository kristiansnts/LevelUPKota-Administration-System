<?php

namespace App\Filament\Resources\Mail\MailsResource\Shared\Repositories\Contracts;

interface MailCategoryRepository
{
    public function getMailCategoryNameById(int $id): string;

    public function getMailCategoryCountByCategoryId(int $id): ?int;

    public function setMailCategoryCountByCategoryId(int $id): void;

    public function getMailCategoryDescById(int $id): string;
}
