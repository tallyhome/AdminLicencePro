<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Version de l'application
    |--------------------------------------------------------------------------
    |
    | Ce fichier contient les informations de version de l'application.
    | Ces informations sont utilisées pour afficher la version actuelle
    | dans l'interface d'administration.
    |
    */

    'major' => 3,      // Changements majeurs/incompatibles
    'minor' => 3,      // Nouvelles fonctionnalités compatibles
    'patch' => 1,      // Corrections de bugs compatibles
    'release' => null, // Suffixe de version (alpha, beta, rc, etc.),
     
    // Date de la dernière mise à jour
    'last_update' => '10/04/2025',
    
    // Référence à la fonction helper pour obtenir la version complète formatée
    // Nous utilisons une chaîne au lieu d'une closure pour éviter les problèmes de sérialisation
    'full' => 'App\Helpers\VersionHelper::getFullVersion',
];