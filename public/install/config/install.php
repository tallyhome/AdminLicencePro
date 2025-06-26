<?php

/**
 * Installation Configuration
 * Centralized configuration for the installation process
 */

return [
    // Installation steps configuration
    'steps' => [
        1 => [
            'name' => 'license_verification',
            'title' => 'Vérification de licence',
            'description' => 'Vérification de votre clé de licence AdminLicence',
            'template' => 'steps/license_verification.php',
            'required_fields' => ['license_key'],
            'validation_rules' => [
                'license_key' => 'required|string|min:10'
            ]
        ],
        2 => [
            'name' => 'system_requirements',
            'title' => 'Vérification du système',
            'description' => 'Vérification des prérequis système et des permissions',
            'template' => 'steps/system_requirements.php',
            'required_fields' => [],
            'auto_check' => true
        ],
        3 => [
            'name' => 'database_configuration',
            'title' => 'Configuration de la base de données',
            'description' => 'Configuration de la connexion à la base de données',
            'template' => 'steps/database_configuration.php',
            'required_fields' => ['host', 'database', 'username'],
            'validation_rules' => [
                'host' => 'required|string',
                'database' => 'required|string|alpha_dash',
                'username' => 'required|string',
                'password' => 'nullable|string',
                'port' => 'nullable|integer|between:1,65535'
            ]
        ],
        4 => [
            'name' => 'admin_setup',
            'title' => 'Configuration administrateur',
            'description' => 'Création du compte administrateur principal',
            'template' => 'steps/admin_setup.php',
            'required_fields' => ['name', 'email', 'password', 'password_confirmation'],
            'validation_rules' => [
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'password' => 'required|string|min:8|confirmed',
                'password_confirmation' => 'required|string'
            ]
        ],
        5 => [
            'name' => 'finalization',
            'title' => 'Finalisation',
            'description' => 'Finalisation de l\'installation et configuration finale',
            'template' => 'steps/finalization.php',
            'required_fields' => [],
            'auto_process' => true
        ]
    ],
    
    // API endpoints configuration
    'api_endpoints' => [
        'license' => 'https://adminlicence.eu/api/ultra-simple.php',
        'license_check' => 'https://adminlicence.eu/api/check.php',
        'license_info' => 'https://adminlicence.eu/api/info.php'
    ],
    
    // System requirements
    'system_requirements' => [
        'php_version' => [
            'minimum' => '8.0.0',
            'recommended' => '8.2.0'
        ],
        'required_extensions' => [
            'pdo' => 'PDO Extension',
            'pdo_mysql' => 'PDO MySQL Extension',
            'mbstring' => 'Multibyte String Extension',
            'openssl' => 'OpenSSL Extension',
            'tokenizer' => 'Tokenizer Extension',
            'xml' => 'XML Extension',
            'ctype' => 'Ctype Extension',
            'json' => 'JSON Extension',
            'bcmath' => 'BCMath Extension',
            'curl' => 'cURL Extension',
            'fileinfo' => 'Fileinfo Extension'
        ],
        'optional_extensions' => [
            'gd' => 'GD Extension (for image processing)',
            'imagick' => 'ImageMagick Extension (alternative to GD)',
            'zip' => 'ZIP Extension (for backups)',
            'redis' => 'Redis Extension (for caching)'
        ],
        'required_permissions' => [
            'storage' => [
                'path' => 'storage',
                'permission' => 0755,
                'recursive' => true
            ],
            'bootstrap_cache' => [
                'path' => 'bootstrap/cache',
                'permission' => 0755,
                'recursive' => true
            ],
            'public_storage' => [
                'path' => 'public/storage',
                'permission' => 0755,
                'recursive' => false
            ],
            'env_file' => [
                'path' => '.env',
                'permission' => 0644,
                'recursive' => false,
                'create_if_missing' => true
            ]
        ],
        'recommended_settings' => [
            'memory_limit' => '256M',
            'max_execution_time' => '300',
            'upload_max_filesize' => '10M',
            'post_max_size' => '10M'
        ]
    ],
    
    // Database configuration
    'database' => [
        'supported_drivers' => ['mysql'],
        'mysql' => [
            'minimum_version' => '5.7.0',
            'recommended_version' => '8.0.0',
            'required_features' => [
                'innodb' => 'InnoDB Storage Engine',
                'utf8mb4' => 'UTF8MB4 Character Set'
            ],
            'default_charset' => 'utf8mb4',
            'default_collation' => 'utf8mb4_unicode_ci'
        ]
    ],
    
    // Security configuration
    'security' => [
        'csrf_protection' => true,
        'xss_protection' => true,
        'secure_headers' => true,
        'password_requirements' => [
            'min_length' => 8,
            'require_uppercase' => false,
            'require_lowercase' => false,
            'require_numbers' => false,
            'require_symbols' => false
        ],
        'session_security' => [
            'secure_cookies' => true,
            'http_only_cookies' => true,
            'same_site_cookies' => 'strict'
        ]
    ],
    
    // Installation behavior
    'installation' => [
        'allow_reinstall' => false,
        'backup_before_install' => true,
        'cleanup_on_failure' => true,
        'log_installation' => true,
        'timeout' => [
            'step_timeout' => 300,
            'api_timeout' => 30,
            'database_timeout' => 60
        ],
        'retry_attempts' => [
            'api_calls' => 3,
            'database_operations' => 2
        ]
    ],
    
    // UI configuration
    'ui' => [
        'theme' => 'modern',
        'show_progress_bar' => true,
        'show_step_indicators' => true,
        'auto_hide_alerts' => true,
        'alert_timeout' => 5000,
        'animations' => true,
        'responsive_design' => true
    ],
    
    // Logging configuration
    'logging' => [
        'enabled' => true,
        'level' => 'info',
        'max_file_size' => '10MB',
        'max_files' => 5,
        'log_api_requests' => true,
        'log_database_queries' => false,
        'log_user_actions' => true
    ],
    
    // Error handling
    'error_handling' => [
        'show_detailed_errors' => false,
        'log_errors' => true,
        'email_errors' => false,
        'error_page_template' => 'error.php'
    ],
    
    // Localization
    'localization' => [
        'default_language' => 'fr',
        'supported_languages' => ['fr', 'en', 'es', 'pt', 'ar', 'ru', 'zh'],
        'auto_detect_language' => true,
        'fallback_language' => 'en'
    ],
    
    // Cache configuration
    'cache' => [
        'enabled' => true,
        'driver' => 'file',
        'ttl' => 3600,
        'prefix' => 'install_',
        'cache_api_responses' => true,
        'cache_system_checks' => true
    ],
    
    // Development settings
    'development' => [
        'debug_mode' => false,
        'show_sql_queries' => false,
        'mock_api_responses' => false,
        'skip_license_check' => false,
        'allow_test_licenses' => false
    ]
];