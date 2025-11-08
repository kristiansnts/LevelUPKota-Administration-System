<?php

namespace App\Filament\Resources\Mail\MailsOutResource\Actions;

use App\Models\Mail;
use App\Models\QRGenerator;
use App\Models\QRSigner;
use App\Models\QrGeneratorSigner;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;

class RequestApprovalAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'request_approval';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label('Request Approval')
            ->icon('heroicon-o-clipboard-document-check')
            ->color('info')
            ->visible(fn(Mail $record): bool => !empty($record->file_name))
            ->form([
                Forms\Components\Repeater::make('signers')
                    ->label('Pilih Penanda Tangan')
                    ->schema([
                        Forms\Components\Select::make('qr_signer_id')
                            ->label('Penanda Tangan')
                            ->options(QRSigner::all()->pluck('signer_name', 'qr_signer_id'))
                            ->searchable()
                            ->required()
                            ->distinct(),
                    ])
                    ->minItems(1)
                    ->addActionLabel('Tambah Penanda Tangan')
                    ->defaultItems(1)
                    ->collapsible()
                    ->itemLabel(
                        fn(array $state): ?string =>
                        $state['qr_signer_id']
                        ? QRSigner::find($state['qr_signer_id'])?->signer_name
                        : 'Penanda Tangan Baru'
                    ),
            ])
            ->action(function (Mail $record, array $data): void {
                // Create QR Generator for this mail
                $qrGenerator = QRGenerator::create([
                    'document_id' => $record->id,
                ]);

                // Count total signers
                $totalSigners = count($data['signers']);

                // Create QR Generator Signers with total_sign set to total count
                foreach ($data['signers'] as $signer) {
                    QrGeneratorSigner::create([
                        'qr_generator_id' => $qrGenerator->qr_id,
                        'qr_signer_id' => $signer['qr_signer_id'],
                        'status' => 'draft',
                        'total_sign' => $totalSigners,
                    ]);
                }

                Notification::make()
                    ->title('Request Approval Berhasil')
                    ->success()
                    ->body("QR Code untuk approval telah dibuat dengan {$totalSigners} penanda tangan.")
                    ->send();
            })
            ->modalHeading('Request Approval')
            ->modalDescription('Pilih penanda tangan untuk dokumen ini')
            ->modalSubmitActionLabel('Buat Request')
            ->modalWidth('lg');
    }
}
