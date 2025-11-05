<?php

namespace App\Filament\Resources\Finance;

use App\Filament\Resources\Finance\TransactionResource\Form\TransactionForm;
use App\Filament\Resources\Finance\TransactionResource\Pages;
use App\Filament\Resources\Finance\TransactionResource\Table\TransactionTable;
use App\Models\Transaction;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms;
use Filament\Tables\Actions\Action;
use App\Models\TransactionUser;
use App\Filament\Shared\Services\ResourceScopeService;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Support\HtmlString;
use App\Helpers\StringHelper;
use App\Filament\Shared\Services\ModelQueryService;
use App\Filament\Resources\Finance\TransactionResource\Shared\Services\TransactionService;
use App\Filament\Resources\Finance\TransactionResource\Shared\Services\CreatePivotService;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $modelLabel = 'Transaksi';

    protected static ?string $navigationGroup = 'Keuangan';

    protected static ?string $navigationLabel = 'Daftar Transaksi';

    protected static ?string $slug = 'transaksi';

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    public static function form(Form $form): Form
    {
        return $form->schema([
            ...TransactionForm::getFormSchema(),
            Forms\Components\Section::make('Laporan')
                ->schema([
                    Forms\Components\Select::make('report_id')
                    ->label('Laporan')
                    ->default(request()->query('report_id'))
                    ->preload()
                    ->searchable()
                    ->relationship('report', 'title'),
                ])->columnSpan(2),
        ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return TransactionTable::table($table)
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Catat Transaksi')
                    ->after(function (Transaction $record): void {
                        $transactionId = $record->getKey();
                        CreatePivotService::make()->createTransactionUserPivot($transactionId);
                        (new TransactionService())->createTransaction($record, $transactionId);
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->icon('heroicon-o-pencil')
                    ->color('warning')
                    ->after(function (Transaction $record): void {
                        $transactionId = $record->getKey();
                        (new TransactionService())->updateTransaction($record, $transactionId);
                    }),
                Tables\Actions\ViewAction::make('transaction_proof_link')
                    ->label('Bukti Transaksi')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->form([])
                    ->visible(function (?Transaction $record): bool {
                        return $record && !empty($record->transaction_proof_link);
                    })
                    ->modalWidth(MaxWidth::Small)
                    ->modalHeading('Bukti Transaksi')
                    ->modalContent(fn ($record) => new HtmlString('
                        <iframe 
                            src="'.StringHelper::getTransactionProofLink($record->transaction_proof_link).'" 
                            style="width: 100%; height: 250px; border: none;"
                            title="Bukti Transaksi"
                        ></iframe>
                    ')),
            ], position: Tables\Enums\ActionsPosition::BeforeColumns)
            ->actionsColumnLabel('Aksi')
            ->filters([
                Tables\Filters\SelectFilter::make('transaction_category_id')
                    ->label('Kategori Transaksi')
                    ->preload()
                    ->searchable()
                    ->options(ModelQueryService::getTransactionCategoryOptions()),
                Tables\Filters\SelectFilter::make('payment_method_id')
                    ->label('Metode Pembayaran')
                    ->preload()
                    ->searchable()
                    ->options(ModelQueryService::getPaymentMethodOptions()),
            ])
            ->filtersTriggerAction(
                fn (Action $action): Action => $action
                    ->button()
                    ->label('Filter'),
            );
    }

    public static function getRelations(): array
    {
        return [

        ];
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder<Transaction>
     */
    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        /** @var \Illuminate\Database\Eloquent\Builder<Transaction> $transactionIds */
        $transactionIds = ResourceScopeService::userScope(
            TransactionUser::query(),
            'transaction_id'
        );

        /** @var \Illuminate\Database\Eloquent\Builder<Transaction> */
        $query = parent::getEloquentQuery()
            ->whereIn('id', $transactionIds);
            
        // Only filter out transactions with report_id on the index page
        // Allow viewing/editing transactions with report_id when accessed directly
        if (!request()->route('record')) {
            $query->whereNull('report_id');
        }
        
        return $query;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransactions::route('/'),
        ];
    }
}
