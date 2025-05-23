<?php

namespace App\Providers;

use Domain\Product\Interfaces\ProductGatewayInterface;
use Illuminate\Support\ServiceProvider;
use Infra\ExternalServices\Gateways\Product\FakeStoreGateway;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ProductGatewayInterface::class, FakeStoreGateway::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
