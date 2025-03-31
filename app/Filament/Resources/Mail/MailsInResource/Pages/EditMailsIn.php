<?php

namespace App\Filament\Resources\Mail\MailsInResource\Pages;

use App\Filament\Resources\Mail\MailsInResource;
use App\Models\Mail;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Storage;

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
        $fileName = Mail::where('id', $this->record->id)->first()->file_name;
        if ($fileName) {
            $adapter = Storage::disk('google')->getAdapter();
            $fileId = $adapter->getMetadata($fileName);
            $this->record->file_id = $fileId['extraMetadata']['id'];
            $this->record->saveQuietly();
        }
    }

}
