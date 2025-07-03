<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

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
        // Configuration de la locale Carbon pour le français
        Carbon::setLocale(config('app.locale'));
        
        // Forcer HTTPS en production
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }
        
        // S'assurer que asset() utilise le bon URL (important pour les assets Vite/Mix)
        if (config('app.mix_url')) {
            URL::forceRootUrl(config('app.mix_url'));
        }
        
        // Définir la longueur par défaut des chaînes de caractères dans MySQL
        Schema::defaultStringLength(191);
        
        // Configurer la pagination pour utiliser Bootstrap 5
        Paginator::defaultView('pagination::bootstrap-5');
        Paginator::defaultSimpleView('pagination::simple-bootstrap-5');
    }
}
