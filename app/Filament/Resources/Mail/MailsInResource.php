<?php

namespace App\Filament\Resources\Mail;

use App\Enums\MailTypeEnum;
use App\Filament\Resources\Mail\MailsInResource\Pages;
use App\Filament\Shared\Services\ResourceScopeService;
use App\Models\Mail;
use App\Models\MailCategory;
use App\Models\MailUser;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

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
                    ->schema([
                        Forms\Components\DatePicker::make('mail_date')
                            ->label('Tanggal Surat')
                            ->native(false)
                            ->required(),
                        Forms\Components\Select::make('mail_category_id')
                            ->label('Kategori Surat')
                            ->searchable()
                            ->preload()
                            ->options(MailCategory::query()->pluck('description', 'id'))
                            ->required(),
                        Forms\Components\TextInput::make('sender_name')
                            ->label('Pengirim')
                            ->required(),
                        Forms\Components\TagsInput::make('receiver_name')
                            ->label('Penerima')
                            ->separator(',')
                            ->splitKeys(['Enter', 'Tab'])
                            ->placeholder('Masukkan penerima')
                            ->required(),
                        Forms\Components\Textarea::make('description')
                            ->label('Keterangan')
                            ->required(),
                    ])->columnSpan(2),
                Forms\Components\Section::make('Data Surat Masuk')
                    ->schema([
                        Forms\Components\TextInput::make('mail_code')
                            ->label('Nomor Surat'),
                        Forms\Components\FileUpload::make('link')
                            ->label('Upload Surat')
                            ->default('#')
                            ->acceptedFileTypes(['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document']),
                        Forms\Components\Hidden::make('type')
                            ->default(MailTypeEnum::IN->value),
                    ])->columnSpan(1),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
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
                    ->label('Keterangan'),

            ])
            ->filters([

            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Edit')
                    ->icon('heroicon-c-pencil'),
                Tables\Actions\ViewAction::make()
                    ->label('Lihat')
                    ->icon('heroicon-c-eye'),
            ])
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
