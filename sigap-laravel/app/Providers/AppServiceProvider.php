<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    // ======================================================
    // AppServiceProvider
    // Tempat mendaftarkan binding service container dan bootstrap
    // logika aplikasi yang diperlukan pada setiap request.
    // Saat ini kosong — gunakan metode `register()` dan `boot()`
    // untuk mengikat service atau menambahkan macro/view composer.
    // ======================================================
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
        //
    }
}
