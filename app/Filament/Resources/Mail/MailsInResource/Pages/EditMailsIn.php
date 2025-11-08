<?php

namespace App\Filament\Resources\Mail\MailsInResource\Pages;

use App\Filament\Resources\Mail\MailsInResource;
use App\Models\Mail;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Actions\Action;
use App\Filament\Resources\Mail\MailsResource\Shared\Services\UpdateFileIdGoogleService;

class EditMailsIn extends EditRecord
{
    protected static string $resource = MailsInResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->before(function (Mail $record): void {
                    if ($record->user()->exists()) {
                        $record->user()->detach();
                    }
                }),
        ];
    }

    protected function getSaveFormAction(): Action
    {
        return Action::make('save')
            ->label('Ubah Surat Masuk')
            ->submit('save')
            ->keyBindings(['mod+s']);
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public function mount(int | string $record): void
    {
        parent::mount($record);
        if ($this->record->file_name) {
            (new UpdateFileIdGoogleService())->updateFileId($this->record);
        }
    }

    protected function afterSave(): void
    {
        if (! $this->record instanceof \App\Models\Mail) {
            return;
        }

        if ($this->record->file_name) {
            (new UpdateFileIdGoogleService())->updateFileId($this->record);
        }
    }

}
