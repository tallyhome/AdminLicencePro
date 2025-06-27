<?php

return [
    /*
    |--------------------------------------------------------------------------
    | SEO Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration des paramètres SEO pour AdminLicence
    |
    */

    // Google Analytics ID
    'google_analytics_id' => env('GOOGLE_ANALYTICS_ID', null),
    
    // Facebook Pixel ID
    'facebook_pixel_id' => env('FACEBOOK_PIXEL_ID', null),
    
    // Meta tags par défaut
    'default_meta' => [
        'title' => 'AdminLicence - Gestion Professionnelle de Licences',
        'description' => 'Solution professionnelle de gestion de licences logicielles. Protégez votre propriété intellectuelle avec notre plateforme sécurisée.',
        'keywords' => 'licences, logiciels, sécurité, API, protection, AdminLicence',
        'author' => 'AdminLicence Team',
        'robots' => 'index, follow',
        'viewport' => 'width=device-width, initial-scale=1',
        'charset' => 'UTF-8',
    ],
    
    // Open Graph par défaut
    'og_defaults' => [
        'type' => 'website',
        'site_name' => 'AdminLicence',
        'locale' => 'fr_FR',
        'image' => '/images/og-default.jpg',
        'image_width' => 1200,
        'image_height' => 630,
    ],
    
    // Twitter Card par défaut
    'twitter_defaults' => [
        'card' => 'summary_large_image',
        'site' => '@AdminLicence',
        'creator' => '@AdminLicence',
    ],
    
    // Structured Data
    'structured_data' => [
        'organization' => [
            'name' => 'AdminLicence',
            'url' => env('APP_URL', 'https://adminlicence.com'),
            'logo' => env('APP_URL', 'https://adminlicence.com') . '/images/logo.png',
            'description' => 'Solution professionnelle de gestion de licences logicielles',
            'telephone' => '+33-1-23-45-67-89',
            'email' => 'contact@adminlicence.com',
            'address' => [
                'streetAddress' => '123 Rue de la Tech',
                'addressLocality' => 'Paris',
                'postalCode' => '75001',
                'addressCountry' => 'FR'
            ]
        ]
    ],
    
    // Sitemap configuration
    'sitemap' => [
        'cache_duration' => 3600, // 1 heure
        'pages' => [
            'home' => ['priority' => '1.0', 'changefreq' => 'weekly'],
            'about' => ['priority' => '0.8', 'changefreq' => 'monthly'],
            'contact' => ['priority' => '0.7', 'changefreq' => 'monthly'],
            'pricing' => ['priority' => '0.9', 'changefreq' => 'weekly'],
            'features' => ['priority' => '0.8', 'changefreq' => 'weekly'],
        ]
    ],
    
    // Robots.txt configuration
    'robots' => [
        'disallow' => [
            '/admin/',
            '/api/',
            '/vendor/',
            '/storage/app/',
            '/storage/framework/',
        ],
        'allow' => [
            '/storage/app/public/',
        ]
    ]
]; 