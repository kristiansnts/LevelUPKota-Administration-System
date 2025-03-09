<?php

namespace App\Filament\Resources\Mail;

use App\Filament\Resources\Mail\MailsOutResource\Actions\MailCodeCreateAction;
use App\Filament\Resources\Mail\MailsOutResource\Pages;
use App\Helpers\RouteHelper;
use App\Models\Mail;
use App\Models\MailCategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class MailsOutResource extends Resource
{
    protected static ?string $model = Mail::class;

    protected static ?string $modelLabel = 'Rekapitulasi Surat Keluar';

    protected static ?string $navigationIcon = 'heroicon-o-envelope';

    protected static ?string $navigationGroup = 'Surat';

    protected static ?string $slug = 'surat-keluar';

    protected static ?string $navigationLabel = 'Rekapitulasi Surat Keluar';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Surat Keluar')
                    ->schema([
                        Forms\Components\DatePicker::make('mail_date')
                            ->label('Tanggal Surat')
                            ->native(false)
                            ->live()
                            ->required(),
                        Forms\Components\Select::make('mail_category_id')
                            ->label('Kategori Surat')
                            ->searchable()
                            ->preload()
                            ->live()
                            ->options(MailCategory::query()->pluck('description', 'id'))
                            ->required(),
                        Forms\Components\TextInput::make('sender_name')
                            ->label('Pengirim')
                            ->placeholder('Masukkan nama pengirim')
                            ->live()
                            ->required(),
                        Forms\Components\TagsInput::make('receiver_name')
                            ->label('Penerima')
                            ->separator(',')
                            ->splitKeys(['Enter', 'Tab'])
                            ->live()
                            ->placeholder('Masukkan nama penerima, bisa lebih dari satu')
                            ->required(),
                        Forms\Components\Textarea::make('description')
                            ->label('Keterangan')
                            ->live()
                            ->required(),
                    ])->columnSpan(2),
                Forms\Components\Section::make('Data Surat Keluar')
                    ->schema([
                        Forms\Components\TextInput::make('mail_code')
                            ->label('Nomor Surat')
                            ->disabled()
                            ->hintActions([
                                MailCodeCreateAction::make('mailCodeCreateAction'),
                            ]),
                        Forms\Components\FileUpload::make('link')
                            ->label('Upload Surat')
                            ->acceptedFileTypes(['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])
                            ->required()
                            ->visible(RouteHelper::isRouteName('filament.admin.resources.surat-keluar.edit')),
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
        return parent::getEloquentQuery()->where('type', 'out');
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
