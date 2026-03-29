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
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Paksa HTTPS jika diakses lewat proxy/aaPanel yang memiliki header HTTPS
        // if (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] === 'on' || $_SERVER['HTTPS'] === 1) 
        //     || isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
        //     \Illuminate\Support\Facades\URL::forceScheme('https');
        // }

        // // Paksa Root URL sesuai config APP_URL (penting untuk validasi Signature)
        // if (!app()->runningInConsole()) {
        //     \Illuminate\Support\Facades\URL::forceRootUrl(config('app.url'));
        // }
    }
}
