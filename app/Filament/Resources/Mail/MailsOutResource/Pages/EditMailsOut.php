<?php

namespace App\Filament\Resources\Mail\MailsOutResource\Pages;

use App\Enums\MailStatusEnum;
use App\Filament\Resources\Mail\MailsOutResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

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
}
