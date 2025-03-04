<?php

namespace App\Providers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void {}

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        try {
            /**
             * @param  \Illuminate\Filesystem\FilesystemAdapter  $driver
             * @param  array<string, mixed>  $config
             *
             * @return \Illuminate\Filesystem\FilesystemAdapter
             */
            Storage::extend('google', function ($app, array $config): \Illuminate\Filesystem\FilesystemAdapter {
                $options = [];

                if (isset($config['teamDriveId']) && is_string($config['teamDriveId'])) {
                    $options['teamDriveId'] = $config['teamDriveId'];
                }

                if (isset($config['sharedFolderId']) && is_string($config['sharedFolderId'])) {
                    $options['sharedFolderId'] = $config['sharedFolderId'];
                }

                if (! isset($config['clientId']) || ! isset($config['clientSecret']) || ! isset($config['refreshToken'])) {
                    throw new \InvalidArgumentException('Google Drive client configuration is incomplete');
                }

                /** @var string */
                $clientId = $config['clientId'];
                /** @var string */
                $clientSecret = $config['clientSecret'];
                /** @var string */
                $refreshToken = $config['refreshToken'];
                /** @var string */
                $folder = $config['folder'];

                $client = new \Google\Client;
                $client->setClientId($clientId);
                $client->setClientSecret($clientSecret);
                $client->refreshToken($refreshToken);

                $service = new \Google\Service\Drive($client);
                $adapter = new \Masbug\Flysystem\GoogleDriveAdapter(
                    $service,
                    $folder,
                    $options
                );
                $driver = new \League\Flysystem\Filesystem($adapter);

                return new \Illuminate\Filesystem\FilesystemAdapter($driver, $adapter);
            });
        } catch (\Exception) {
            // your exception handling logic
        }
    }
}
