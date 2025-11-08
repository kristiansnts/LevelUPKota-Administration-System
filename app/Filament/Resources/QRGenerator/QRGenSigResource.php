<?php

namespace App\Filament\Resources\QRGenerator;

use App\Filament\Resources\QRGenerator\QRGenSigResource\Pages;
use App\Filament\Resources\QRGenerator\QRGenSigResource\RelationManagers;
use App\Models\QrGeneratorSigner;
use App\Models\QRGenerator;
use App\Models\QRSigner;
use App\Models\Mail;
use App\Http\Controllers\QRDocumentController;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use App\Filament\Shared\Services\ResourceScopeService;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Checkbox;
use App\Models\MailUser;
use Filament\Tables\Actions\ActionGroup;
use Filament\Support\Enums\ActionSize;
use Filament\Tables\Actions\Action;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Support\HtmlString;
use Illuminate\Database\Eloquent\Builder;

class QRGenSigResource extends Resource
{
    protected static ?string $model = QrGeneratorSigner::class;

    protected static ?string $modelLabel = 'Buat Tanda Tangan QR';

    protected static ?string $navigationGroup = 'Tanda Tangan';

    protected static ?string $navigationLabel = 'Buat Tanda Tangan QR';

    protected static ?string $navigationIcon = 'heroicon-o-qr-code';

