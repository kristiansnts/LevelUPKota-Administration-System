<?php

namespace App\Filament\Resources\Mail\MailsOutResource\Pages;

use App\Filament\Resources\Mail\MailsOutResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\Mail\MailsResource\Shared\Services\UpdateFileIdGoogleService;

class EditMailsOut extends EditRecord
{
    protected static string $resource = MailsOutResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    public function mount(int | string $record): void
    {
        parent::mount($record);
        if ($this->record->file_name) {
            (new UpdateFileIdGoogleService())->updateFileId($this->record);
        }
    }
}
