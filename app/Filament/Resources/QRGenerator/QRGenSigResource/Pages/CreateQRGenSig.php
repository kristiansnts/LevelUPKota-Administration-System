<?php

namespace App\Filament\Resources\QRGenerator\QRGenSigResource\Pages;

use App\Filament\Resources\QRGenerator\QRGenSigResource;
use App\Models\QRGenerator;
use App\Models\QrGeneratorSigner;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;
use App\Models\Mail;

class CreateQRGenSig extends CreateRecord
{
    protected static string $resource = QRGenSigResource::class;

    protected $createdRecord;

    protected function handleRecordCreation(array $data): QrGeneratorSigner
    {
        // Create QRGenerator first
        $qrGenerator = QRGenerator::create([
            'qr_id' => (string) Str::ulid(),
            'document_id' => $data['document_id'],
        ]);

        $firstRecord = null;

        // Create multiple QrGeneratorSigner records for each signer
        if (isset($data['signers'])) {
            foreach ($data['signers'] as $signer) {
                $record = QrGeneratorSigner::create([
                    'qr_generator_qr_signer_id' => (string) Str::ulid(),
                    'qr_generator_id' => $qrGenerator->qr_id,
                    'qr_signer_id' => $signer['qr_signer_id'],
                    'is_sign' => $signer['is_sign'] ?? false,
                ]);

                // Store the first record for return
                if ($firstRecord === null) {
                    $firstRecord = $record;
                }
            }
        }

        return $firstRecord;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
