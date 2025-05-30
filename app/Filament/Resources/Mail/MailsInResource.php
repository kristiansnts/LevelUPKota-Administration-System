<?php

namespace App\Filament\Resources\Mail;

use App\Filament\Resources\Mail\MailsInResource\Pages;
use App\Filament\Resources\Mail\MailsResource\Form\MailInfoForm;
use App\Filament\Shared\Services\ResourceScopeService;
use App\Models\Mail;
use App\Models\MailUser;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Filament\Forms\Get;
use App\Enums\MailTypeEnum;
use App\Enums\MailStatusEnum;
use App\Helpers\StringHelper;
use Illuminate\Database\Eloquent\Collection;
use App\Filament\Shared\Actions\DeleteGoogleFileAction;

class MailsInResource extends Resource
{
    protected static ?string $model = Mail::class;

    protected static ?string $modelLabel = 'Rekapitulasi Surat Masuk';

    protected static ?string $navigationIcon = 'heroicon-c-envelope-open';

    protected static ?string $navigationGroup = 'Surat';

    protected static ?string $slug = 'surat-masuk';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Surat Masuk')
                    ->schema(MailInfoForm::getFormSchema())->columnSpan(2),
                Forms\Components\Section::make('Data Surat Masuk')
                    ->schema([
                        Forms\Components\TextInput::make('mail_code')
                            ->label('Nomor Surat'),
                        Forms\Components\FileUpload::make('file_name')
                            ->label('Upload Surat')
                            ->disk('google')
                            ->acceptedFileTypes(['application/pdf', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])
                            ->directory(function () {
                              return StringHelper::setMailInDirNameByAddress();
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
                            ),
                        Forms\Components\Hidden::make('type')
                            ->default(MailTypeEnum::IN->value),
                    ])->columnSpan(1),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordUrl(null)
            ->columns([
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->getStateUsing(function (Mail $record): string {
                        $state = empty($record->file_name) ? MailStatusEnum::DRAFT->value : MailStatusEnum::UPLOADED->value;
                        return MailStatusEnum::from($state)->getLabel();
                    })
                    ->color(function (Mail $record): string {
                        return empty($record->file_name) ? 'warning' : 'success';
                    }),
                Tables\Columns\TextColumn::make('mail_code')
                    ->label('Nomor Surat'),
                Tables\Columns\TextColumn::make('mail_date')
                    ->label('Tanggal Surat')
                    ->dateTime('d M Y'),
                Tables\Columns\TextColumn::make('sender_name')
                    ->label('Pengirim'),
                Tables\Columns\TextColumn::make('receiver_name')
                    ->label('Penerima'),
                Tables\Columns\TextColumn::make('mailCategory.description')
                    ->label('Kategori'),
                Tables\Columns\TextColumn::make('description')
                    ->label('Keterangan')
                    ->wrap(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options(MailStatusEnum::class)
                    ->query(function (Builder $query, array $data): Builder {
                        if (!isset($data['value']) || $data['value'] === '') {
                            return $query;
                        }
                        
                        if ($data['value'] === MailStatusEnum::DRAFT->value) {
                            return $query->where(function ($q) {
                                $q->whereNull('file_name')->orWhere('file_name', '');
                            });
                        }
                        
                        if ($data['value'] === MailStatusEnum::UPLOADED->value) {
                            return $query->whereNotNull('file_name')->where('file_name', '!=', '');
                        }
                        
                        return $query;
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Edit Surat')
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
                    Tables\Actions\DeleteBulkAction::make()
                        ->before(function (Collection $records) {
                            // Delete related mail_user entries first
                            foreach ($records as $record) {
                                $record->mailUsers()->delete();
                            }
                        }),
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
            ->where('type', 'in');
    }

    public static function getRelations(): array
    {
        return [

        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMailsIns::route('/'),
            'create' => Pages\CreateMailsIn::route('/create'),
            'edit' => Pages\EditMailsIn::route('/{record}/edit'),
        ];
    }
}
