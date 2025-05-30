<?php

namespace App\Filament\Resources\Finance;

use App\Filament\Resources\Finance\TransactionCategoryResource\Form\TransactionCategoryCreateForm;
use App\Filament\Resources\Finance\TransactionCategoryResource\Pages;
use App\Models\TransactionCategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Filament\Shared\Services\ResourceScopeService;
use App\Models\TransactionCategoryUser;

class TransactionCategoryResource extends Resource
{
    protected static ?string $model = TransactionCategory::class;

    protected static ?string $modelLabel = 'Kategori Transaksi';

    protected static ?string $navigationGroup = 'Keuangan';

    protected static ?string $navigationParentItem = 'Daftar Transaksi';

    protected static ?string $navigationLabel = 'Kategori Transaksi';

    protected static ?string $slug = 'kategori-transaksi';

    protected static ?string $navigationIcon = 'heroicon-o-hashtag';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Kategori Transaksi')
                    ->schema(TransactionCategoryCreateForm::getFormSchema()),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Kategori Transaksi')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')
                    ->label('Detail')
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([

            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->before(function (TransactionCategory $record) {
                        if ($record->transactions()->exists()) {
                            \Filament\Notifications\Notification::make()
                                ->title('Tidak dapat menghapus kategori')
                                ->body('Kategori transaksi ini masih digunakan oleh beberapa transaksi. Hapus atau ubah kategori transaksi tersebut terlebih dahulu.')
                                ->danger()
                                ->send();
                            
                            return false;
                        }
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->before(function ($records) {
                            foreach ($records as $record) {
                                if ($record->transactions()->exists()) {
                                    \Filament\Notifications\Notification::make()
                                        ->title('Tidak dapat menghapus kategori')
                                        ->body('Satu atau lebih kategori transaksi masih digunakan oleh transaksi. Hapus atau ubah kategori transaksi tersebut terlebih dahulu.')
                                        ->danger()
                                        ->send();
                                    
                                    return false;
                                }
                            }
                        }),
                ]),
            ]);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder<TransactionCategory>
     */
    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        /** @var \Illuminate\Database\Eloquent\Builder<TransactionCategory> $transactionCategoryIds */
        $transactionCategoryIds = ResourceScopeService::userScope(
            TransactionCategoryUser::query(),
            'transaction_category_id'
        );

        /** @var \Illuminate\Database\Eloquent\Builder<TransactionCategory> */
        return parent::getEloquentQuery()
            ->whereIn('id', $transactionCategoryIds);
    }

    public static function getRelations(): array
    {
        return [

        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransactionCategories::route('/'),
            'create' => Pages\CreateTransactionCategory::route('/create'),
            'edit' => Pages\EditTransactionCategory::route('/{record}/edit'),
        ];
    }
}
