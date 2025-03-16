<?php

namespace App\Filament\Resources\Mail\MailsInResource\Pages;

use App\Filament\Resources\Mail\MailsInResource;
use App\Models\Mail;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

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
}
