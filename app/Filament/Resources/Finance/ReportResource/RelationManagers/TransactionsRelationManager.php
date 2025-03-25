<?php

namespace App\Filament\Resources\Finance\ReportResource\RelationManagers;

use App\Filament\Resources\Finance\TransactionResource\Form\TransactionForm;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use App\Filament\Resources\Finance\TransactionResource\Table\TransactionTable;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Model;
use App\Filament\Resources\Finance\TransactionResource;
use App\Filament\Resources\Finance\ReportResource\Pages\PreviewReport;
use App\Helpers\StringHelper;

class TransactionsRelationManager extends RelationManager
{
    protected static string $relationship = 'transactions';


    protected static ?string $title = 'Transaksi';

    public function form(Form $form): Form
    {
        return $form
            ->schema(TransactionForm::getFormSchema());
    }

    public function table(Table $table): Table
    {
        return TransactionTable::table($table)
            ->recordTitleAttribute('description')
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Catat Transaksi')
                    ->hidden(fn (): bool => $this->getPageClass() === PreviewReport::class)
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['report_id'] = $this->getOwnerRecord()->id;
                        return $data;
                    })
                    ->url(fn (): string => TransactionResource::getUrl('create', ['report_id' => $this->getOwnerRecord()->id])),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make()
                            ->label('Ubah Transaksi')
                            ->icon('heroicon-o-pencil')
                            ->color('warning')
                            ->mutateFormDataUsing(function (array $data): array {
                        $data['report_id'] = $this->getOwnerRecord()->id;
                        return $data;
                    })
                    ->url(fn (Model $record): string => TransactionResource::getUrl('edit', ['record' => $record->id])),
                    Tables\Actions\DeleteAction::make()
                        ->label('Hapus Transaksi')
                        ->icon('heroicon-o-trash')
                        ->color('danger'),
                    Tables\Actions\ViewAction::make('bukti_transaksi')
                        ->label('Bukti Transaksi')
                        ->icon('heroicon-o-eye')
                        ->color('info')
                        ->url(fn (Model $record): string => StringHelper::getTransactionProofLink($record->transaction_proof_link)),
                ])
                ->button()
                ->label('Aksi')
                ->hidden(fn (): bool => $this->getPageClass() === PreviewReport::class),
            ]);
    }
}
