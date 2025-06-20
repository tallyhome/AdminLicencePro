<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Route as RouteFacade;

/**
 * Classe d'aide pour la gestion des routes
 */
class RouteHelper
{
    /**
     * Génère une URL pour une route nommée
     * Redirection désactivée pour éviter les conflits - nous utilisons LoginRedirectServiceProvider
     *
     * @param string $name Nom de la route
     * @param array $parameters Paramètres de la route
     * @param bool $absolute URL absolue ou relative
     * @return string
     */
    public static function route($name, $parameters = [], $absolute = true)
    {
        // Redirection désactivée pour éviter les conflits
        // if ($name === 'login') {
        //     return route('admin.login', $parameters, $absolute);
        // }
        
        // Utiliser la fonction route() standard pour toutes les routes
        return app('url')->route($name, $parameters, $absolute);
    }
}
