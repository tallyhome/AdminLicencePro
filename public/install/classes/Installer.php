<?php

require_once __DIR__ . '/InstallationException.php';
require_once __DIR__ . '/DatabaseManager.php';
require_once __DIR__ . '/LicenseValidator.php';

/**
 * Main Installer Class
 * Orchestrates the entire installation process
 */
class Installer
{
    private $config;
    private $currentStep;
    private $maxSteps;
    private $databaseManager;
    private $licenseValidator;
    private $logger;
    
    public function __construct(array $config = [])
    {
        $this->config = array_merge($this->getDefaultConfig(), $config);
        $this->currentStep = 1;
        $this->maxSteps = 5;
        $this->databaseManager = new DatabaseManager();
        $this->licenseValidator = new LicenseValidator();
        $this->initializeLogger();
    }
    
    /**
     * Get default configuration
     */
    private function getDefaultConfig(): array
    {
        return [
            'steps' => [
                1 => 'license_verification',
                2 => 'system_requirements',
                3 => 'database_configuration',
                4 => 'admin_setup',
                5 => 'finalization'
            ],
            'api_endpoints' => [
                'license' => 'https://adminlicence.eu/api/ultra-simple.php'
            ],
            'required_php_version' => '8.0',
            'required_extensions' => [
                'pdo', 'pdo_mysql', 'mbstring', 'openssl', 'tokenizer', 'xml', 'ctype', 'json'
            ],
            'required_permissions' => [
                'storage' => 0755,
                'bootstrap/cache' => 0755,
                '.env' => 0644
            ]
        ];
    }
    
    /**
     * Initialize logger
     */
    private function initializeLogger(): void
    {
        $logDir = __DIR__ . '/../logs';
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }
        
