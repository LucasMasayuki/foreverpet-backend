<?php

namespace App\Providers;

use App\Api\Support\Contracts\StorageServiceInterface;
use App\Api\Support\Services\LocalStorageService;
use App\Api\Support\Services\S3StorageService;
use Illuminate\Support\ServiceProvider;

class StorageServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(StorageServiceInterface::class, function ($app) {
            $driver = config('services.storage.driver', 'local');

            return match ($driver) {
                's3' => new S3StorageService(),
                'local' => new LocalStorageService(),
                default => new LocalStorageService(),
            };
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}

