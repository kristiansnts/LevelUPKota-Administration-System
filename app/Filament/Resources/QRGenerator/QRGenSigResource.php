<?php

namespace App\Filament\Resources\QRGenerator;

use App\Filament\Resources\QRGenerator\QRGenSigResource\Pages;
use App\Filament\Resources\QRGenerator\QRGenSigResource\RelationManagers;
use App\Models\QrGeneratorSigner;
use App\Models\QRGenerator;
use App\Models\QRSigner;
use App\Models\Mail;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use App\Filament\Shared\Services\ResourceScopeService;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Checkbox;
use App\Models\MailUser;
use Filament\Tables\Actions\ActionGroup;
use Filament\Support\Enums\ActionSize;
use Filament\Tables\Actions\Action;

class QRGenSigResource extends Resource
{
    protected static ?string $model = QrGeneratorSigner::class;

    protected static ?string $modelLabel = 'Buat Tanda Tangan QR';

    protected static ?string $navigationGroup = 'Tanda Tangan';

    protected static ?string $navigationLabel = 'Buat Tanda Tangan QR';

    protected static ?string $navigationIcon = 'heroicon-o-qr-code';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Dokumen')
                    ->schema([
                        Select::make('document_id')
                            ->label('Dokumen')
                            ->options(function () {
                                // Get mail IDs that are scoped to user's city/district
                                $mailIds = ResourceScopeService::userScope(
                                    MailUser::query(),
                                    'mail_id'
                                );
                                
                                return Mail::whereIn('id', $mailIds)
                                    ->get()
                                    ->mapWithKeys(function ($mail) {
                                        return [$mail->id => $mail->mail_code . ' - ' . $mail->description];
                                    });
                            })
                            ->searchable()
                            ->preload()
                            ->required(),
                        Repeater::make('signers')
                            ->label('Penanda Tangan')
                            ->schema([
                                Select::make('qr_signer_id')
                                    ->label('Penanda Tangan')
                                    ->relationship('qrSigner', 'signer_name')
                                    ->searchable()
                                    ->preload()
                                    ->required(),
                                Checkbox::make('is_sign')
                                    ->label('Sudah di tandatangani?')
                                    ->default(false)
                            ])
                            ->minItems(1)
                            ->addActionLabel('Tambah Penanda Tangan'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->columns([
                TextColumn::make('qr_generator_id')
                    ->label('QR ID')
                    ->limit(8)
                    ->tooltip(fn ($record) => $record->qr_generator_id),
                TextColumn::make('qrGenerator.document.mail_code')
                    ->label('Kode Surat')
                    ->searchable(),
                TextColumn::make('qrGenerator.document.description')
                    ->label('Deskripsi Dokumen')
                    ->limit(50),
                TextColumn::make('signers_count')
                    ->label('Jumlah Penanda Tangan')
                    ->getStateUsing(function ($record) {
                        return QrGeneratorSigner::where('qr_generator_id', $record->qr_generator_id)->count();
                    }),
                TextColumn::make('signed_count')
                    ->label('Sudah Ditandatangani')
                    ->getStateUsing(function ($record) {
                        return QrGeneratorSigner::where('qr_generator_id', $record->qr_generator_id)
                            ->where('is_sign', true)->count();
                    }),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('view')
                    ->label('Lihat')
                    ->url(fn (QrGeneratorSigner $record): string => route('qr.document.show', ['qrGeneratorId' => $record->qr_generator_id]))
                    ->icon('heroicon-o-eye')
                    ->color('success')
                    ->size(ActionSize::Small)
                    ->openUrlInNewTab(),
                Tables\Actions\Action::make('download')
                    ->label('Download')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('warning')
                    ->size(ActionSize::Small),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListQRGenSigs::route('/'),
            'create' => Pages\CreateQRGenSig::route('/create'),
            'edit' => Pages\EditQRGenSig::route('/{record}/edit'),
            // 'view' => Pages\ViewQRGenSig::route('/{record}/view'),
        ];
    }
}
