<?php

namespace App\Filament\Resources\Mail\MailsOutResource\Pages;

use App\Filament\Resources\Mail\MailsOutResource;
use App\Filament\Shared\Services\ModelQueryService;
use App\Models\MailUser;
use Filament\Resources\Pages\CreateRecord;

class CreateMailsOut extends CreateRecord
{
    protected static string $resource = MailsOutResource::class;

    protected static bool $canCreateAnother = false;

    public function mutateFormDataBeforeCreate(array $data): array
    {
        $data['status'] = MailStatusEnum::DRAFT->value;

        return $data;
    }

    public function getFormActions(): array
    {
        return [
            parent::getSubmitFormAction()
                ->label('Buat Surat Keluar')
                ->disabled(fn (): bool => empty($this->data['mail_code'])),
            parent::getCancelFormAction()->label('Batal'),
        ];
    }

    public function getRecord(): ?\App\Models\Mail
    {
        return $this->record;
    }

    protected function afterCreate(): void
    {
        if (! $this->record instanceof \Illuminate\Database\Eloquent\Model) {
            return;
        }

        $user = ModelQueryService::getUserModel();

        MailUser::create([
            'mail_id' => $this->getRecord()?->id,
            'city_id' => $user->city_id,
            'district_id' => $user->district_id ?? null,
        ]);
    }
}
