<?php

namespace App\Helpers;

/**
 * Helper pour la gestion des versions de l'application
 */
class VersionHelper
{
    /**
     * Obtient la version complète formatée de l'application
     *
     * @return string
     */
    public static function getFullVersion(): string
    {
        $version = config('version.major') . '.' . config('version.minor') . '.' . config('version.patch');
        if (config('version.release')) {
            $version .= '-' . config('version.release');
        }
        return $version;
    }
}