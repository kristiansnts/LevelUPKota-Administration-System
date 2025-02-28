<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MailResource\Pages;
use App\Filament\Resources\MailResource\RelationManagers;
use App\Models\Mail;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Get;

class MailResource extends Resource
{
    protected static ?string $model = Mail::class;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';
    protected static ?string $navigationLabel = 'Mail';
    protected static ?string $navigationGroup = 'System';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('mail_code')
                    ->label('Nomor Surat')
                    ->default(function (Get $get) {
                        $mailDate = $get('mail_date');

                        return 'Surat ' . date('Y', strtotime($mailDate)) . '/' . date('m', strtotime($mailDate)) . '/' . date('d', strtotime($mailDate));
                    })
                    ->disabled()
                    ->required(),
                Forms\Components\DatePicker::make('mail_date')
                    ->label('Tanggal Surat')
                    ->live()
                    ->required(),
                Forms\Components\TextInput::make('sender_name')
                    ->label('Pengirim')
                    ->required(),
                Forms\Components\TextInput::make('receiver_name')
                    ->label('Penerima')
                    ->required(),
                Forms\Components\Textarea::make('description')
                    ->label('Keterangan')
                    ->required(),
                Forms\Components\Select::make('type')
                    ->label('Jenis')
                    ->options([
                        'in' => 'Masuk',
                        'out' => 'Keluar',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('link')
                    ->label('Link'),
                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options([
                        'read' => 'Dibaca',
                        'unread' => 'Belum Dibaca',
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('mail_code')
                    ->label('Nomor Surat'),
                TextColumn::make('mail_date')
                    ->label('Tanggal Surat'),
                TextColumn::make('mail_category_id')
                    ->label('Jenis Surat'),
                TextColumn::make('sender_name')
                    ->label('Pengirim'),
                TextColumn::make('receiver_name')
                    ->label('Penerima'),
                TextColumn::make('description')
                    ->label('Keterangan'),
                TextColumn::make('type')
                    ->label('Kategori'),
                TextColumn::make('link')
                    ->label('Link'),
                TextColumn::make('status')
                    ->label('Status'),
            ])
            ->filters([
                //
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMails::route('/'),
            'create' => Pages\CreateMail::route('/create'),
            'edit' => Pages\EditMail::route('/{record}/edit'),
        ];
    }
}
