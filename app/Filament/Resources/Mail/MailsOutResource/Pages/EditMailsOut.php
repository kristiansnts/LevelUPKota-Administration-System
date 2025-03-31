<?php

namespace App\Filament\Resources\Mail\MailsOutResource\Pages;

use App\Enums\MailStatusEnum;
use App\Filament\Resources\Mail\MailsOutResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Models\Mail;
use Illuminate\Support\Facades\Storage;

class EditMailsOut extends EditRecord
{
    protected static string $resource = MailsOutResource::class;

    protected function afterSave(): void
    {
        /** @var array<mixed> $data */
        $data = $this->data;
        /** @var string|null $link */
        $link = $data['link'];

        if ($link !== null && $this->record instanceof \Illuminate\Database\Eloquent\Model) {
            $this->record->update([
                'status' => MailStatusEnum::UPLOADED->value,
                'link' => $link,
            ]);
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
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
