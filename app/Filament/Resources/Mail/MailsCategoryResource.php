<?php

namespace App\Filament\Resources\Mail;

use App\Filament\Resources\Mail\MailsCategoryResource\Pages;
use App\Models\MailCategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class MailsCategoryResource extends Resource
{
    protected static ?string $model = MailCategory::class;

    protected static ?string $modelLabel = 'Kategori Surat';

    protected static ?string $slug = 'kategori-surat';

    protected static ?string $navigationGroup = 'Surat';

    protected static ?string $navigationLabel = 'Kategori Surat';

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Kode Surat')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('description')
                    ->label('Jenis Surat')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Kode Surat')
                    ->badge(),
                Tables\Columns\TextColumn::make('description')
                    ->label('Jenis Surat'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Dibuat')
                    ->dateTime(),
            ])
            ->filters([

            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMailsCategories::route('/'),
            'create' => Pages\CreateMailsCategory::route('/create'),
            'edit' => Pages\EditMailsCategory::route('/{record}/edit'),
        ];
    }
}
