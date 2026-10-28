<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
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
        // Por defecto Laravel dibuja los links de paginación con clases de Tailwind.
        // Como la interfaz usa Bootstrap, le pedimos que use su plantilla de Bootstrap 5.
        Paginator::useBootstrapFive();
    }
}
