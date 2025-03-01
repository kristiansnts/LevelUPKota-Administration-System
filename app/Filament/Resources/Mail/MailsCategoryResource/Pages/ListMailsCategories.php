<?php

namespace App\Filament\Resources\Mail\MailsCategoryResource\Pages;

use App\Filament\Resources\Mail\MailsCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMailsCategories extends ListRecords
{
    protected static string $resource = MailsCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Kategori Surat'),
        ];
    }
}