    private static function getPublicUrl($qrGeneratorId)
    {
        return route('qr.document.show', ['qrGeneratorId' => $qrGeneratorId]);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Dokumen')
                    ->schema([
                        Select::make('document_id')
                            ->label('Dokumen')
                            ->options(function () {
                                // Get mail IDs that are scoped to user's city/district
                                $mailIds = ResourceScopeService::userScope(
                                    MailUser::query(),
                                    'mail_id'
                                );
                                
                                return Mail::whereIn('id', $mailIds)
                                    ->get()
                                    ->mapWithKeys(function ($mail) {
                                        return [$mail->id => $mail->mail_code . ' - ' . $mail->description];
                                    });
                            })
                            ->searchable()
                            ->preload()
                            ->required(),
                        Repeater::make('signers')
                            ->label('Penanda Tangan')
                            ->schema([
                                Select::make('qr_signer_id')
                                    ->label('Penanda Tangan')
                                    ->relationship('qrSigner', 'signer_name', function ($query) {
                                        return ResourceScopeService::userScope($query);
                                    })
                                    ->searchable()
                                    ->preload()
                                    ->required(),
                                Select::make('status')
                                    ->label('Status')
                                    ->options([
                                        'draft' => 'Draft',
                                        'approved' => 'Disetujui',
                                        'rejected' => 'Ditolak',
                                    ])
                                    ->default('draft')
                                    ->required()
                            ])
                            ->minItems(1)
                            ->addActionLabel('Tambah Penanda Tangan'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                $user = auth()->user();
                
                // Use a subquery to get the first record for each qr_generator_id
                $query->whereIn('qr_generator_qr_signer_id', function ($subQuery) {
                    $subQuery->selectRaw('MIN(qr_generator_qr_signer_id)')
                        ->from('qr_generator_qr_signer')
                        ->groupBy('qr_generator_id');
                });
                
                // Join with qr_generator, mails, and qr_signer
                $query->join('qr_generator', 'qr_generator_qr_signer.qr_generator_id', '=', 'qr_generator.qr_id')
                    ->join('mails', 'qr_generator.document_id', '=', 'mails.id')
                    ->join('qr_signer', 'qr_generator_qr_signer.qr_signer_id', '=', 'qr_signer.qr_signer_id');
                
                // Try to filter by mails.city_id first (for production)
                $mailsHasCityId = false;
                try {
                    if ($user->city_id && \Schema::hasColumn('mails', 'city_id')) {
                        $query->where('mails.city_id', $user->city_id);
                        $mailsHasCityId = true;
                    }
                    
                    if ($user->district_id && \Schema::hasColumn('mails', 'district_id')) {
                        $query->where('mails.district_id', $user->district_id);
                    }
                } catch (\Exception $e) {
                    // Mails columns don't exist
                }
                
                // Fallback: Filter by qr_signer.city_id (for local)
                if (!$mailsHasCityId && $user->city_id) {
                    $query->where('qr_signer.city_id', $user->city_id);
                }
                
                if (!$mailsHasCityId && $user->district_id) {
                    $query->where('qr_signer.district_id', $user->district_id);
                }
                
                return $query->select('qr_generator_qr_signer.*')
                    ->orderBy('qr_generator_qr_signer.created_at', 'desc');
            })
            ->columns([
                TextColumn::make('qr_generator_id')
                    ->label('QR ID')
                    ->limit(8)
                    ->tooltip(fn ($record) => $record->qr_generator_id),
                TextColumn::make('qrGenerator.document.mail_code')
                    ->label('Kode Surat')
                    ->searchable(),
                TextColumn::make('qrGenerator.document.description')
                    ->label('Deskripsi Dokumen')
                    ->limit(50),
                TextColumn::make('signers_count')
                    ->label('Jumlah Penanda Tangan')
                    ->getStateUsing(function ($record) {
                        return QrGeneratorSigner::where('qr_generator_id', $record->qr_generator_id)->count();
                    }),
                TextColumn::make('signed_count')
                    ->label('Sudah Ditandatangani')
                    ->getStateUsing(function ($record) {
                        return QrGeneratorSigner::where('qr_generator_id', $record->qr_generator_id)
                            ->where('status', 'approved')->count();
                    }),
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\DeleteAction::make()
                    ->action(function ($record) {
                        // Delete all signers for this QR Generator
                        QrGeneratorSigner::where('qr_generator_id', $record->qr_generator_id)->delete();
                        
                        // Also delete the QR Generator record itself
                        QRGenerator::where('qr_id', $record->qr_generator_id)->delete();
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Hapus QR Generator')
                    ->modalDescription('Apakah Anda yakin ingin menghapus QR Generator ini? Semua penanda tangan yang terkait akan ikut terhapus.')
                    ->modalSubmitActionLabel('Ya, Hapus'),
                Tables\Actions\Action::make('view')
                    ->label('Lihat')
                    ->url(fn (QrGeneratorSigner $record): string => self::getPublicUrl($record->qr_generator_id))
                    ->icon('heroicon-o-eye')
                    ->color('success')
                    ->size(ActionSize::Small)
                    ->openUrlInNewTab(),
                Tables\Actions\Action::make('download')
                    ->label('Download QR')
                    ->url(fn (QrGeneratorSigner $record): string => route('qr.code.download', ['qrGeneratorId' => $record->qr_generator_id]))
                    ->icon('heroicon-o-qr-code')
                    ->color('warning')
                    ->size(ActionSize::Small)
                    ->openUrlInNewTab(),
                Tables\Actions\Action::make('preview_qr')
                    ->label('Preview QR')
                    ->icon('heroicon-o-magnifying-glass')
                    ->color('info')
                    ->size(ActionSize::Small)
                    ->modalHeading('QR Code Preview')
                    ->modalDescription(fn (QrGeneratorSigner $record) => 'QR Code untuk dokumen: ' . ($record->qrGenerator->document->mail_code ?? '-'))
                    ->modalContent(function (QrGeneratorSigner $record) {
                        $qrUrl = route('qr.code.generate', ['qrGeneratorId' => $record->qr_generator_id]);
                        $documentUrl = self::getPublicUrl($record->qr_generator_id);
                        
                        return new HtmlString('
                            <div class="text-center space-y-4">
                                <div class="bg-gray-50 p-6 rounded-lg">
                                    <img src="' . $qrUrl . '" alt="QR Code" class="mx-auto max-w-xs w-full" style="max-width: 300px;">
                                </div>
                                <div class="text-sm text-gray-600">
                                    <p><strong>Dokumen:</strong> ' . ($record->qrGenerator->document->mail_code ?? '-') . '</p>
                                    <p><strong>QR Code Link:</strong></p>
                                    <div class="bg-gray-100 p-2 rounded text-xs break-all font-mono">
                                        ' . $documentUrl . '
                                    </div>
                                </div>
                                <div class="flex gap-2 justify-center">
                                    <a href="' . $documentUrl . '" 
                                       target="_blank" 
                                       class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                        </svg>
                                        Test Link
                                    </a>
                                    <a href="' . route('qr.code.download', ['qrGeneratorId' => $record->qr_generator_id]) . '" 
                                       class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        Download
                                    </a>
                                    <a href="' . $qrUrl . '" 
                                       target="_blank" 
                                       class="inline-flex items-center px-4 py-2 bg-purple-600 text-white text-sm font-medium rounded-md hover:bg-purple-700">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        Open QR in Tab
                                    </a>
                                </div>
                            </div>
                        ');
                    })
                    ->modalWidth(MaxWidth::Large)
                    ->modalCancelAction(false)
                    ->modalSubmitAction(false)
                    ->modalCloseButton(true),
            ], position: Tables\Enums\ActionsPosition::BeforeColumns)
            ->actionsColumnLabel('Aksi')
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->action(function ($records) {
                            // Get all unique qr_generator_ids from selected records
                            $qrGeneratorIds = $records->pluck('qr_generator_id')->unique();
                            
                            // Delete all signers for these QR Generators
                            QrGeneratorSigner::whereIn('qr_generator_id', $qrGeneratorIds)->delete();
                            
                            // Also delete the QR Generator records themselves
                            QRGenerator::whereIn('qr_id', $qrGeneratorIds)->delete();
                        })
                        ->requiresConfirmation()
                        ->modalHeading('Hapus QR Generator Terpilih')
                        ->modalDescription('Apakah Anda yakin ingin menghapus QR Generator yang dipilih? Semua penanda tangan yang terkait akan ikut terhapus.')
                        ->modalSubmitActionLabel('Ya, Hapus Semua'),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListQRGenSigs::route('/'),
            'create' => Pages\CreateQRGenSig::route('/create'),
            'edit' => Pages\EditQRGenSig::route('/{record}/edit'),
            // 'view' => Pages\ViewQRGenSig::route('/{record}/view'),
        ];
    }
}
