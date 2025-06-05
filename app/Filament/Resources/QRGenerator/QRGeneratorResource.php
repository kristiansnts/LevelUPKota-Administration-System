<?php

namespace App\Filament\Resources\QRGenerator;

use App\Filament\Resources\QRGenerator\QRGeneratorResource\Pages;
use App\Filament\Resources\QRGenerator\QRGeneratorResource\RelationManagers;
use App\Models\QRGenerator;
use App\Models\Mail;
use App\Models\MailUser;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use App\Filament\Shared\Services\ResourceScopeService;

class QRGeneratorResource extends Resource
{
    protected static ?string $model = QRGenerator::class;

    protected static ?string $modelLabel = 'QR Info';

    protected static ?string $navigationGroup = 'Tanda Tangan';

    protected static ?string $navigationLabel = 'QR Info';

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Dokumen')
                    ->schema([
                        Select::make('document_id')
                            ->label('Dokumen')
                            ->relationship(
                                'document', 
                                'description',
                                modifyQueryUsing: function ($query) {
                                    // Get mail IDs that are scoped to user's city/district
                                    $mailIds = ResourceScopeService::userScope(
                                        MailUser::query(),
                                        'mail_id'
                                    );
                                    
                                    return $query->whereIn('id', $mailIds);
                                }
                            )
                            ->getOptionLabelFromRecordUsing(fn ($record) => $record->mail_code . ' - ' . $record->description)
                            ->searchable(['mail_code', 'description'])
                            ->preload()
                            ->required(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('document.mail_code')
                    ->label('Dokumen')
                    ->formatStateUsing(fn ($record) => $record->document ? $record->document->mail_code . ' - ' . $record->document->description : '-'),
            ])
            ->filters([
                //
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
            //
        ];
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListQRGenerators::route('/'),
            'create' => Pages\CreateQRGenerator::route('/create'),
            'edit' => Pages\EditQRGenerator::route('/{record}/edit'),
        ];
    }
}
