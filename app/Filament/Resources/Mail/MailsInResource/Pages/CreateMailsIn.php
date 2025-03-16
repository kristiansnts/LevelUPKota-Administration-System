<?php

namespace App\Filament\Resources\Mail\MailsInResource\Pages;

use App\Enums\MailStatusEnum;
use App\Filament\Resources\Mail\MailsInResource;
use App\Filament\Shared\Services\ModelQueryService;
use App\Models\MailUser;
use Filament\Resources\Pages\CreateRecord;

class CreateMailsIn extends CreateRecord
{
    protected static string $resource = MailsInResource::class;

    protected static bool $canCreateAnother = false;

    public function mutateFormDataBeforeCreate(array $data): array
    {
        if (empty($data['link'])) {
            $data['status'] = MailStatusEnum::DRAFT->value;
        } else {
            $data['status'] = MailStatusEnum::UPLOADED->value;
        }

        return $data;
    }

    protected function afterCreate(): void
    {
        if (! $this->record instanceof \App\Models\Mail) {
            return;
        }

        $user = ModelQueryService::getUserModel();

        MailUser::create([
            'mail_id' => $this->record->id,
            'city_id' => $user->city_id,
            'district_id' => $user->district_id ?? null,
        ]);
    }
}
