<?php

namespace App\Filament\Resources\Finance\TransactionResource\Form;

use App\Filament\Resources\Finance\TransactionCategoryResource\Form\TransactionCategoryCreateForm;
use App\Filament\Resources\Finance\TransactionResource\Shared\UseCases\CreateOptionUseCase;
use Filament\Forms;
use Filament\Forms\Form;

class TransactionForm extends Form
{
    public static function form(Form $form): Form
    {
        return $form
            ->schema(self::getFormSchema())
            ->columns(3);
    }

    /**
     * @return array<int, Forms\Components\Component>
     */
    public static function getFormSchema(): array
    {
        return [
            Forms\Components\Section::make('Transaksi')
                ->description('Informasi transaksi dan bukti transaksi')
                ->icon('heroicon-o-currency-dollar')
                ->schema([
                    Forms\Components\Section::make('Informasi Transaksi')
                        ->schema([
                            Forms\Components\Select::make('period_id')
                                ->label('Periode')
                                ->searchable()
                                ->relationship('transactionPeriod', 'name')
                                ->preload()
                                ->required(),
                            Forms\Components\DatePicker::make('transaction_date')
                                ->label('Tanggal Transaksi')
                                ->native(false)
                                ->required(),
                            Forms\Components\TextInput::make('description')
                                ->label('Keterangan')
                                ->required(),
                            \Pelmered\FilamentMoneyField\Forms\Components\MoneyInput::make('amount')
                                ->label('Jumlah Transaksi')
                                ->minValue(0)
                                ->required(),
                            Forms\Components\TextInput::make('invoice_code')
                                ->label('Nomor Kwitansi')
                                ->required(),
                        ]),
                    Forms\Components\Section::make('Transaksi Kategori & Tipe')
                        ->schema([
                            Forms\Components\Select::make('transaction_category_id')
                                ->label('Kategori Transaksi')
                                ->relationship('transactionCategory', 'name')
                                ->createOptionForm(fn (): array => TransactionCategoryCreateForm::getFormSchema())
                                ->createOptionModalHeading('Buat Kategori Transaksi')
                                ->createOptionUsing(function (array $data): int {
                                    /** @var array<string, mixed> $data */
                                    return CreateOptionUseCase::make()->createTransactionCategory($data);
                                })
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
                                ->createOptionUsing(function (array $data): int {
                                    /** @var array<string, mixed> $data */
                                    return CreateOptionUseCase::make()->createTransactionType($data);
                                })
                                ->createOptionModalHeading('Buat Tipe Transaksi')
                                ->relationship('transactionType', 'name')
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
        ];
    }
}
