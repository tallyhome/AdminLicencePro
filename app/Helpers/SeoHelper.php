<?php

namespace App\Helpers;

use App\Http\Controllers\SeoController;

class SeoHelper
{
    /**
     * Générer les méta tags HTML
     */
    public static function generateMetaTags($page = 'home', $customData = [])
    {
        $seoData = SeoController::getPageSeoData($page, $customData);
        $defaultMeta = config('seo.default_meta', []);
        $ogDefaults = config('seo.og_defaults', []);
        $twitterDefaults = config('seo.twitter_defaults', []);
        
        $html = '';
        
        // Meta tags de base
        $html .= '<title>' . htmlspecialchars($seoData['title']) . '</title>' . "\n";
        $html .= '<meta name="description" content="' . htmlspecialchars($seoData['description']) . '">' . "\n";
        $html .= '<meta name="keywords" content="' . htmlspecialchars($seoData['keywords']) . '">' . "\n";
        $html .= '<meta name="robots" content="' . ($defaultMeta['robots'] ?? 'index, follow') . '">' . "\n";
        $html .= '<meta name="author" content="' . ($defaultMeta['author'] ?? 'AdminLicence') . '">' . "\n";
        $html .= '<link rel="canonical" href="' . $seoData['url'] . '">' . "\n";
        
        // Open Graph
        $html .= '<meta property="og:title" content="' . htmlspecialchars($seoData['title']) . '">' . "\n";
        $html .= '<meta property="og:description" content="' . htmlspecialchars($seoData['description']) . '">' . "\n";
        $html .= '<meta property="og:url" content="' . $seoData['url'] . '">' . "\n";
        $html .= '<meta property="og:type" content="' . ($seoData['type'] ?? $ogDefaults['type'] ?? 'website') . '">' . "\n";
        $html .= '<meta property="og:site_name" content="' . ($ogDefaults['site_name'] ?? 'AdminLicence') . '">' . "\n";
        $html .= '<meta property="og:locale" content="' . ($ogDefaults['locale'] ?? 'fr_FR') . '">' . "\n";
        
        if (isset($seoData['image'])) {
            $html .= '<meta property="og:image" content="' . $seoData['image'] . '">' . "\n";
            $html .= '<meta property="og:image:width" content="' . ($ogDefaults['image_width'] ?? 1200) . '">' . "\n";
            $html .= '<meta property="og:image:height" content="' . ($ogDefaults['image_height'] ?? 630) . '">' . "\n";
        }
        
        // Twitter Card
        $html .= '<meta name="twitter:card" content="' . ($twitterDefaults['card'] ?? 'summary_large_image') . '">' . "\n";
        $html .= '<meta name="twitter:title" content="' . htmlspecialchars($seoData['title']) . '">' . "\n";
        $html .= '<meta name="twitter:description" content="' . htmlspecialchars($seoData['description']) . '">' . "\n";
        
        if (isset($twitterDefaults['site'])) {
            $html .= '<meta name="twitter:site" content="' . $twitterDefaults['site'] . '">' . "\n";
        }
        
        if (isset($seoData['image'])) {
            $html .= '<meta name="twitter:image" content="' . $seoData['image'] . '">' . "\n";
        }
        
        return $html;
    }
    
    /**
     * Générer le JSON-LD pour les données structurées
     */
    public static function generateJsonLd($type = 'organization', $data = [])
    {
        $jsonLd = SeoController::generateJsonLd($type, $data);
        
        if (empty($jsonLd)) {
            return '';
        }
        
        return '<script type="application/ld+json">' . json_encode($jsonLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>';
    }
    
    /**
     * Obtenir les scripts d'analytics
     */
    public static function getAnalyticsScripts()
    {
        return SeoController::getAnalyticsCode();
    }
}

// Fonction helper globale
if (!function_exists('seo_meta')) {
    function seo_meta($page = 'home', $customData = []) {
        return SeoHelper::generateMetaTags($page, $customData);
    }
}

if (!function_exists('json_ld')) {
    function json_ld($type = 'organization', $data = []) {
        return SeoHelper::generateJsonLd($type, $data);
    }
} 