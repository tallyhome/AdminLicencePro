<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Facades\Log;

class LoginRedirectServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Remplacer la classe UrlGenerator par notre propre implémentation
        $this->app->extend('url', function ($urlGenerator, $app) {
            $routes = $app['router']->getRoutes();
            
            // Créer une classe anonyme qui étend UrlGenerator
            return new class($routes, $app->make('request')) extends UrlGenerator {
                public function route($name, $parameters = [], $absolute = true)
                {
                    // Rediriger les routes spécifiques vers leurs équivalents admin
                    if ($name === 'login') {
                        // Utiliser la route admin.login.form au lieu de générer directement l'URL
                        return parent::route('admin.login.form', $parameters, $absolute);
                    }
                    
                    // Pour toutes les autres routes, utiliser le comportement normal
                    try {
                        return parent::route($name, $parameters, $absolute);
                    } catch (\Exception $e) {
                        Log::warning('Route not found: ' . $name);
                        return url('/admin');
                    }
                }
            };
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Nous ne définissons plus de route ici pour éviter les conflits
        // La redirection est gérée par la méthode route() ci-dessus
    }
}