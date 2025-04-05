<?php

namespace App\Filament\Resources\Mail\MailsOutResource\Pages;

use App\Filament\Resources\Mail\MailsOutResource;
use App\Filament\Resources\Mail\MailsResource\Shared\Services\CreatePivotMailService;
use App\Filament\Resources\Mail\MailsResource\Shared\Services\UpdateFileIdGoogleService;
use Filament\Resources\Pages\CreateRecord;

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

        (new CreatePivotMailService())->createMailUserPivot($this->record);

        (new UpdateFileIdGoogleService())->updateFileId($this->record);
    }
}
