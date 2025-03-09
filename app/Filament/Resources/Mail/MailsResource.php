<?php

namespace App\Filament\Resources\Mail;

use App\Filament\Resources\Mail\MailsResource\Pages;
use App\Filament\Shared\Services\ResourceScopeService;
use App\Models\Mail;
use App\Models\MailUser;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class MailsResource extends Resource
{
    protected static ?string $model = Mail::class;

    protected static ?string $slug = 'list-file-surat';

    protected static ?string $modelLabel = 'List File Surat';

    protected static ?string $navigationGroup = 'Surat';

    protected static ?string $navigationLabel = 'List File Surat';

    protected static ?string $navigationIcon = 'heroicon-s-folder-arrow-down';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

            ])
            ->filters([

            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [

        ];
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder<Mail>
     */
    public static function getEloquentQuery(): Builder
    {
        /** @var \Illuminate\Database\Eloquent\Builder<MailUser> $mailIds */
        $mailIds = ResourceScopeService::userScope(
            MailUser::query(),
            'mail_id'
        );

        /** @var \Illuminate\Database\Eloquent\Builder<Mail> */
        return parent::getEloquentQuery()
            ->whereIn('id', $mailIds);
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMails::route('/'),
            'create' => Pages\CreateMails::route('/create'),
            'edit' => Pages\EditMails::route('/{record}/edit'),
        ];
    }
}
