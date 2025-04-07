<?php

namespace App\Helpers;

use App\Filament\Shared\Services\ModelQueryService;
use Illuminate\Support\Facades\Storage;

class StringHelper
{
    public static function setTransactionDirNameByAddress(): string
    {
        $user = ModelQueryService::getUserModel();

        return 'BUKTI TRANSAKSI ' . ($user->district->dis_name ?? $user->city->city_name);
    }

    public static function setMailOutDirNameByAddress(): string
    {
        $user = ModelQueryService::getUserModel();

        return 'SURAT KELUAR ' . ($user->district->dis_name ?? $user->city->city_name);
    }

    public static function setMailInDirNameByAddress(): string
    {
        $user = ModelQueryService::getUserModel();

        return 'SURAT MASUK ' . ($user->district->dis_name ?? $user->city->city_name);
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

    public static function getMailLink(string $path): string
    {
        $storage = Storage::disk('google');
        $adapter = $storage->getAdapter();
        $originalUrl = $adapter->getUrl($path);
        
        $isPdf = strtolower(pathinfo($path, PATHINFO_EXTENSION)) === 'pdf';

        if ($isPdf) {
            $url = str_replace(
                'https://drive.google.com/uc?id=',
                'https://drive.google.com/file/d/',
                $originalUrl
            );
            $url = str_replace('&export=media', '', $url) . '/preview';
        } else {
            $url = str_replace(
                'https://drive.google.com/uc?id=',
                'https://docs.google.com/document/d/',
                $originalUrl
            );
            $url = str_replace('&export=media', '', $url) . '/edit';
        }

        return $url;
    }
}
