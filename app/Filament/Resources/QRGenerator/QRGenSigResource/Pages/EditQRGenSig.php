<?php

namespace App\Filament\Resources\QRGenerator\QRGenSigResource\Pages;

use App\Filament\Resources\QRGenerator\QRGenSigResource;
use App\Models\QrGeneratorSigner;
use App\Models\QRGenerator;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Str;

class EditQRGenSig extends EditRecord
{
    protected static string $resource = QRGenSigResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Get the QR Generator info
        $qrGenerator = $this->record->qrGenerator;
        
        // Get all signers for this QR Generator
        $allSigners = QrGeneratorSigner::where('qr_generator_id', $this->record->qr_generator_id)->get();
        
        // Transform data for the form
        $data['document_id'] = $qrGenerator->document_id;
        $data['signers'] = $allSigners->map(function ($signer) {
            return [
                'qr_signer_id' => $signer->qr_signer_id,
                'status' => $signer->status,
            ];
        })->toArray();

        return $data;
    }

    protected function handleRecordUpdate($record, array $data): QrGeneratorSigner
    {
        // Update the QR Generator document if changed
        $qrGenerator = $record->qrGenerator;
        $qrGenerator->update([
            'document_id' => $data['document_id']
        ]);

        // Delete existing signer records for this QR Generator
        QrGeneratorSigner::where('qr_generator_id', $record->qr_generator_id)->delete();

        $firstRecord = null;

        // Create new signer records
        if (isset($data['signers'])) {
            foreach ($data['signers'] as $signer) {
                $newRecord = QrGeneratorSigner::create([
                    'qr_generator_qr_signer_id' => (string) Str::ulid(),
                    'qr_generator_id' => $record->qr_generator_id,
                    'qr_signer_id' => $signer['qr_signer_id'],
                    'status' => $signer['status'] ?? 'draft',
                    'total_sign' => count($data['signers']),
                ]);

                if ($firstRecord === null) {
                    $firstRecord = $newRecord;
                }
            }
        }

        return $firstRecord ?? $record;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
