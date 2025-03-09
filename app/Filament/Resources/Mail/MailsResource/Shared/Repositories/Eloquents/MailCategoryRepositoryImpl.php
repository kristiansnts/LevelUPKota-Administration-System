<?php

namespace App\Filament\Resources\Mail\MailsResource\Shared\Repositories\Eloquents;

use App\Filament\Resources\Mail\MailsResource\Shared\Repositories\Contracts\MailCategoryRepository;
use App\Models\MailCategory;

class MailCategoryRepositoryImpl implements MailCategoryRepository
{
    public function getMailCategoryNameById(int $id): string
    {
        return MailCategory::find($id)->name;
    }

    public function getMailCategoryDescById(int $id): string
    {
        return MailCategory::find($id)->description;
    }

    public function getMailCategoryCountById(int $id): ?int
    {
        return MailCategory::find($id)->count;
    }
}
