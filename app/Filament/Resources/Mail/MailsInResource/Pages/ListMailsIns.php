<?php

namespace App\Filament\Resources\Mail\MailsInResource\Pages;

use App\Filament\Resources\Mail\MailsInResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMailsIns extends ListRecords
{
    protected static string $resource = MailsInResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Surat Masuk'),
        ];
    }
}
