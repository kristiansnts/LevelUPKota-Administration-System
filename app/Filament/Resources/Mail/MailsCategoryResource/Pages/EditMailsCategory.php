<?php

namespace App\Filament\Resources\Mail\MailsCategoryResource\Pages;

use App\Filament\Resources\Mail\MailsCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMailsCategory extends EditRecord
{
    protected static string $resource = MailsCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
