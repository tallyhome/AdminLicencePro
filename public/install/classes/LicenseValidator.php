<?php

require_once __DIR__ . '/InstallationException.php';

/**
 * Enhanced License Validator Class
 * Improved version with better error handling and security
 */
class LicenseValidator
{
    private $apiUrl;
    private $timeout;
    private $retryAttempts;
    private $userAgent;
    
    public function __construct(array $config = [])
    {
        $this->apiUrl = $config['api_url'] ?? 'https://licence.myvcard.fr/api/ultra-simple.php';
        $this->timeout = $config['timeout'] ?? 30;
        $this->retryAttempts = $config['retry_attempts'] ?? 3;
        $this->userAgent = $config['user_agent'] ?? 'AdminLicence-Installer/1.0';
    }
    
    /**
     * Validate license key
     */
    public function validate(string $licenseKey): array
    {
        try {
            // Basic format validation
            if (!$this->isValidFormat($licenseKey)) {
                throw InstallationException::licenseError(
                    'Invalid license key format',
                    ['license_key' => $licenseKey]
                );
            }
            
            // Attempt validation with retry logic
            $lastError = null;
            for ($attempt = 1; $attempt <= $this->retryAttempts; $attempt++) {
                try {
                    $result = $this->makeApiRequest($licenseKey);
                    
                    if ($result['success']) {
                        return [
                            'valid' => true,
                            'message' => 'License validated successfully',
                            'data' => $result['data'],
                            'attempt' => $attempt
                        ];
                    } else {
                        $lastError = $result['message'];
                        if ($attempt < $this->retryAttempts) {
                            sleep(2); // Wait before retry
                        }
                    }
                } catch (Exception $e) {
                    $lastError = $e->getMessage();
                    if ($attempt < $this->retryAttempts) {
                        sleep(2);
                    }
                }
            }
            
            throw InstallationException::apiError(
                'License validation failed after ' . $this->retryAttempts . ' attempts: ' . $lastError,
                ['license_key' => $licenseKey, 'attempts' => $this->retryAttempts]
            );
            
        } catch (InstallationException $e) {
            return [
                'valid' => false,
                'message' => $e->getUserMessage(),
                'error_code' => $e->getErrorCode(),
                'context' => $e->getContext()
            ];
        }
    }
    
    /**
     * Check if license key format is valid
     */
    private function isValidFormat(string $licenseKey): bool
    {
        // Remove whitespace
        $licenseKey = trim($licenseKey);
        
        // Check if empty
        if (empty($licenseKey)) {
            return false;
        }
        
        // Check minimum length
        if (strlen($licenseKey) < 10) {
            return false;
        }
        
        // Check for basic alphanumeric pattern (adjust according to your format)
        if (!preg_match('/^[A-Za-z0-9\-_]+$/', $licenseKey)) {
            return false;
        }
        
        return true;
    }
    
