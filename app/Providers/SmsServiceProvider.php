<?php

namespace App\Providers;

use App\Api\Support\Contracts\SmsServiceInterface;
use App\Api\Support\Services\LogSmsService;
use App\Api\Support\Services\TwilioSmsService;
use Illuminate\Support\ServiceProvider;

class SmsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(SmsServiceInterface::class, function ($app) {
            $driver = config('services.sms.driver', 'log');

            return match ($driver) {
                'twilio' => new TwilioSmsService(),
                'log' => new LogSmsService(),
                default => new LogSmsService(),
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


