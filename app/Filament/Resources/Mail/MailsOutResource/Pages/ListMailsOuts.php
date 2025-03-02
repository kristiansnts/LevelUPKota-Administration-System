<?php

namespace App\Filament\Resources\Mail\MailsOutResource\Pages;

use App\Filament\Resources\Mail\MailsOutResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMailsOuts extends ListRecords
{
    protected static string $resource = MailsOutResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Surat Keluar'),
        ];
    }
}
