<?php

namespace App\Filament\Resources\Finance\TransactionResource\Form;

use App\Filament\Resources\Finance\PaymentMethodResource\Form\PaymentMethodCreateForm;
use App\Filament\Resources\Finance\TransactionCategoryResource\Form\TransactionCategoryCreateForm;
use App\Filament\Resources\Finance\TransactionResource\Shared\UseCases\CreateOptionUseCase;
use Filament\Forms;
use Filament\Forms\Form;
use App\Helpers\StringHelper;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Placeholder;
use App\Filament\Resources\Finance\TransactionResource\Pages\EditTransaction;
use App\Filament\Shared\Actions\DeleteGoogleFileAction;
use App\Filament\Shared\Services\ModelQueryService;

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
                            Forms\Components\DatePicker::make('transaction_date')
                                ->label('Tanggal Transaksi')
                                ->native(false)
                                ->displayFormat('l, d F Y')
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
                                ->options(fn (): array => ModelQueryService::getTransactionCategoryOptionsWithHtml())
                                ->allowHtml()
                                ->createOptionForm(fn (): array => TransactionCategoryCreateForm::getFormSchema())
                                ->createOptionModalHeading('Buat Kategori Transaksi')
                                ->createOptionUsing(function (array $data): int {
                                    /** @var array<string, mixed> $data */
                                    /** @var \App\Filament\Resources\Finance\TransactionResource\Shared\UseCases\CreateOptionUseCase $useCase */
                                    $useCase = app(CreateOptionUseCase::class);

                                    return $useCase->createTransactionCategory($data);
                                })
                                ->searchable()
                                ->preload()
                                ->required(),
                            Forms\Components\Select::make('payment_method_id')
                                ->label('Metode Pembayaran')
                                ->createOptionForm(fn (): array => PaymentMethodCreateForm::getFormSchema())
                                ->createOptionUsing(function (array $data): int {
                                    /** @var array<string, mixed> $data */
                                    /** @var \App\Filament\Resources\Finance\TransactionResource\Shared\UseCases\CreateOptionUseCase $useCase */
                                    $useCase = app(CreateOptionUseCase::class);

                                    return $useCase->createPaymentMethod($data);
                                })
                                ->createOptionModalHeading('Buat Metode Pembayaran')
                                ->options(fn (): array => ModelQueryService::getPaymentMethodOptions())
                                ->searchable()
                                ->preload()
                                ->required(),
                        ]),
                ])->columnSpan(2),
            Forms\Components\Section::make('Bukti Transaksi')
                ->description('Bukti transaksi berupa gambar')
                ->icon('heroicon-o-document-text')
                ->schema([
                    Placeholder::make('transaction_proof_link')
                        ->label('Preview Bukti Transaksi')
                        ->content(function ($record) {
                            /** @var \App\Models\Transaction $record */
                            if (!$record?->transaction_proof_link) return null;
                            
                            return new HtmlString(
                                '<iframe src="' . StringHelper::getTransactionProofLink($record->transaction_proof_link) . 
                                '" width="250" height="250" allow="autoplay"></iframe>'
                            );
                        })
                        ->visible(fn ($livewire): bool => $livewire instanceof EditTransaction && !empty($livewire->record->transaction_proof_link)),
                    Forms\Components\FileUpload::make('transaction_proof_link')
                        ->label('Upload Bukti Transaksi')
                        ->disk('google')
                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/gif', 'application/pdf', 'image/jpg'])
                        ->directory(function () {
                            return StringHelper::setTransactionDirNameByAddress();
                        })
                        ->hintActions([
                            DeleteGoogleFileAction::make('delete_file')
                                ->fileColumn('transaction_proof_link')
                        ])
                        ->getUploadedFileUsing(fn () => null)
                        ->imageEditor()
                        ->imageEditorAspectRatios([
                            '16:9',
                            '4:3',
                        ])
                ])->columnSpan(1),
        ];
    }
}
