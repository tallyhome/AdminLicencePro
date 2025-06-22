<?php

namespace App\Services;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class TranslationService
{
    /**
     * Liste des langues disponibles dans l'application
     *
     * @var array
     */
    protected $availableLocales;

    /**
     * Langue de fallback par défaut
     *
     * @var string
     */
    protected $fallbackLocale;

    /**
     * Cache des traductions chargées
     *
     * @var array
     */
    protected $translations = [];

    public function __construct()
    {
        $this->availableLocales = config('app.available_locales', ['en', 'fr']);
        $this->fallbackLocale = config('app.fallback_locale', 'fr');
    }

    /**
     * Obtenir la liste des langues disponibles
     *
     * @return array
     */
    public function getAvailableLocales(): array
    {
        return $this->availableLocales;
    }

    /**
     * Vérifier si une langue est disponible
     *
     * @param string $locale
     * @return bool
     */
    public function isLocaleAvailable(string $locale): bool
    {
        return in_array($locale, $this->availableLocales);
    }

    /**
     * Définir la langue active
     *
     * @param string $locale
     * @return bool
     */
    public function setLocale(string $locale): bool
    {
        if ($this->isLocaleAvailable($locale)) {
            // Stocker la langue en session
            Session::put('locale', $locale);
            
            // Définir la langue de l'application
            App::setLocale($locale);
            
            // Mettre à jour la configuration
            Config::set('app.locale', $locale);
            
            // Vider le cache des traductions
            Cache::forget('translations.' . $locale);
            
            // Forcer la mise à jour de la session
            Session::save();
            
            return true;
        }
        
        return false;
    }

    /**
     * Obtenir la langue actuelle de l'application
     *
     * @return string
     */
    public function getLocale(): string
    {
        // D'abord vérifier la session
        if (Session::has('locale')) {
            $locale = Session::get('locale');
            if ($this->isLocaleAvailable($locale)) {
                App::setLocale($locale);
                return $locale;
            }
        }
        
        // Sinon, utiliser la langue de l'application
        return App::getLocale();
    }

    /**
     * Alias pour getLocale() pour compatibilité
     *
     * @return string
     */
    public function getCurrentLocale(): string
    {
        return $this->getLocale();
    }

    /**
     * Obtenir le nom de la langue dans sa langue native
     *
     * @param string $locale
     * @return string
     */
    public function getLocaleName(string $locale): string
    {
        $names = [
            'en' => 'English',
            'fr' => 'Français',
            'es' => 'Español',
            'de' => 'Deutsch',
            'it' => 'Italiano',
            'pt' => 'Português',
            'nl' => 'Nederlands',
            'ru' => 'Русский',
            'zh' => '中文',
            'ja' => '日本語',
            'tr' => 'Türkçe',
            'ar' => 'العربية'
        ];

        return $names[$locale] ?? $locale;
    }

