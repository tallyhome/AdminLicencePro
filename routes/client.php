<?php

use App\Http\Controllers\Auth\ClientRegistrationController;
use App\Http\Controllers\Auth\ClientAuthController;
use App\Http\Controllers\Client\DashboardController;
use App\Http\Controllers\Client\ProjectController;
use App\Http\Controllers\Client\LicenseController;
use App\Http\Controllers\Client\AnalyticsController;
use App\Http\Controllers\Client\SupportController;
use App\Http\Controllers\Client\SettingsController;
use App\Http\Controllers\Client\ApiKeyController;
use App\Http\Middleware\ClientAuthenticate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
/*
|--------------------------------------------------------------------------
| Client Routes
|--------------------------------------------------------------------------
|
| Routes pour les clients (utilisateurs finaux du SaaS)
|
*/

// Routes publiques pour les clients (pas besoin de middleware web car déjà appliqué dans RouteServiceProvider)
Route::name('client.')->group(function () {
    
    // Routes d'inscription
    Route::get('/register', [ClientRegistrationController::class, 'showRegistrationForm'])
        ->name('register.form');
    Route::post('/register', [ClientRegistrationController::class, 'register'])
        ->name('register');
    Route::post('/check-domain', [ClientRegistrationController::class, 'checkDomainAvailability'])
        ->name('check-domain');

    // Routes de connexion
    Route::get('/login', [ClientAuthController::class, 'showLoginForm'])
        ->name('login.form');
    Route::post('/login', [ClientAuthController::class, 'login'])
        ->name('login');
    Route::post('/logout', [ClientAuthController::class, 'logout'])
        ->name('logout');

    // Routes de récupération de mot de passe
    Route::get('/forgot-password', [ClientAuthController::class, 'showForgotPasswordForm'])
        ->name('forgot-password.form');
    Route::post('/forgot-password', [ClientAuthController::class, 'sendResetLinkEmail'])
        ->name('forgot-password');

    // Routes de test temporaires (sans authentification)
    Route::get('/test-faq', [SupportController::class, 'faq'])->name('test.faq');
    Route::get('/test-documentation', [SupportController::class, 'documentation'])->name('test.documentation');

    // Pages d'aide (temporairement sans authentification pour debug)
    Route::get('/support/faq', [SupportController::class, 'faq'])->name('support.faq');
    Route::get('/support/documentation', [SupportController::class, 'documentation'])->name('support.documentation');

    // Routes protégées par authentification client
    Route::middleware([ClientAuthenticate::class])->group(function () {
        
        // Dashboard principal
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');
        Route::get('/dashboard/chart-data', [DashboardController::class, 'chartData'])
            ->name('dashboard.chart-data');
        
        // Route de test pour déboguer le dashboard
        Route::get('/test-dashboard', function() {
            $client = Auth::guard('client')->user();
            $tenant = $client->tenant;
            
            if (!$tenant) {
                return response()->json(['error' => 'Aucun tenant']);
            }
            
            $subscription = $tenant->subscriptions()->with('plan')->latest()->first();
            $plan = $subscription ? $subscription->plan : null;
            
            $projectCount = $tenant->projects()->count();
            $licenseCount = $tenant->serialKeys()->count();
            
            return response()->json([
                'client' => $client->name,
                'tenant' => $tenant->name,
                'plan' => $plan ? $plan->name : 'Aucun',
                'projects' => $projectCount,
                'licenses' => $licenseCount,
                'project_limit' => $plan ? $plan->max_projects : 'N/A',
                'license_limit' => $plan ? $plan->max_licenses : 'N/A'
            ]);
        })->name('test-dashboard');

        // Dashboard simple pour test
        Route::get('/simple-dashboard', function() {
            $client = Auth::guard('client')->user();
            $tenant = $client->tenant;
            
            if (!$tenant) {
                return view('client.dashboard-simple', [
                    'error' => 'Aucun tenant associé',
                    'usageStats' => [
                        'projects' => ['count' => 0, 'limit' => 0, 'percentage' => 0, 'status' => 'success', 'text' => '0 sur 0 projets'],
                        'licenses' => ['count' => 0, 'limit' => 0, 'percentage' => 0, 'status' => 'success', 'text' => '0 sur 0 licences'],
                        'active_licenses' => ['count' => 0, 'text' => 'Licences actives'],
                        'total_activations' => ['count' => 0, 'text' => 'Activations totales']
                    ]
                ]);
            }
            
            $subscription = $tenant->subscriptions()->with('plan')->latest()->first();
            $plan = $subscription ? $subscription->plan : null;
            
            $projectCount = $tenant->projects()->count();
            $licenseCount = $tenant->serialKeys()->count();
            $activeLicenseCount = $tenant->serialKeys()->where('serial_keys.status', 'active')->count();
            $totalActivations = $tenant->serialKeys()->sum('current_activations');
            
            $projectLimit = $plan ? ($plan->max_projects === null ? 'Illimité' : $plan->max_projects) : 2;
            $licenseLimit = $plan ? ($plan->max_licenses === null ? 'Illimité' : $plan->max_licenses) : 10;
            
            $projectPercentage = ($projectLimit !== 'Illimité' && $projectLimit > 0) ? round(($projectCount / $projectLimit) * 100) : 0;
            $licensePercentage = ($licenseLimit !== 'Illimité' && $licenseLimit > 0) ? round(($licenseCount / $licenseLimit) * 100) : 0;
            
            $usageStats = [
                'projects' => [
                    'count' => $projectCount,
                    'limit' => $projectLimit,
                    'percentage' => $projectPercentage,
                    'status' => $projectPercentage < 80 ? 'success' : ($projectPercentage < 90 ? 'warning' : 'danger'),
                    'text' => $projectLimit === 'Illimité' ? 'Projets illimités' : $projectCount . ' sur ' . $projectLimit . ' projets'
                ],
                'licenses' => [
                    'count' => $licenseCount,
                    'limit' => $licenseLimit,
                    'percentage' => $licensePercentage,
                    'status' => $licensePercentage < 80 ? 'success' : ($licensePercentage < 90 ? 'warning' : 'danger'),
                    'text' => $licenseLimit === 'Illimité' ? 'Licences illimitées' : $licenseCount . ' sur ' . $licenseLimit . ' licences'
                ],
                'active_licenses' => [
                    'count' => $activeLicenseCount,
                    'text' => 'Licences actives'
                ],
                'total_activations' => [
                    'count' => $totalActivations,
                    'text' => 'Activations totales'
                ]
            ];
            
            return view('client.dashboard', [
                'client' => $client,
                'tenant' => $tenant,
                'subscription' => $subscription,
                'usageStats' => $usageStats,
                'notifications' => [],
                'chartsData' => ['labels' => [], 'data' => [], 'datasets' => []],
                'recentActivity' => [],
                'recentProjects' => collect(),
                'recentLicenses' => collect()
            ]);
        })->name('simple-dashboard');

        // Routes des projets
        Route::prefix('projects')->name('projects.')->group(function () {
            Route::get('/', [ProjectController::class, 'index'])->name('index');
            Route::get('/create', [ProjectController::class, 'create'])->name('create');
            Route::post('/', [ProjectController::class, 'store'])->name('store');
            Route::get('/{project}', [ProjectController::class, 'show'])->name('show');
            Route::get('/{project}/edit', [ProjectController::class, 'edit'])->name('edit');
            Route::put('/{project}', [ProjectController::class, 'update'])->name('update');
            Route::delete('/{project}', [ProjectController::class, 'destroy'])->name('destroy');
            Route::post('/{project}/toggle-status', [ProjectController::class, 'toggleStatus'])->name('toggle-status');
        });

        // Routes des licences
        Route::prefix('licenses')->name('licenses.')->group(function () {
            Route::get('/', [LicenseController::class, 'index'])->name('index');
            Route::get('/create', [LicenseController::class, 'create'])->name('create');
            Route::post('/', [LicenseController::class, 'store'])->name('store');
            Route::get('/{license}', [LicenseController::class, 'show'])->name('show');
            Route::get('/{license}/edit', [LicenseController::class, 'edit'])->name('edit');
            Route::put('/{license}', [LicenseController::class, 'update'])->name('update');
            Route::delete('/{license}', [LicenseController::class, 'destroy'])->name('destroy');
            Route::post('/{license}/toggle-status', [LicenseController::class, 'toggleStatus'])->name('toggle-status');
            Route::post('/{license}/regenerate', [LicenseController::class, 'regenerate'])->name('regenerate');
            Route::get('/{license}/download', [LicenseController::class, 'download'])->name('download');
        });

        // Routes des analytics
        Route::prefix('analytics')->name('analytics.')->group(function () {
            Route::get('/', [AnalyticsController::class, 'index'])->name('index');
            Route::get('/data', [AnalyticsController::class, 'getData'])->name('data');
            Route::get('/chart-data', [AnalyticsController::class, 'getChartData'])->name('chart-data');
            Route::get('/export', [AnalyticsController::class, 'export'])->name('export');
        });

        // Routes des clés API
        Route::prefix('api-keys')->name('api-keys.')->group(function () {
            Route::get('/', [ApiKeyController::class, 'index'])->name('index');
            Route::get('/create', [ApiKeyController::class, 'create'])->name('create');
            Route::post('/', [ApiKeyController::class, 'store'])->name('store');
            Route::get('/{apiKey}', [ApiKeyController::class, 'show'])->name('show');
            Route::get('/{apiKey}/edit', [ApiKeyController::class, 'edit'])->name('edit');
            Route::put('/{apiKey}', [ApiKeyController::class, 'update'])->name('update');
            Route::delete('/{apiKey}', [ApiKeyController::class, 'destroy'])->name('destroy');
            Route::post('/{apiKey}/regenerate', [ApiKeyController::class, 'regenerate'])->name('regenerate');
            Route::post('/{apiKey}/toggle', [ApiKeyController::class, 'toggle'])->name('toggle');
            Route::get('/export', [ApiKeyController::class, 'export'])->name('export');
        });

        // Routes du support
        Route::prefix('support')->name('support.')->group(function () {
            Route::get('/', [SupportController::class, 'index'])->name('index');
            Route::get('/create', [SupportController::class, 'create'])->name('create');
            Route::post('/', [SupportController::class, 'store'])->name('store');
            Route::get('/{ticket}', [SupportController::class, 'show'])->name('show');
            Route::post('/{ticket}/reply', [SupportController::class, 'reply'])->name('reply');
            Route::post('/{ticket}/close', [SupportController::class, 'close'])->name('close');
            Route::post('/{ticket}/reopen', [SupportController::class, 'reopen'])->name('reopen');
            Route::get('/{ticket}/reply/{reply}/attachment/{index}', [SupportController::class, 'downloadAttachment'])->name('download-attachment');
        });

        // Routes des paramètres
        Route::prefix('settings')->name('settings.')->group(function () {
            Route::get('/', [SettingsController::class, 'index'])->name('index');
            Route::put('/profile', [SettingsController::class, 'updateProfile'])->name('update-profile');
            Route::put('/password', [SettingsController::class, 'updatePassword'])->name('update-password');
            Route::put('/company', [SettingsController::class, 'updateCompany'])->name('update-company');
            Route::post('/avatar', [SettingsController::class, 'uploadAvatar'])->name('upload-avatar');
            Route::delete('/account', [SettingsController::class, 'deleteAccount'])->name('delete-account');
        });

        // Routes de facturation
        Route::prefix('billing')->name('billing.')->group(function () {
            Route::get('/', [SettingsController::class, 'billing'])->name('index');
            Route::get('/invoices', [SettingsController::class, 'invoices'])->name('invoices');
            Route::get('/invoices/{invoice}', [SettingsController::class, 'downloadInvoice'])->name('download-invoice');
            Route::get('/subscription', [SettingsController::class, 'subscription'])->name('subscription');
            Route::post('/subscription/upgrade', [SettingsController::class, 'upgradeSubscription'])->name('upgrade');
            Route::post('/subscription/cancel', [SettingsController::class, 'cancelSubscription'])->name('cancel');
        });

        // Routes des méthodes de paiement
        Route::prefix('payment-methods')->name('payment-methods.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Client\PaymentMethodController::class, 'index'])->name('index');
            Route::get('/create', [\App\Http\Controllers\Client\PaymentMethodController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\Client\PaymentMethodController::class, 'store'])->name('store');
            Route::get('/{paymentMethod}', [\App\Http\Controllers\Client\PaymentMethodController::class, 'show'])->name('show');
            Route::get('/{paymentMethod}/edit', [\App\Http\Controllers\Client\PaymentMethodController::class, 'edit'])->name('edit');
            Route::put('/{paymentMethod}', [\App\Http\Controllers\Client\PaymentMethodController::class, 'update'])->name('update');
            Route::delete('/{paymentMethod}', [\App\Http\Controllers\Client\PaymentMethodController::class, 'destroy'])->name('destroy');
            Route::post('/{paymentMethod}/set-default', [\App\Http\Controllers\Client\PaymentMethodController::class, 'setDefault'])->name('set-default');
            Route::post('/stripe/setup', [\App\Http\Controllers\Client\PaymentMethodController::class, 'stripeSetup'])->name('stripe.setup');
            Route::post('/paypal/setup', [\App\Http\Controllers\Client\PaymentMethodController::class, 'paypalSetup'])->name('paypal.setup');
        });

        // Routes de subscription/plans
        Route::prefix('subscription')->name('subscription.')->group(function () {
            Route::get('/plans', [\App\Http\Controllers\SubscriptionController::class, 'plans'])->name('plans');
            Route::get('/checkout/{planId}', [\App\Http\Controllers\SubscriptionController::class, 'checkout'])->name('checkout');
        });
    });
}); 