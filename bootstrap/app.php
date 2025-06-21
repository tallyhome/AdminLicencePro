<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Register middleware aliases
        $middleware->alias([
            'check.licence' => \App\Http\Middleware\CheckLicence::class,
        ]);
        
        // Register the frontend middleware group
        $middleware->group('frontend', [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \App\Http\Middleware\LocaleMiddleware::class,
        ]);
        
        // Register the api middleware group
        $middleware->group('api', [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            \Illuminate\Routing\Middleware\ThrottleRequests::class.':api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);
        
        // Register the licence-api middleware group
        $middleware->group('licence-api', [
            \App\Http\Middleware\ApiRateLimiter::class.':10,1',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->withCommands([
        // Nous utilisons notre propre ServeCommand personnalisé
        // \Illuminate\Foundation\Console\ServeCommand::class,
        \Illuminate\Foundation\Console\OptimizeClearCommand::class,
        \Illuminate\Foundation\Console\OptimizeCommand::class,
        \Illuminate\Foundation\Console\ConfigCacheCommand::class,
        \Illuminate\Foundation\Console\ConfigClearCommand::class,
        \Illuminate\Foundation\Console\PackageDiscoverCommand::class,
        \Illuminate\Foundation\Console\CacheClearCommand::class,
        \Illuminate\Foundation\Console\CacheTableCommand::class,
        \Illuminate\Foundation\Console\VendorPublishCommand::class,
    ])
    ->withProviders([
        // FixLoginRouteServiceProvider désactivé pour éviter les conflits de redirection
        // App\Providers\FixLoginRouteServiceProvider::class,
        \App\Providers\EventServiceProvider::class,
        \App\Providers\RouteServiceProvider::class,
    ])
    ->create();
