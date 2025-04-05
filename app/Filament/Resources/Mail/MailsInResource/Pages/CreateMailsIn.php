<?php

namespace App\Filament\Resources\Mail\MailsInResource\Pages;

use App\Filament\Resources\Mail\MailsInResource;
use App\Filament\Resources\Mail\MailsResource\Shared\Services\CreatePivotMailService;
use App\Filament\Resources\Mail\MailsResource\Shared\Services\UpdateFileIdGoogleService;
use Filament\Resources\Pages\CreateRecord;
use Filament\Actions\Action;

class CreateMailsIn extends CreateRecord
{
    protected static string $resource = MailsInResource::class;

    protected static bool $canCreateAnother = false;

    protected function afterCreate(): void
    {
        if (! $this->record instanceof \App\Models\Mail) {
            return;
        }

        (new CreatePivotMailService())->createMailUserPivot($this->record);

        (new UpdateFileIdGoogleService())->updateFileId($this->record);
    }

    protected function getCreateFormAction(): Action
    {
        return Action::make('create')
            ->label('Catat Surat Masuk')
            ->submit('create')
            ->keyBindings(['mod+s']);
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
