<?php

namespace App\Filament\Resources\Mail\MailsOutResource\Pages;

use App\Enums\MailStatusEnum;
use App\Filament\Resources\Mail\MailsOutResource;
use App\Filament\Shared\Services\ModelQueryService;
use App\Models\MailUser;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Storage;

class CreateMailsOut extends CreateRecord
{
    protected static string $resource = MailsOutResource::class;

    protected static bool $canCreateAnother = false;

    public function getFormActions(): array
    {
        return [
            parent::getSubmitFormAction()
                ->label('Buat Surat Keluar')
                ->disabled(fn (): bool => empty($this->data['mail_code'])),
            parent::getCancelFormAction()->label('Batal'),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
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

        $adapter = Storage::disk('google')->getAdapter();
        $fileId = $adapter->getMetadata($this->record->file_name);
        $this->record->file_id = $fileId['extraMetadata']['id'];
        $this->record->save();
    }
}