    /**
     * Obtenir les traductions pour la langue active
     *
     * @param string|null $locale
     * @return array
     */
    public function getTranslations(?string $locale = null): array
    {
        $locale = $locale ?? $this->getCurrentLocale();
        
        // Récupérer les traductions du cache ou les charger
        $cacheKey = 'translations.' . $locale;
        
        return Cache::remember($cacheKey, 60 * 24, function () use ($locale) {
            $translations = [];
            
            // Charger le fichier principal de traduction depuis resources/locales
            $path = resource_path('locales/' . $locale . '/translation.json');
            
            // Vérifier si le fichier existe
            if (File::exists($path)) {
                try {
                    $content = File::get($path);
                    $decoded = json_decode($content, true);
                    
                    // Vérifier si le JSON est valide
                    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                        $translations = $decoded;
                    } else {
                        Log::error("Erreur de décodage JSON pour {$locale}/translation.json: " . json_last_error_msg());
                        // Fallback vers l'anglais
                        $translations = $this->loadFallbackTranslations();
                    }
                } catch (\Exception $e) {
                    Log::error("Exception lors du chargement de {$locale}/translation.json: " . $e->getMessage());
                    // Fallback vers l'anglais
                    $translations = $this->loadFallbackTranslations();
                }
            } else {
                // Fallback vers l'anglais si la traduction n'existe pas
                $translations = $this->loadFallbackTranslations();
            }
            
            // Charger les fichiers de traduction supplémentaires
            $additionalFiles = File::glob(resource_path('locales/' . $locale . '/*.json'));
            foreach ($additionalFiles as $file) {
                if (basename($file) !== 'translation.json') {
                    try {
                        $key = pathinfo($file, PATHINFO_FILENAME);
                        $content = File::get($file);
                        $decoded = json_decode($content, true);
                        
                        // Vérifier si le JSON est valide
                        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                            $translations[$key] = $decoded;
                        } else {
                            Log::error("Erreur de décodage JSON pour {$locale}/{$key}.json: " . json_last_error_msg());
                        }
                    } catch (\Exception $e) {
                        Log::error("Exception lors du chargement de {$locale}/{$key}.json: " . $e->getMessage());
                    }
                }
            }
            
            return $translations;
        });
    }

    /**
     * Charge les traductions pour une locale donnée
     *
     * @param string $locale
     * @return array
     */
    private function loadTranslations(string $locale): array
    {
        $translations = [];
        
        // Utiliser le bon chemin : /resources/locales/ au lieu de /resources/lang/
        $localeDir = resource_path("locales/{$locale}");
        
        if (!is_dir($localeDir)) {
            Log::warning("Répertoire de traductions non trouvé: {$localeDir}");
            return [];
        }

        // Charger tous les fichiers JSON du répertoire de la locale
        $files = glob($localeDir . '/*.json');
        
        foreach ($files as $file) {
            $filename = pathinfo($file, PATHINFO_FILENAME);
            $content = file_get_contents($file);
            
            if ($content === false) {
                Log::warning("Impossible de lire le fichier de traduction: {$file}");
                continue;
            }
            
            $decoded = json_decode($content, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error("Erreur JSON dans le fichier {$file}: " . json_last_error_msg());
                continue;
            }
            
            if (is_array($decoded)) {
                // Si c'est le fichier principal (translation.json), fusionner directement
                if ($filename === 'translation') {
                    $translations = array_merge($translations, $decoded);
                } else {
                    // Sinon, utiliser le nom du fichier comme clé
                    $translations[$filename] = $decoded;
                }
            }
        }
        
        return $translations;
    }

    /**
     * Traduit une clé donnée avec les paramètres fournis
     *
     * @param string $key
     * @param array $parameters
     * @param string|null $locale
     * @return string
     */
    public function translate(string $key, array $parameters = [], ?string $locale = null): string
    {
        $locale = $locale ?? $this->getCurrentLocale();
        
        // Charger les traductions si nécessaire
        if (!isset($this->translations[$locale])) {
            $this->translations[$locale] = $this->loadTranslations($locale);
        }
        
        $translations = $this->translations[$locale];
        
        // Gérer les clés imbriquées avec des points (ex: "common.dashboard")
        $keys = explode('.', $key);
        $value = $translations;
        
        foreach ($keys as $keyPart) {
            if (is_array($value) && isset($value[$keyPart])) {
                $value = $value[$keyPart];
            } else {
                // Clé non trouvée, essayer avec la locale de fallback
                if ($locale !== $this->fallbackLocale) {
                    return $this->translate($key, $parameters, $this->fallbackLocale);
                }
                
                // Si même la locale de fallback n'a pas la clé, retourner la clé elle-même
                Log::debug("Clé de traduction non trouvée: {$key} pour la locale {$locale}");
                return $key;
            }
        }
        
        // Si la valeur n'est pas une chaîne, retourner la clé
        if (!is_string($value)) {
            Log::debug("Valeur de traduction invalide pour la clé: {$key}");
            return $key;
        }
        
        // Remplacer les paramètres dans la chaîne traduite
        foreach ($parameters as $param => $replacement) {
            $value = str_replace(":{$param}", $replacement, $value);
        }
        
        return $value;
    }

    /**
     * Obtenir la liste des langues disponibles avec leurs noms natifs
     *
     * @return array
     */
    public function getAvailableLanguages(): array
    {
        $languages = [];
        foreach ($this->availableLocales as $locale) {
            $languages[$locale] = $this->getLocaleName($locale);
        }
        return $languages;
    }

    /**
     * Charge les traductions de fallback (français)
     *
     * @return array
     */
    protected function loadFallbackTranslations(): array
    {
        $fallbackLocale = config('app.fallback_locale', 'fr');
        $fallbackPath = resource_path('locales/' . $fallbackLocale . '/translation.json');
        
        try {
            if (File::exists($fallbackPath)) {
                $content = File::get($fallbackPath);
                $decoded = json_decode($content, true);
                
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    return $decoded;
                }
            }
        } catch (\Exception $e) {
            Log::error("Exception lors du chargement des traductions de fallback: " . $e->getMessage());
        }
        
        // Si tout échoue, retourner un tableau vide
        return [];
    }

    /**
     * Force le chargement des traductions depuis les fichiers JSON dans le dossier resources/lang
     * 
     * @param string $locale
     * @return array
     */
    public function loadJsonTranslations(string $locale): array
    {
        try {
            // Chemin principal : fichier JSON direct dans resources/lang
            $path = resource_path('lang/' . $locale . '.json');
            
            // Utiliser le fallback en anglais si la langue spécifiée n'est pas disponible
            if (!File::exists($path)) {
                $locale = config('app.fallback_locale', 'en');
                $path = resource_path('lang/' . $locale . '.json');
            }
            
            $translations = [];
            
            // Charger le contenu du fichier
            if (File::exists($path)) {
                $content = File::get($path);
                $translations = json_decode($content, true) ?? [];
                
                // Vider le cache et stocker les nouvelles traductions
                $cacheKey = 'translations.' . $locale;
                Cache::forget($cacheKey);
                Cache::put($cacheKey, $translations, now()->addDay());
                
                // S'assurer que le translator est disponible avant d'appeler addJsonPath
                if (app()->bound('translator')) {
                    app('translator')->addJsonPath(resource_path('lang'));
                }
            }
            
            return $translations;
        } catch (\Exception $e) {
            // En cas d'erreur, retourner un tableau vide et logger l'erreur
            Log::error('Erreur lors du chargement des traductions: ' . $e->getMessage());
            return [];
        }
    }
}