        $this->logger = $logDir . '/installation.log';
    }
    
    /**
     * Log a message
     */
    private function log(string $message, string $level = 'INFO'): void
    {
        $timestamp = date('Y-m-d H:i:s');
        $logEntry = "[{$timestamp}] [{$level}] {$message}\n";
        file_put_contents($this->logger, $logEntry, FILE_APPEND | LOCK_EX);
    }
    
    /**
     * Start the installation process
     */
    public function start(): array
    {
        try {
            $this->log('Installation process started');
            
            // Check if already installed
            if ($this->isAlreadyInstalled()) {
                throw InstallationException::validationError(
                    'Application is already installed',
                    ['env_exists' => true]
                );
            }
            
            return [
                'success' => true,
                'step' => $this->currentStep,
                'step_name' => $this->config['steps'][$this->currentStep],
                'message' => 'Installation initialized successfully'
            ];
            
        } catch (InstallationException $e) {
            $e->logError();
            return [
                'success' => false,
                'error' => $e->getUserMessage(),
                'details' => $e->getContext()
            ];
        }
    }
    
    /**
     * Process a specific installation step
     */
    public function processStep(int $step, array $data = []): array
    {
        try {
            $this->currentStep = $step;
            $stepName = $this->config['steps'][$step] ?? 'unknown';
            
            $this->log("Processing step {$step}: {$stepName}");
            
            switch ($step) {
                case 1:
                    return $this->processLicenseVerification($data);
                case 2:
                    return $this->processSystemRequirements($data);
                case 3:
                    return $this->processDatabaseConfiguration($data);
                case 4:
                    return $this->processAdminSetup($data);
                case 5:
                    return $this->processFinalization($data);
                default:
                    throw InstallationException::validationError(
                        "Invalid step: {$step}",
                        ['step' => $step]
                    );
            }
            
        } catch (InstallationException $e) {
            $e->logError();
            return [
                'success' => false,
                'error' => $e->getUserMessage(),
                'step' => $step,
                'details' => $e->getContext()
            ];
        }
    }
    
    /**
     * Process license verification
     */
    private function processLicenseVerification(array $data): array
    {
        if (empty($data['license_key'])) {
            throw InstallationException::validationError(
                'License key is required',
                ['field' => 'license_key']
            );
        }
        
        $result = $this->licenseValidator->validate($data['license_key']);
        
        if (!$result['valid']) {
            throw InstallationException::licenseError(
                $result['message'],
                ['license_key' => $data['license_key'], 'response' => $result]
            );
        }
        
        // Store license information
        $this->storeLicenseInfo($result['data']);
        
        return [
            'success' => true,
            'message' => 'License verified successfully',
            'next_step' => 2,
            'license_info' => $result['data']
        ];
    }
    
    /**
     * Process system requirements check
     */
    private function processSystemRequirements(array $data): array
    {
        $checks = [];
        
        // PHP version check
        $phpVersion = PHP_VERSION;
        $requiredVersion = $this->config['required_php_version'];
        $checks['php_version'] = [
            'name' => 'PHP Version',
            'required' => ">= {$requiredVersion}",
            'current' => $phpVersion,
            'status' => version_compare($phpVersion, $requiredVersion, '>=')
        ];
        
        // Extensions check
        foreach ($this->config['required_extensions'] as $extension) {
            $checks['extensions'][$extension] = [
                'name' => $extension,
                'status' => extension_loaded($extension)
            ];
        }
        
        // Permissions check
        $basePath = dirname(dirname(dirname(__DIR__)));
        foreach ($this->config['required_permissions'] as $path => $permission) {
            $fullPath = $basePath . '/' . $path;
            $checks['permissions'][$path] = [
                'name' => $path,
                'path' => $fullPath,
                'required' => decoct($permission),
                'status' => is_writable($fullPath) || is_writable(dirname($fullPath))
            ];
        }
        
        // Check if all requirements are met
        $allPassed = $checks['php_version']['status'];
        foreach ($checks['extensions'] as $ext) {
            $allPassed = $allPassed && $ext['status'];
        }
        foreach ($checks['permissions'] as $perm) {
            $allPassed = $allPassed && $perm['status'];
        }
        
        if (!$allPassed) {
            throw InstallationException::permissionError(
                'System requirements not met',
                ['checks' => $checks]
            );
        }
        
        return [
            'success' => true,
            'message' => 'System requirements check passed',
            'next_step' => 3,
            'checks' => $checks
        ];
    }
    
    /**
     * Process database configuration
     */
    private function processDatabaseConfiguration(array $data): array
    {
        $required = ['host', 'database', 'username'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                throw InstallationException::validationError(
                    "Field {$field} is required",
                    ['field' => $field]
                );
            }
        }
        
        // Test database connection
        $connectionResult = $this->databaseManager->testConnection($data);
        if (!$connectionResult['success']) {
            throw InstallationException::databaseError(
                $connectionResult['message'],
                ['connection_data' => $data]
            );
        }
        
        // Create .env file
        $this->createEnvFile($data);
        
        // Run migrations
        $migrationResult = $this->databaseManager->runMigrations();
        if (!$migrationResult['success']) {
            throw InstallationException::databaseError(
                'Migration failed: ' . $migrationResult['message'],
                ['migration_output' => $migrationResult]
            );
        }
        
        return [
            'success' => true,
            'message' => 'Database configured successfully',
            'next_step' => 4
        ];
    }
    
    /**
     * Process admin setup
     */
    private function processAdminSetup(array $data): array
    {
        $required = ['name', 'email', 'password'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                throw InstallationException::validationError(
                    "Field {$field} is required",
                    ['field' => $field]
                );
            }
        }
        
        // Validate email
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw InstallationException::validationError(
                'Invalid email format',
                ['email' => $data['email']]
            );
        }
        
        // Create admin user
        $adminResult = $this->databaseManager->createAdminUser($data);
        if (!$adminResult['success']) {
            throw InstallationException::databaseError(
                'Failed to create admin user: ' . $adminResult['message'],
                ['admin_data' => $data]
            );
        }
        
        return [
            'success' => true,
            'message' => 'Admin user created successfully',
            'next_step' => 5
        ];
    }
    
    /**
     * Process finalization
     */
    private function processFinalization(array $data): array
    {
        // Generate application key
        $this->generateAppKey();
        
        // Clear caches
        $this->clearCaches();
        
        // Create installation complete marker
        $this->createInstallationMarker();
        
        $this->log('Installation completed successfully');
        
        return [
            'success' => true,
            'message' => 'Installation completed successfully',
            'redirect' => '/admin/login'
        ];
    }
    
    /**
     * Check if application is already installed
     */
    private function isAlreadyInstalled(): bool
    {
        $envFile = dirname(dirname(dirname(__DIR__))) . '/.env';
        return file_exists($envFile) && filesize($envFile) > 0;
    }
    
    /**
     * Store license information
     */
    private function storeLicenseInfo(array $licenseData): void
    {
        $licenseFile = __DIR__ . '/../temp/license_info.json';
        $tempDir = dirname($licenseFile);
        
        if (!is_dir($tempDir)) {
            mkdir($tempDir, 0755, true);
        }
        
        file_put_contents($licenseFile, json_encode($licenseData, JSON_PRETTY_PRINT));
    }
    
    /**
     * Create .env file
     */
    private function createEnvFile(array $dbConfig): void
    {
        $envTemplate = file_get_contents(__DIR__ . '/../templates/.env.template');
        
        $replacements = [
            '{{DB_HOST}}' => $dbConfig['host'],
            '{{DB_DATABASE}}' => $dbConfig['database'],
            '{{DB_USERNAME}}' => $dbConfig['username'],
            '{{DB_PASSWORD}}' => $dbConfig['password'] ?? '',
            '{{APP_KEY}}' => 'base64:' . base64_encode(random_bytes(32)),
            '{{APP_URL}}' => $this->getAppUrl()
        ];
        
        $envContent = str_replace(array_keys($replacements), array_values($replacements), $envTemplate);
        
        $envFile = dirname(dirname(dirname(__DIR__))) . '/.env';
        file_put_contents($envFile, $envContent);
    }
    
    /**
     * Generate application key
     */
    private function generateAppKey(): void
    {
        $basePath = dirname(dirname(dirname(__DIR__)));
        exec("cd {$basePath} && php artisan key:generate --force");
    }
    
    /**
     * Clear application caches
     */
    private function clearCaches(): void
    {
        $basePath = dirname(dirname(dirname(__DIR__)));
        $commands = [
            'cache:clear',
            'config:clear',
            'route:clear',
            'view:clear'
        ];
        
        foreach ($commands as $command) {
            exec("cd {$basePath} && php artisan {$command}");
        }
    }
    
    /**
     * Create installation complete marker
     */
    private function createInstallationMarker(): void
    {
        $markerFile = dirname(dirname(dirname(__DIR__))) . '/storage/app/installed';
        $markerDir = dirname($markerFile);
        
        if (!is_dir($markerDir)) {
            mkdir($markerDir, 0755, true);
        }
        
        file_put_contents($markerFile, date('Y-m-d H:i:s'));
    }
    
    /**
     * Get application URL
     */
    private function getAppUrl(): string
    {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $path = dirname(dirname($_SERVER['REQUEST_URI']));
        
        return $protocol . '://' . $host . $path;
    }
    
    /**
     * Get current installation progress
     */
    public function getProgress(): array
    {
        return [
            'current_step' => $this->currentStep,
            'max_steps' => $this->maxSteps,
            'progress_percentage' => ($this->currentStep / $this->maxSteps) * 100,
            'step_name' => $this->config['steps'][$this->currentStep] ?? 'unknown'
        ];
    }
}