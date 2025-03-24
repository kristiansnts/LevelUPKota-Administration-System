<?php

namespace App\Filament\Resources\Finance;

use App\Filament\Resources\Finance\ReportResource\Pages;
use App\Filament\Resources\Finance\ReportResource\RelationManagers;
use App\Models\Report;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Enums\ReportStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Database\Eloquent\Model;

class ReportResource extends Resource
{
    protected static ?string $model = Report::class;

    protected static ?string $modelLabel = 'Laporan Keuangan';

    protected static ?string $navigationGroup = 'Keuangan';

    protected static ?string $navigationLabel = 'Laporan Keuangan';

    protected static ?string $slug = 'laporan-keuangan';

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Laporan Keuangan')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Judul')
                            ->required(),
                        Forms\Components\TextInput::make('period')
                            ->label('Periode')
                            ->required(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Judul'),
                Tables\Columns\TextColumn::make('period')
                    ->label('Periode'),
                Tables\Columns\TextColumn::make('is_done')
                    ->label('Status')
                    ->getStateUsing(function (Report $record): string {
                        return ReportStatus::from(
                            $record->is_done ? 'true' : 'false'
                        )->getLabel();
                    })
                    ->badge()
                    ->color(fn (Report $record): string => match (ReportStatus::from($record->is_done ? 'true' : 'false')) {
                        ReportStatus::DRAFT => 'warning',
                        ReportStatus::SUBMITTED => 'success',
                        default => 'gray',
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('is_done')
                    ->label('Status')
                    ->options(ReportStatus::class),
                Tables\Filters\SelectFilter::make('period')
                    ->label('Periode')
                    ->preload()
                    ->searchable()
                    ->options(fn () => Report::query()
                        ->distinct()
                        ->pluck('period', 'period')
                        ->all()
                    ),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Ubah Laporan')
                    ->visible(fn (Report $record): bool => ReportStatus::from($record->is_done ? 'true' : 'false') === ReportStatus::DRAFT),
                Tables\Actions\Action::make('preview')
                    ->label('Lihat Laporan')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->url(fn (Report $record): string => ReportResource::getUrl('preview', ['record' => $record->id])),
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
            RelationManagers\TransactionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReports::route('/'),
            'create' => Pages\CreateReport::route('/create'),
            'edit' => Pages\EditReport::route('/{record}/edit'),
            'preview' => Pages\PreviewReport::route('/{record}/preview'),
        ];
    }
}
