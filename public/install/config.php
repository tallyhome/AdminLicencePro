<?php
/**
 * Configuration de base pour l'installateur
 */

// Activer l'affichage des erreurs pour le débogage
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Définir le fuseau horaire par défaut
date_default_timezone_set('UTC');

// Définir le chemin racine du projet
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', realpath(dirname(dirname(__DIR__))));
}
if (!defined('INSTALL_PATH')) {
    define('INSTALL_PATH', __DIR__);
}

// Configuration des langues
if (!defined('DEFAULT_LANGUAGE')) {
    define('DEFAULT_LANGUAGE', 'fr');
}
if (!defined('AVAILABLE_LANGUAGES')) {
    define('AVAILABLE_LANGUAGES', [
        'fr' => 'Français',
        'en' => 'English',
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
    ]);
}

// Vérifier les extensions PHP requises
$required_extensions = ['curl', 'json', 'pdo', 'pdo_mysql', 'mbstring'];
$missing_extensions = [];
foreach ($required_extensions as $ext) {
    if (!extension_loaded($ext)) {
        $missing_extensions[] = $ext;
    }
}

if (!empty($missing_extensions)) {
    die("Extensions PHP requises manquantes : " . implode(', ', $missing_extensions));
}

// Vérifier si les fonctions nécessaires sont disponibles
$required_functions = ['curl_init', 'json_encode', 'json_decode'];
$disabled_functions = explode(',', ini_get('disable_functions'));
$missing_functions = [];

foreach ($required_functions as $func) {
    if (!function_exists($func) || in_array($func, $disabled_functions)) {
        $missing_functions[] = $func;
    }
}

if (!empty($missing_functions)) {
    die("Fonctions PHP requises désactivées : " . implode(', ', $missing_functions));
}

// Démarrer la session si elle n'est pas déjà démarrée
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
