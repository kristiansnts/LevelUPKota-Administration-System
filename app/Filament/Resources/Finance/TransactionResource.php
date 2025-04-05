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
            ->actions([
                Tables\Actions\EditAction::make()
                    ->icon('heroicon-o-pencil')
                    ->color('warning'),
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
                Tables\Filters\SelectFilter::make('report_id')
                    ->label('Laporan')
                    ->preload()
                    ->searchable()
                    ->relationship('report', 'title'),
                Tables\Filters\SelectFilter::make('transaction_category_id')
                    ->label('Kategori Transaksi')
                    ->preload()
                    ->searchable()
                    ->relationship('transactionCategory', 'name'),
                Tables\Filters\SelectFilter::make('payment_method_id')
                    ->label('Metode Pembayaran')
                    ->preload()
                    ->searchable()
                    ->relationship('paymentMethod', 'name'),
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
        return parent::getEloquentQuery()
            ->whereIn('id', $transactionIds);
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