    /**
     * Make API request to validate license
     */
    private function makeApiRequest(string $licenseKey): array
    {
        $postData = [
            'serial_key' => $licenseKey,
            'domain' => $this->getCurrentDomain(),
            'ip_address' => $this->getCurrentIpAddress(),
            'user_agent' => $this->userAgent,
            'timestamp' => time(),
            'version' => '4.5.1'
        ];
        
        // Initialize cURL
        $ch = curl_init();
        
        curl_setopt_array($ch, [
            CURLOPT_URL => $this->apiUrl,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($postData),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => $this->timeout,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_USERAGENT => $this->userAgent,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS => 3,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/x-www-form-urlencoded',
                'Accept: application/json',
                'Cache-Control: no-cache'
            ]
        ]);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);
        
        // Check for cURL errors
        if ($response === false || !empty($error)) {
            throw new Exception('cURL error: ' . $error);
        }
        
        // Check HTTP status code
        if ($httpCode !== 200) {
            throw new Exception('HTTP error: ' . $httpCode);
        }
        
        // Parse JSON response
        $data = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Invalid JSON response: ' . json_last_error_msg());
        }
        
        return $this->parseApiResponse($data);
    }
    
    /**
     * Parse API response
     */
    private function parseApiResponse(array $data): array
    {
        // Handle different response formats
        if (isset($data['status'])) {
            $status = $data['status'];
            
            switch ($status) {
                case 'valid':
                case 'active':
                case true:
                case 1:
                case '1':
                    return [
                        'success' => true,
                        'data' => [
                            'status' => 'valid',
                            'license_key' => $data['serial_key'] ?? '',
                            'secure_code' => $data['secure_code'] ?? '',
                            'valid_until' => $data['valid_until'] ?? $data['expiry_date'] ?? null,
                            'domain' => $data['domain'] ?? '',
                            'ip_address' => $data['ip_address'] ?? '',
                            'additional_info' => $data['additional_info'] ?? []
                        ]
                    ];
                    
                case 'expired':
                    return [
                        'success' => false,
                        'message' => 'License has expired'
                    ];
                    
                case 'revoked':
                case 'suspended':
                    return [
                        'success' => false,
                        'message' => 'License has been ' . $status
                    ];
                    
                case 'invalid':
                case false:
                case 0:
                case '0':
                default:
                    return [
                        'success' => false,
                        'message' => $data['message'] ?? 'Invalid license key'
                    ];
            }
        }
        
        // Fallback for unknown response format
        return [
            'success' => false,
            'message' => 'Unknown response format from license server'
        ];
    }
    
    /**
     * Get current domain
     */
    private function getCurrentDomain(): string
    {
        if (isset($_SERVER['HTTP_HOST'])) {
            return $_SERVER['HTTP_HOST'];
        }
        
        if (isset($_SERVER['SERVER_NAME'])) {
            return $_SERVER['SERVER_NAME'];
        }
        
        return 'localhost';
    }
    
    /**
     * Get current IP address
     */
    private function getCurrentIpAddress(): string
    {
        // Check for IP from various sources
        $ipSources = [
            'HTTP_CLIENT_IP',
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_FORWARDED',
            'HTTP_X_CLUSTER_CLIENT_IP',
            'HTTP_FORWARDED_FOR',
            'HTTP_FORWARDED',
            'REMOTE_ADDR'
        ];
        
        foreach ($ipSources as $source) {
            if (isset($_SERVER[$source]) && !empty($_SERVER[$source])) {
                $ip = $_SERVER[$source];
                
                // Handle comma-separated IPs (from proxies)
                if (strpos($ip, ',') !== false) {
                    $ip = trim(explode(',', $ip)[0]);
                }
                
                // Validate IP
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                    return $ip;
                }
            }
        }
        
        return $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
    }
    
    /**
     * Validate license offline (for testing)
     */
    public function validateOffline(string $licenseKey): array
    {
        // This is for testing purposes only
        // In production, always use online validation
        
        if (!$this->isValidFormat($licenseKey)) {
            return [
                'valid' => false,
                'message' => 'Invalid license key format'
            ];
        }
        
        // Simple offline validation (for development/testing)
        $testKeys = [
            'TEST-KEY-1234-5678',
            'DEV-LICENSE-2024',
            'DEMO-ADMIN-LICENCE'
        ];
        
        if (in_array(strtoupper($licenseKey), array_map('strtoupper', $testKeys))) {
            return [
                'valid' => true,
                'message' => 'Test license validated (offline mode)',
                'data' => [
                    'status' => 'valid',
                    'license_key' => $licenseKey,
                    'secure_code' => 'TEST-CODE',
                    'valid_until' => date('Y-m-d', strtotime('+1 year')),
                    'domain' => $this->getCurrentDomain(),
                    'ip_address' => $this->getCurrentIpAddress(),
                    'mode' => 'offline_test'
                ]
            ];
        }
        
        return [
            'valid' => false,
            'message' => 'License key not found in test database'
        ];
    }
    
    /**
     * Get API status
     */
    public function getApiStatus(): array
    {
        try {
            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL => $this->apiUrl,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 10,
                CURLOPT_CONNECTTIMEOUT => 5,
                CURLOPT_NOBODY => true,
                CURLOPT_USERAGENT => $this->userAgent
            ]);
            
            $result = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            curl_close($ch);
            
            if ($result === false || !empty($error)) {
                return [
                    'available' => false,
                    'message' => 'API unreachable: ' . $error
                ];
            }
            
            return [
                'available' => $httpCode === 200,
                'http_code' => $httpCode,
                'message' => $httpCode === 200 ? 'API is available' : 'API returned HTTP ' . $httpCode
            ];
            
        } catch (Exception $e) {
            return [
                'available' => false,
                'message' => 'Error checking API status: ' . $e->getMessage()
            ];
        }
    }
}