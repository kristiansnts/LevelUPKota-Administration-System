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
use Illuminate\Support\HtmlString;
use Filament\Support\Enums\MaxWidth;

class TransactionsRelationManager extends RelationManager
{
    protected static string $relationship = 'transactions';


    protected static ?string $title = 'Transaksi';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                ...TransactionForm::getFormSchema(),
                Forms\Components\Hidden::make('report_id')
                    ->default(request()->query('report_id')),
            ]);
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
                Tables\Actions\EditAction::make()
                        ->label('Ubah Transaksi')
                        ->icon('heroicon-o-pencil')
                        ->color('warning')
                        ->hidden(fn (): bool => $this->getPageClass() === PreviewReport::class)
                        ->mutateFormDataUsing(function (array $data): array {
                            $data['report_id'] = $this->getOwnerRecord()->id;
                            return $data;
                        })
                        ->url(fn (Model $record): string => TransactionResource::getUrl('edit', ['record' => $record->id])),
                    Tables\Actions\ViewAction::make('bukti_transaksi')
                        ->label('Bukti Transaksi')
                        ->icon('heroicon-o-eye')
                        ->color('info')
                        ->hidden(fn (): bool => $this->getPageClass() === PreviewReport::class)
                        ->form([])
                        ->modalWidth(MaxWidth::Small)
                        ->modalHeading('Bukti Transaksi')
                        ->modalContent(fn ($record) => new HtmlString('
                            <iframe 
                                src="'.StringHelper::getTransactionProofLink($record->transaction_proof_link).'" 
                                style="width: 100%; height: 250px; border: none;"
                                title="Bukti Transaksi"
                            ></iframe>
                        ')),
            ]);
    }
}
