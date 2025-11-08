<?php

namespace App\Filament\Resources\QRGenerator;

use App\Filament\Resources\QRGenerator\QRSignerResource\Pages;
use App\Models\QRSigner;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Hidden;

class QRSignerResource extends Resource
{
    protected static ?string $model = QRSigner::class;

    protected static ?string $modelLabel = 'Penanda Tangan';

    protected static ?string $navigationGroup = 'Tanda Tangan';

    protected static ?string $navigationLabel = 'Penanda Tangan';

    protected static ?string $navigationIcon = 'heroicon-o-user-group';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Info Penanda Tangan')
                    ->schema([
                        TextInput::make('signer_name')
                            ->label('Nama Penanda Tangan')
                            ->required(),
                        TextInput::make('phone_number')
                            ->label('Nomor Telepon')
                            ->tel()
                            ->required(),
                        TextInput::make('signer_position')
                            ->label('Jabatan Penanda Tangan')
                            ->required(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('signer_name')
                    ->label('Nama Penanda Tangan'),
                TextColumn::make('phone_number')
                    ->label('Nomor Telepon'),
                TextColumn::make('signer_position')
                    ->label('Jabatan Penanda Tangan'),
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
            'index' => Pages\ListQRSigners::route('/'),
            'create' => Pages\CreateQRSigner::route('/create'),
            'edit' => Pages\EditQRSigner::route('/{record}/edit'),
        ];
    }
}
