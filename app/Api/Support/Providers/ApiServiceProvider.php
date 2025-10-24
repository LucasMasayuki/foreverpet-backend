<?php

namespace App\Api\Support\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class ApiServiceProvider extends ServiceProvider
{
    const ROUTE_PREFIX = 'api/v1/';

    protected array $modules = [
        'user',
        // Adicione aqui novos módulos conforme necessário
    ];

    public function boot(): void
    {
        $this->registerConfig();
        $this->registerRoutes();
        $this->registerViews();
    }

    public function registerConfig(): void
    {
        foreach ($this->modules as $module) {
            $configPath = app_path("Api/Modules/{$this->moduleNameCamel($module)}/config.php");

            if (file_exists($configPath)) {
                $this->mergeConfigFrom(
                    $configPath,
                    "api.{$this->moduleNameSnakeCase($module)}"
                );
            }
        }
    }

    public function registerRoutes(): void
    {
        // Rotas autenticadas da API
        Route::prefix(self::ROUTE_PREFIX)
            ->name('api.v1.')
            ->middleware([
                'api',
                'throttle:api',
            ])
            ->group(app_path('Api/Http/Routes/api.php'));

        // Rotas não autenticadas da API
        Route::prefix(self::ROUTE_PREFIX)
            ->name('api.v1.public.')
            ->middleware([
                'api',
                'throttle:api',
            ])
            ->group(app_path('Api/Http/Routes/public.php'));
    }

    public function registerViews(): void
    {
        foreach ($this->modules as $module) {
            $viewsPath = app_path("Api/Modules/{$this->moduleNameCamel($module)}/Views");

            if (is_dir($viewsPath)) {
                $this->loadViewsFrom(
                    $viewsPath,
                    "api.{$this->moduleNameSnakeCase($module)}"
                );
            }
        }
    }

    public function moduleNameSnakeCase(string $module): string
    {
        return str_replace('-', '_', strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $module)));
    }

    private function moduleNameCamel(string $module): string
    {
        return str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $module)));
    }
}

