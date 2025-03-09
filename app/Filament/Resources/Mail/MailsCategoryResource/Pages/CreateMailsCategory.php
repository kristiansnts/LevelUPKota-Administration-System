<?php

namespace App\Filament\Resources\Mail\MailsCategoryResource\Pages;

use App\Filament\Resources\Mail\MailsCategoryResource;
use App\Filament\Shared\Services\ModelQueryService;
use App\Models\MailCategoryUser;
use Filament\Resources\Pages\CreateRecord;

class CreateMailsCategory extends CreateRecord
{
    protected static string $resource = MailsCategoryResource::class;

    public function getRecord(): ?\App\Models\MailCategory
    {
        return $this->record;
    }

    protected function afterCreate(): void
    {
        if (!$this->record instanceof \Illuminate\Database\Eloquent\Model) {
            return;
        }

        $user = ModelQueryService::getUserModel();

        MailCategoryUser::create([
            'mail_category_id' => $this->getRecord()?->id,
            'city_id' => $user->city_id,
            'district_id' => $user->district_id ?? null,
            'order' => 0,
        ]);
    }
}
