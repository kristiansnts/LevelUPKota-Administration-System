<?php

namespace App\Filament\Resources\Mail;

use App\Enums\MailStatusEnum;
use App\Enums\MailTypeEnum;
use App\Filament\Resources\Mail\MailsOutResource\Actions\MailCodeCreateAction;
use App\Filament\Resources\Mail\MailsOutResource\Pages;
use App\Filament\Resources\Mail\MailsResource\Form\MailInfoForm;
use App\Filament\Shared\Services\ResourceScopeService;
use App\Helpers\RouteHelper;
use App\Models\Mail;
use App\Models\MailUser;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use App\Helpers\StringHelper;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use App\Filament\Shared\Actions\DeleteGoogleFileAction;

class MailsOutResource extends Resource
{
    protected static ?string $model = Mail::class;

    protected static ?string $modelLabel = 'Rekapitulasi Surat Keluar';

    protected static ?string $navigationIcon = 'heroicon-c-envelope';

    protected static ?string $navigationGroup = 'Surat';

    protected static ?string $slug = 'surat-keluar';

    protected static ?string $navigationLabel = 'Rekapitulasi Surat Keluar';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Surat Keluar')
                    ->schema(MailInfoForm::getFormSchema())->columnSpan(2),
                Forms\Components\Section::make('Data Surat Keluar')
                    ->schema([
                        Forms\Components\TextInput::make('mail_code')
                            ->label('Nomor Surat')
                            ->disabled(fn (Get $get): bool => $get('mail_code') === null)
                            ->hintActions([
                                MailCodeCreateAction::make('mailCodeCreateAction'),
                            ])
                            ->live()
                            ->readOnly(),
                        Forms\Components\FileUpload::make('file_name')
                            ->label('Upload Surat')
                            ->disk('google')
                            ->directory(function () {
                                return StringHelper::setMailOutDirNameByAddress();
                            })
                            ->hintActions([
                                DeleteGoogleFileAction::make('delete_file')
                                    ->fileColumn('file_name')
                                    ->additionalColumn('file_id')
                            ])
                            ->getUploadedFileNameForStorageUsing(
                                function (TemporaryUploadedFile $file, Get $get): string {
                                    $mailCode = $get('mail_code');
                                    $mailNumber = explode('/', $mailCode)[0];
                                    $mailCategory = explode('/', $mailCode)[2];
                                    $description = $get('description');
                                    return "{$mailNumber} - {$mailCategory} - {$description}." . $file->getClientOriginalExtension();
                              }
                            )
                            ->visibility('public')
                    ])->columnSpan(1),
                Forms\Components\Hidden::make('type')
                    ->default(MailTypeEnum::OUT->value),
            ])->columns(3);
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
                    ->label('Pengirim'),
                Tables\Columns\TextColumn::make('receiver_name')
                    ->label('Penerima'),
                Tables\Columns\TextColumn::make('description')
                    ->label('Keterangan')
                    ->wrap(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Dibuat')
                    ->dateTime('d-m-Y H:i:s'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options(MailStatusEnum::class),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Edit dan Upload')
                    ->icon('heroicon-c-pencil'),
                Tables\Actions\ViewAction::make()
                    ->label('Lihat Surat')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->visible(fn (Mail $record): bool => !empty($record->file_name))
                    ->url(function (Mail $record): string {
                        if ($record->file_name) {
                            return StringHelper::getMailLink($record->file_name);
                        }
                        return '';
                    })
                    ->extraAttributes([
                        'target' => '_blank',
                    ]),
            ], position: Tables\Enums\ActionsPosition::BeforeColumns)
            ->actionsColumnLabel('Aksi')
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    /**
     * @return Builder<Mail>
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
            ->whereIn('id', $mailIds)
            ->where('type', MailTypeEnum::OUT->value);
    }

    public static function getRelations(): array
    {
        return [

        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMailsOuts::route('/'),
            'create' => Pages\CreateMailsOut::route('/create'),
            'edit' => Pages\EditMailsOut::route('/{record}/edit'),
        ];
    }
}
