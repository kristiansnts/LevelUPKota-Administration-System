<?php

namespace App\Filament\Resources\Mail;

use App\Enums\MailStatusEnum;
use App\Enums\MailTypeEnum;
use App\Filament\Resources\Mail\MailsOutResource\Actions\MailCodeCreateAction;
use App\Filament\Resources\Mail\MailsOutResource\Pages;
use App\Filament\Resources\Mail\MailsResource\Form\MailInfoForm;
use App\Filament\Shared\Services\ResourceScopeService;
use App\Helpers\RouteHelper;
use App\Models\Mail;
use App\Models\MailUser;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class MailsOutResource extends Resource
{
    protected static ?string $model = Mail::class;

    protected static ?string $modelLabel = 'Rekapitulasi Surat Keluar';

    protected static ?string $navigationIcon = 'heroicon-c-envelope';

    protected static ?string $navigationGroup = 'Surat';

    protected static ?string $slug = 'surat-keluar';

    protected static ?string $navigationLabel = 'Rekapitulasi Surat Keluar';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Surat Keluar')
                    ->schema(MailInfoForm::getFormSchema())->columnSpan(2),
                Forms\Components\Section::make('Data Surat Keluar')
                    ->schema([
                        Forms\Components\TextInput::make('mail_code')
                            ->label('Nomor Surat')
                            ->disabled(fn (Get $get): bool => $get('mail_code') === null)
                            ->hintActions([
                                MailCodeCreateAction::make('mailCodeCreateAction'),
                            ])
                            ->live()
                            ->readOnly(),
                        Forms\Components\TextInput::make('link')
                            ->label('Upload Link Surat')
                            ->visible(RouteHelper::isRouteName('filament.admin.resources.surat-keluar.edit')),
                    ])->columnSpan(1),
                Forms\Components\Hidden::make('type')
                    ->default(MailTypeEnum::OUT->value),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('mail_code')
                    ->label('Nomor Surat'),
                Tables\Columns\TextColumn::make('mail_date')
                    ->label('Tanggal Surat'),
                Tables\Columns\TextColumn::make('sender_name')
                    ->label('Pengirim'),
                Tables\Columns\TextColumn::make('receiver_name')
                    ->label('Penerima'),
                Tables\Columns\TextColumn::make('description')
                    ->label('Keterangan'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Dibuat')
                    ->dateTime('d-m-Y H:i:s'),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->colors([
                        'warning' => MailStatusEnum::DRAFT->value,
                        'success' => MailStatusEnum::UPLOADED->value,
                    ])
                    ->formatStateUsing(fn (string $state): string => MailStatusEnum::from($state)->getLabel()),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options(MailStatusEnum::class),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Edit dan Upload'),
                Tables\Actions\ViewAction::make()
                    ->label('Lihat Surat')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->url(fn (Mail $record): string => $record->link ?? '#'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    /**
     * @return Builder<Mail>
     */
    public static function getEloquentQuery(): Builder
    {
        /** @var \Illuminate\Database\Eloquent\Builder<MailUser> $mailIds */
        $mailIds = ResourceScopeService::userScope(
            MailUser::query(),
            'mail_id'
        );

        /** @var \Illuminate\Database\Eloquent\Builder<Mail> */
        return parent::getEloquentQuery()
            ->whereIn('id', $mailIds)
            ->where('type', MailTypeEnum::OUT->value);
    }

    public static function getRelations(): array
    {
        return [

        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMailsOuts::route('/'),
            'create' => Pages\CreateMailsOut::route('/create'),
            'edit' => Pages\EditMailsOut::route('/{record}/edit'),
        ];
    }
}
