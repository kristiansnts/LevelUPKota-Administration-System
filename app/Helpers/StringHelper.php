<?php

namespace App\Helpers;

use App\Filament\Shared\Services\ModelQueryService;
use Illuminate\Support\Facades\Storage;

class StringHelper
{
    public static function setTransactionDirNameByAddress(): string
    {
        $user = ModelQueryService::getUserModel();
        
        return 'bukti_transaksi_' . ($user->district->dis_name ?? $user->city->city_name);
    }

    public static function getTransactionProofLink(string $path): string
    {
        $storage = Storage::disk('google');
        $adapter = $storage->getAdapter();
        $originalUrl = $adapter->getUrl($path);
        $url = str_replace(
            'https://drive.google.com/uc?id=',
            'https://drive.google.com/file/d/',
            $originalUrl
        );
        $url = str_replace('&export=media', '', $url) . '/preview';

        return $url;
    }
}
