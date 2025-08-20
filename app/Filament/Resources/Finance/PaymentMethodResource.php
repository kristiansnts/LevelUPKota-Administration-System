<?php

namespace App\Filament\Resources\Finance;

use App\Filament\Resources\Finance\PaymentMethodResource\Form\PaymentMethodCreateForm;
use App\Filament\Resources\Finance\PaymentMethodResource\Pages;
use App\Models\PaymentMethod;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Filament\Shared\Services\ResourceScopeService;
use App\Models\PaymentMethodUser;

class PaymentMethodResource extends Resource
{
    protected static ?string $model = PaymentMethod::class;

    protected static ?string $modelLabel = 'Metode Pembayaran';

    protected static ?string $navigationGroup = 'Keuangan';

    protected static ?string $navigationParentItem = 'Daftar Transaksi';

    protected static ?string $navigationLabel = 'Metode Pembayaran';

    protected static ?string $slug = 'metode-pembayaran';

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Metode Pembayaran')
                    ->schema(PaymentMethodCreateForm::getFormSchema()),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Metode Pembayaran')
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
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->before(function ($records) {
                            foreach ($records as $record) {
                                $record->transactions()->delete();
                            }
                        }),
                ]),
            ]);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder<PaymentMethod>
     */
    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        /** @var \Illuminate\Database\Eloquent\Builder<PaymentMethod> $paymentMethodIds */
        $paymentMethodIds = ResourceScopeService::userScope(
            PaymentMethodUser::query(),
            'payment_method_id'
        );

        /** @var \Illuminate\Database\Eloquent\Builder<PaymentMethod> */
        return parent::getEloquentQuery()
            ->whereIn('id', $paymentMethodIds);
    }

    public static function getRelations(): array
    {
        return [

        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPaymentMethods::route('/'),
            'create' => Pages\CreatePaymentMethod::route('/create'),
            'edit' => Pages\EditPaymentMethod::route('/{record}/edit'),
        ];
    }
}
