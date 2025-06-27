<?php

/**
 * Fonction route() de Laravel - Désactivée pour éviter les conflits de redirection
 * Nous utilisons maintenant LoginRedirectServiceProvider pour gérer les redirections
 */
// if (!function_exists('route')) {
//     /**
//      * Generate the URL to a named route.
//      *
//      * @param  string  $name
//      * @param  mixed  $parameters
//      * @param  bool  $absolute
//      * @return string
//      */
//     function route($name, $parameters = [], $absolute = true)
//     {
//         // Si la route est 'login', utiliser 'admin.login' à la place
//         if ($name === 'login') {
//             $name = 'admin.login';
//         }
//         
//         return app('url')->route($name, $parameters, $absolute);
//     }
// }

/**
 * Fonction d'aide pour les traductions
 *
 * @param string $key La clé de traduction
 * @param array $replace Les variables à remplacer
 * @param string|null $locale La langue à utiliser
 * @return string
 */
if (!function_exists('t')) {
    function t($key, $replace = [], $locale = null)
    {
        try {
            // Essayer d'utiliser le service de traduction personnalisé
            if (app()->bound('App\Services\TranslationService')) {
                return app('App\Services\TranslationService')->translate($key, $replace, $locale);
            }
            
            // Fallback vers la fonction __() de Laravel
            return __($key, $replace, $locale);
        } catch (\Exception $e) {
            // En cas d'erreur, retourner la clé
            \Log::error('Erreur de traduction: ' . $e->getMessage());
            return $key;
        }
    }
}

if (!function_exists('frontend')) {
    /**
     * Récupérer un paramètre du frontend
     *
     * @param string|null $key
     * @param mixed $default
     * @return mixed
     */
    function frontend(string $key = null, $default = null)
    {
        if ($key === null) {
            return \App\Helpers\FrontendHelper::all();
        }
        
        return \App\Helpers\FrontendHelper::get($key, $default);
    }
}
