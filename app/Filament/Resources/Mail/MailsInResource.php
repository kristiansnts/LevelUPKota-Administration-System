<?php

namespace App\Filament\Resources\Mail;

use App\Filament\Resources\Mail\MailsInResource\Pages;
use App\Models\Mail;
use App\Models\MailCategory;
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
                            ->acceptedFileTypes(['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])
                            ->required(),
                    ])->columnSpan(1),
            ])->columns(3);
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

    /**
     * @return Builder<Mail>
     */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('type', 'in');
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
