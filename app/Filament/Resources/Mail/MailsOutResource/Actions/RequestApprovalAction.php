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
                            ->distinct()
                            ->reactive()
                            ->hintActions([
                                Forms\Components\Actions\Action::make('send_whatsapp')
                                    ->label('Kirim WhatsApp')
                                    ->icon('heroicon-o-chat-bubble-left-right')
                                    ->color('success')
                                    ->visible(fn($state) => !empty($state))
                                    ->action(function ($state, $component) {
                                        $signer = QRSigner::find($state);
                                        if (!$signer || !$signer->phone_number) {
                                            Notification::make()
                                                ->title('Nomor WhatsApp tidak ditemukan')
                                                ->warning()
                                                ->body('Penanda tangan tidak memiliki nomor telepon.')
                                                ->send();
                                            return;
                                        }

                                        // Get the parent action's record (Mail record)
                                        $action = $component->getLivewire()->getMountedTableAction();
                                        $record = $action ? $action->getRecord() : null;

                                        if (!$record) {
                                            Notification::make()
                                                ->title('Error')
                                                ->danger()
                                                ->body('Tidak dapat menemukan data surat.')
                                                ->send();
                                            return;
                                        }

                                        // Get current user's district or city
                                        $user = auth()->user();
                                        $location = '';
                                        if ($user->district_id && $user->district) {
                                            $location = ' ' . $user->district->dis_name;
                                        } elseif ($user->city_id && $user->city) {
                                            $location = ' ' . $user->city->city_name;
                                        }

                                        $description = $record->description ?? 'surat';
                                        $approvalUrl = url('/approval/' . $record->mail_unique);

                                        $message = "Shalom Bapak/Ibu Korwil/PIC/CPIC, kami dari ALK LevelUP{$location} memohon izin meminta tanda tangan untuk persetujuan {$description}. Apabila berkenan bisa klik link dibawah ini terimakasih.\n\n{$approvalUrl}";

                                        $phone = preg_replace('/[^0-9]/', '', $signer->phone_number);
                                        if (substr($phone, 0, 1) === '0') {
                                            $phone = '62' . substr($phone, 1);
                                        }

                                        $whatsappUrl = 'https://wa.me/' . $phone . '?text=' . urlencode($message);

                                        $component->getLivewire()->js("window.open('{$whatsappUrl}', '_blank')");
                                    }),
                            ]),
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

                // Create QR Generator Signers
                foreach ($data['signers'] as $signer) {
                    QrGeneratorSigner::create([
                        'qr_generator_id' => $qrGenerator->qr_id,
                        'qr_signer_id' => $signer['qr_signer_id'],
                        'is_sign' => false,
                    ]);
                }

                Notification::make()
                    ->title('Request Approval Berhasil')
                    ->success()
                    ->body('QR Code untuk approval telah dibuat.')
                    ->send();
            })
            ->modalHeading('Request Approval')
            ->modalDescription('Pilih penanda tangan untuk dokumen ini')
            ->modalSubmitActionLabel('Buat Request')
            ->modalWidth('lg');
    }
}
