<?php

namespace App\Filament\Resources\Mail;

use App\Enums\MailStatusEnum;
use App\Filament\Resources\Mail\MailsResource\Pages;
use App\Filament\Shared\Services\ResourceScopeService;
use App\Models\Mail;
use App\Models\MailUser;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use App\Helpers\StringHelper;
use Filament\Tables\Actions\Action;

class MailsResource extends Resource
{
    protected static ?string $model = Mail::class;

    protected static ?string $modelLabel = 'List Surat';
    
    protected static ?string $navigationGroup = 'Surat';
    
    protected static ?string $navigationLabel = 'List File Surat';
    
    protected static ?string $slug = 'list-file-surat';

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
                Tables\Columns\TextColumn::make('file_name')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(function (Mail $record): string {
                        $state = empty($record->file_name) ? MailStatusEnum::DRAFT->value : MailStatusEnum::UPLOADED->value;
                        return MailStatusEnum::from($state)->getLabel();
                    })
                    ->color(function (Mail $record): string {
                        return empty($record->file_name) ? 'warning' : 'success';
                    }),
                Tables\Columns\TextColumn::make('mail_code')
                    ->label('Nomor Surat'),
                Tables\Columns\TextColumn::make('mail_date')
                    ->dateTime('d M Y')
                    ->label('Tanggal Surat'),
                Tables\Columns\TextColumn::make('sender_name')
                    ->searchable()
                    ->label('Pengirim'),
                Tables\Columns\TextColumn::make('receiver_name')
                    ->searchable()
                    ->label('Penerima'),
                Tables\Columns\TextColumn::make('description')
                    ->searchable()
                    ->label('Keterangan'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d M Y')
                    ->label('Tanggal Dibuat'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('file_name')
                    ->label('Status')
                    ->options(MailStatusEnum::class),
            ])
            ->filtersTriggerAction(
                fn (Action $action): Action => $action
                    ->button()
                    ->label('Filter'),
            )
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Lihat')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->url(fn (Mail $record): string => StringHelper::getMailLink($record->file_name)),
            ], position: Tables\Enums\ActionsPosition::BeforeColumns)
            ->actionsColumnLabel('Aksi')
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
