<?php

namespace App\Filament\Resources\Finance;

use App\Filament\Resources\Finance\TransactionResource\Pages;
use App\Models\Finance;
use App\Models\TransactionCategory;
use App\Models\TransactionType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TransactionResource extends Resource
{
    protected static ?string $model = Finance::class;

    protected static ?string $modelLabel = 'Transaksi';

    protected static ?string $navigationGroup = 'Keuangan';

    protected static ?string $navigationLabel = 'Transaksi';

    protected static ?string $slug = 'transaksi';

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Transaksi')
                    ->description('Informasi transaksi dan bukti transaksi')
                    ->icon('heroicon-o-currency-dollar')
                    ->schema([
                        Forms\Components\Section::make('Informasi Transaksi')
                            ->schema([
                                // create transaction period migration
                                Forms\Components\TextInput::make('period')
                                    ->label('Periode')
                                    ->required(),
                                Forms\Components\DatePicker::make('transaction_date')
                                    ->label('Tanggal Transaksi')
                                    ->native(false)
                                    ->required(),
                                Forms\Components\TextInput::make('description')
                                    ->label('Keterangan')
                                    ->required(),
                                Forms\Components\TextInput::make('invoice_code')
                                    ->label('Nomor Kwitansi')
                                    ->required(),
                            ]),
                        Forms\Components\Section::make('Transaksi Kategori & Tipe')
                            ->schema([
                                Forms\Components\Select::make('transaction_category_id')
                                    ->label('Kategori Transaksi')
                                    ->options(TransactionCategory::pluck('name', 'id'))
                                    ->createOptionForm([
                                        Forms\Components\TextInput::make('name')
                                            ->label('Nama')
                                            ->placeholder('Masukkan nama kategori transaksi co: Persembahan Kasih, Donasi, ...')
                                            ->required()
                                            ->maxLength(255),
                                        Forms\Components\Textarea::make('description')
                                            ->label('Deskripsi')
                                            ->placeholder('Masukkan detail kategori transaksi')
                                            ->required()
                                            ->rows(3),
                                    ])
                                    ->createOptionModalHeading('Buat Kategori Transaksi')
                                    ->searchable()
                                    ->preload()
                                    ->required(),
                                Forms\Components\Select::make('transaction_type_id')
                                    ->label('Tipe Transaksi')
                                    ->createOptionForm([
                                        Forms\Components\TextInput::make('name')
                                            ->label('Nama')
                                            ->placeholder('Masukkan nama tipe transaksi co: Cash, QRIS, Transfer BCA a/n ...')
                                            ->required()
                                            ->maxLength(255),
                                        Forms\Components\Textarea::make('description')
                                            ->label('Deskripsi')
                                            ->placeholder('Masukkan detail tipe transaksi')
                                            ->required()
                                            ->rows(3),
                                    ])
                                    ->createOptionModalHeading('Buat Tipe Transaksi')
                                    ->options(TransactionType::pluck('name', 'id'))
                                    ->searchable()
                                    ->preload()
                                    ->required(),
                            ]),
                    ])->columnSpan(2),
                Forms\Components\Section::make('Bukti Transaksi')
                    ->description('Bukti transaksi berupa gambar')
                    ->icon('heroicon-o-document-text')
                    ->schema([
                        Forms\Components\FileUpload::make('transaction_proof_link')
                            ->label('Upload Bukti Transaksi')
                            ->image()
                            ->imageEditor()
                            ->imageEditorAspectRatios([
                                '16:9',
                                '4:3',
                            ]),
                    ])->columnSpan(1),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('period')
                    ->label('Periode')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('transaction_date')
                    ->label('Tanggal Transaksi')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('transaction_category.name')
                    ->label('Kategori Transaksi')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')
                    ->label('Keterangan')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('transaction_type.name')
                    ->label('Tipe Transaksi')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('amount_in')
                    ->label('Dana Masuk')
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount_out')
                    ->label('Dana Keluar')
                    ->sortable(),
                Tables\Columns\TextColumn::make('invoice_code')
                    ->label('Nomor Kwitansi')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('saldo')
                    ->label('Saldo')
                    ->sortable(),
            ])
            ->filters([

            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->icon('heroicon-o-pencil')
                    ->color('warning'),
                Tables\Actions\ViewAction::make('bukti_transaksi')
                    ->label('Bukti Transaksi')
                    ->icon('heroicon-o-eye')
                    ->color('info'),
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
            'index' => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }
}
