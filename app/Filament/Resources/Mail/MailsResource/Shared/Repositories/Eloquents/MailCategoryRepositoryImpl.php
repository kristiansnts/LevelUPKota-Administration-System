<?php

namespace App\Filament\Resources\Mail\MailsResource\Shared\Repositories\Eloquents;

use App\Filament\Resources\Mail\MailsResource\Shared\Repositories\Contracts\MailCategoryRepository;
use App\Models\MailCategory;
use App\Filament\Shared\Services\ModelQueryService;
use App\Models\User;
use App\Models\MailCategoryUser;

class MailCategoryRepositoryImpl implements MailCategoryRepository
{
    private readonly User $user;

    public function __construct()
    {
        $this->user = ModelQueryService::getUserModel();
    }

    public static function make(): self
    {
        return new self;
    }

    public function getMailCategoryNameById(int $id): string
    {
        return MailCategory::find($id)->name ?? '';
    }

    public function getMailCategoryDescById(int $id): string
    {
        return MailCategory::find($id)->description ?? '';
    }

    public function createMailCategoryUser(array $data): int
    {
        $mailCategory = MailCategory::create($data);
        
        MailCategoryUser::create([
            'mail_category_id' => $mailCategory->id,
            'city_id' => $this->user->city_id,
            'district_id' => $this->user->district_id ?? null,
        ]);

        return $mailCategory->id;
    }
}
