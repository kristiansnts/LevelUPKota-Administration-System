<?php

namespace App\Filament\Resources\Mail\MailsInResource\Pages;

use App\Enums\MailStatusEnum;
use App\Filament\Resources\Mail\MailsInResource;
use App\Filament\Shared\Services\ModelQueryService;
use App\Models\MailUser;
use Filament\Resources\Pages\CreateRecord;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Storage;

class CreateMailsIn extends CreateRecord
{
    protected static string $resource = MailsInResource::class;

    protected static bool $canCreateAnother = false;

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
