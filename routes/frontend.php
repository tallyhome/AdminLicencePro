<?php

use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\PagesController;
use App\Http\Controllers\DocumentationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Frontend Routes
|--------------------------------------------------------------------------
|
| Routes pour l'affichage du site public avec le système CMS
|
*/

// Page d'accueil
Route::get('/', [HomeController::class, 'index'])->name('frontend.home');

// Pages supplémentaires (Phase 3C)
Route::get('/about', [PagesController::class, 'about'])->name('frontend.about');
Route::get('/contact', [PagesController::class, 'contact'])->name('frontend.contact');
Route::post('/contact', [PagesController::class, 'submitContact'])->name('frontend.contact.submit');
Route::get('/pricing', [PagesController::class, 'pricing'])->name('frontend.pricing');

// Anciennes routes (compatibilité)
Route::get('/a-propos', [HomeController::class, 'about'])->name('frontend.about.old');
Route::get('/faq', [HomeController::class, 'faqs'])->name('frontend.faqs');

// Pages statiques avec bonnes routes
Route::get('/features', [PagesController::class, 'features'])->name('frontend.features');
Route::get('/support', [PagesController::class, 'support'])->name('frontend.support');
Route::get('/terms', [PagesController::class, 'terms'])->name('frontend.terms');
Route::get('/cgv', [PagesController::class, 'terms'])->name('frontend.cgv'); // Alias pour compatibilité
Route::get('/privacy', [PagesController::class, 'privacy'])->name('frontend.privacy');

// Nouvelles pages CMS
Route::get('/demo', [PagesController::class, 'demo'])->name('frontend.demo');
Route::post('/demo', [PagesController::class, 'submitDemo'])->name('frontend.demo.submit');

// Changement de langue
Route::post('/set-locale', [HomeController::class, 'setLocale'])->name('set.locale');

// Documentation
Route::get('/documentation', [DocumentationController::class, 'index'])->name('documentation.index');
Route::get('/documentation/api', [DocumentationController::class, 'apiIntegration'])->name('documentation.api');

// Phase 3E - SEO Routes
Route::get('/sitemap.xml', [\App\Http\Controllers\SeoController::class, 'sitemap'])->name('sitemap');
Route::get('/robots.txt', [\App\Http\Controllers\SeoController::class, 'robots'])->name('robots');
