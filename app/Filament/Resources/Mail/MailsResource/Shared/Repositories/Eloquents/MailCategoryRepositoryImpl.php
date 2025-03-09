<?php

namespace App\Filament\Resources\Mail\MailsResource\Shared\Repositories\Eloquents;

use App\Filament\Resources\Mail\MailsResource\Shared\Repositories\Contracts\MailCategoryRepository;
use App\Filament\Shared\Services\ResourceScopeService;
use App\Models\MailCategory;
use App\Models\MailCategoryUser;

class MailCategoryRepositoryImpl implements MailCategoryRepository
{
    public function getMailCategoryNameById(int $id): string
    {
        return MailCategory::find($id)->name ?? '';
    }

    public function getMailCategoryDescById(int $id): string
    {
        return MailCategory::find($id)->description ?? '';
    }

    public function getMailCategoryCountByCategoryId(int $id): ?int
    {
        /** @var \Illuminate\Database\Eloquent\Builder<MailCategoryUser> $query */
        $query = ResourceScopeService::userScope(
            MailCategoryUser::query(),
            'mail_category_id'
        );

        /** @var MailCategoryUser|null $category */
        $category = $query->where('mail_category_id', $id)->first();

        return $category?->order;
    }

    public function setMailCategoryCountByCategoryId(int $id): void
    {
        /** @var \Illuminate\Database\Eloquent\Builder<MailCategoryUser> $query */
        $query = ResourceScopeService::userScope(
            MailCategoryUser::query()
        );

        /** @var MailCategoryUser|null $updateCount */
        $updateCount = $query->where('mail_category_id', $id)->first();

        if ($updateCount) {
            $updateCount->order += 1;
            $updateCount->save();
        }
    }
}
