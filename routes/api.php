<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LicenceApiController;
use App\Http\Controllers\Api\TranslationApiController;
use App\Http\Controllers\WebhookController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Route de test pour vérifier que l'API fonctionne (avec rate limiting)
Route::middleware('throttle:20,1')->get('/test', [LicenceApiController::class, 'test']);

// Routes pour la validation des licences - Version directe (pour compatibilité)
Route::middleware('throttle:10,1')->post('/check-serial', [LicenceApiController::class, 'checkSerial']);

// Route pour récupérer les traductions (SANS rate limiting pour éviter 404)
Route::get('/translations', [TranslationApiController::class, 'getTranslations']);

// Routes pour la validation des licences - Version avec préfixe v1
Route::prefix('v1')->middleware('licence-api')->group(function () {
    // Route de test pour vérifier que l'API fonctionne
    Route::get('/test', [LicenceApiController::class, 'test']);
    
    // Route publique pour vérifier une clé de licence
    Route::post('/check-serial', [LicenceApiController::class, 'checkSerial']);
});

// Routes protégées par JWT pour les opérations sensibles
Route::prefix('v1')->middleware(['licence-api', 'jwt.auth'])->group(function () {
    // Ces routes nécessitent une authentification JWT
    Route::post('/verify-licence', [LicenceApiController::class, 'verifyLicence']);
    Route::post('/refresh-token', [LicenceApiController::class, 'refreshToken']);
});

// Routes pour les webhooks (sans middleware d'authentification)
Route::prefix('webhooks')->group(function () {
    Route::post('/stripe', [WebhookController::class, 'stripe'])->name('webhooks.stripe');
    Route::post('/paypal', [WebhookController::class, 'paypal'])->name('webhooks.paypal');
});