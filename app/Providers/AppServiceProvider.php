<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app['config']->set('curl.certificate', storage_path('certificates/ca-bundle.crt'));

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
