<?php

namespace App\Filament\Resources\Mail\MailsResource\Pages;

use App\Filament\Resources\Mail\MailsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Tab;

class ListMails extends ListRecords
{
    protected static string $resource = MailsResource::class;

    public function getTabs(): array
    {
        return [
            'Surat Keluar' => Tab::make('Surat Keluar'),
            'Surat Masuk' => Tab::make('Surat Masuk'),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
