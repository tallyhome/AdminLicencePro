<?php

namespace App\Helpers;

use App\Models\Setting;

class FrontendHelper
{
    /**
     * Récupérer un paramètre du frontend
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function get(string $key, $default = null)
    {
        return Setting::get('frontend_' . $key, $default);
    }

    /**
     * Récupérer tous les paramètres du frontend
     *
     * @return array
     */
    public static function all(): array
    {
        return [
            // Textes principaux
            'app_name' => self::get('app_name', config('app.name')),
            'app_tagline' => self::get('app_tagline', 'Système de gestion de licences'),
            'hero_title' => self::get('hero_title', 'Gérez vos licences facilement'),
            'hero_subtitle' => self::get('hero_subtitle', 'Une solution complète pour la gestion de vos licences logicielles'),
            'footer_text' => self::get('footer_text', '© 2025 AdminLicence. Tous droits réservés.'),
            
            // Liens sociaux
            'social_facebook' => self::get('social_facebook', ''),
            'social_twitter' => self::get('social_twitter', ''),
            'social_linkedin' => self::get('social_linkedin', ''),
            'social_github' => self::get('social_github', ''),
            
            // Contact
            'contact_email' => self::get('contact_email', ''),
            'contact_phone' => self::get('contact_phone', ''),
            'contact_address' => self::get('contact_address', ''),
            
            // Couleurs et thème
            'primary_color' => self::get('primary_color', '#007bff'),
            'secondary_color' => self::get('secondary_color', '#6c757d'),
            'success_color' => self::get('success_color', '#28a745'),
            'danger_color' => self::get('danger_color', '#dc3545'),
            
            // Images
            'logo_url' => self::get('logo_url', asset('images/logo.png')),
            'hero_image_url' => self::get('hero_image_url', asset('images/dashboard-hero-2.png')),
            'favicon_url' => self::get('favicon_url', asset('favicon.ico')),
            
            // Fonctionnalités
            'show_hero_section' => self::get('show_hero_section', true),
            'show_features_section' => self::get('show_features_section', true),
            'show_contact_section' => self::get('show_contact_section', true),
            'maintenance_mode' => self::get('maintenance_mode', false),
            'maintenance_message' => self::get('maintenance_message', 'Site en maintenance, revenez bientôt !'),
        ];
    }

    /**
     * Vérifier si le mode maintenance est activé
     *
     * @return bool
     */
    public static function isMaintenanceMode(): bool
    {
        return (bool) self::get('maintenance_mode', false);
    }

    /**
     * Récupérer le CSS personnalisé généré
     *
     * @return string
     */
    public static function getCustomCSS(): string
    {
        $cssPath = storage_path('app/public/frontend/custom.css');
        
        if (file_exists($cssPath)) {
            return file_get_contents($cssPath);
        }
        
        return '';
    }

    /**
     * Récupérer l'URL du CSS personnalisé
     *
     * @return string|null
     */
    public static function getCustomCSSUrl(): ?string
    {
        $cssPath = 'frontend/custom.css';
        
        if (\Storage::disk('public')->exists($cssPath)) {
            return \Storage::disk('public')->url($cssPath);
        }
        
        return null;
    }
} 