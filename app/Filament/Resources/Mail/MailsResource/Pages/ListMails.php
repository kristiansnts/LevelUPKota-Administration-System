<?php

namespace App\Filament\Resources\Mail\MailsResource\Pages;

use App\Filament\Resources\Mail\MailsResource;
use App\Filament\Shared\Services\ResourceScopeService;
use App\Models\MailUser;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListMails extends ListRecords
{
    protected static string $resource = MailsResource::class;

    public function getTabs(): array
    {
        return [
            'Surat Keluar' => Tab::make('Surat Keluar')->modifyQueryUsing(function (Builder $query) {
                $mailIds = ResourceScopeService::userScope(
                    MailUser::query(),
                    'mail_id'
                );

                return $query->whereIn('id', $mailIds)
                    ->where('type', 'out');
            }),
            'Surat Masuk' => Tab::make('Surat Masuk')->modifyQueryUsing(function (Builder $query) {
                $mailIds = ResourceScopeService::userScope(
                    MailUser::query(),
                    'mail_id'
                );

                return $query->whereIn('id', $mailIds)
                    ->where('type', 'in');
            }),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
