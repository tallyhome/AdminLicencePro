<?php

/**
 * Custom Installation Exception Class
 * Provides better error handling and context for installation processes
 */
class InstallationException extends Exception
{
    protected $context;
    protected $step;
    protected $errorCode;
    
    public function __construct(
        string $message = "",
        int $code = 0,
        ?Throwable $previous = null,
        array $context = [],
        ?string $step = null,
        ?string $errorCode = null
    ) {
        parent::__construct($message, $code, $previous);
        $this->context = $context;
        $this->step = $step;
        $this->errorCode = $errorCode;
    }
    
    /**
     * Get the context information
     */
    public function getContext(): array
    {
        return $this->context;
    }
    
    /**
     * Get the installation step where the error occurred
     */
    public function getStep(): ?string
    {
        return $this->step;
    }
    
    /**
     * Get the custom error code
     */
    public function getErrorCode(): ?string
    {
        return $this->errorCode;
    }
    
    /**
     * Get a user-friendly error message
     */
    public function getUserMessage(): string
    {
        $messages = [
            'LICENSE_INVALID' => 'La clé de licence fournie n\'est pas valide.',
            'LICENSE_EXPIRED' => 'La clé de licence a expiré.',
            'LICENSE_REVOKED' => 'La clé de licence a été révoquée.',
            'DATABASE_CONNECTION' => 'Impossible de se connecter à la base de données.',
            'DATABASE_CREATION' => 'Erreur lors de la création de la base de données.',
            'MIGRATION_FAILED' => 'Erreur lors de l\'exécution des migrations.',
            'PERMISSION_DENIED' => 'Permissions insuffisantes pour effectuer cette opération.',
            'FILE_NOT_WRITABLE' => 'Impossible d\'écrire dans le fichier ou répertoire.',
            'INVALID_CONFIG' => 'Configuration invalide ou manquante.',
            'API_ERROR' => 'Erreur lors de la communication avec l\'API.',
            'VALIDATION_ERROR' => 'Données de validation incorrectes.'
        ];
        
        return $messages[$this->errorCode] ?? $this->getMessage();
    }
    
    /**
     * Get detailed error information for logging
     */
    public function getDetailedInfo(): array
    {
        return [
            'message' => $this->getMessage(),
            'user_message' => $this->getUserMessage(),
            'code' => $this->getCode(),
            'error_code' => $this->getErrorCode(),
            'step' => $this->getStep(),
            'context' => $this->getContext(),
            'file' => $this->getFile(),
            'line' => $this->getLine(),
            'trace' => $this->getTraceAsString(),
            'timestamp' => date('Y-m-d H:i:s')
        ];
    }
    
    /**
     * Log the exception to the installation log
     */
    public function logError(): void
    {
        $logFile = __DIR__ . '/../logs/installation_errors.log';
        $logDir = dirname($logFile);
        
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }
        
        $errorInfo = $this->getDetailedInfo();
        $logEntry = '[' . $errorInfo['timestamp'] . '] ' . 
                   'INSTALLATION_ERROR: ' . $errorInfo['user_message'] . '\n' .
                   'Step: ' . ($errorInfo['step'] ?? 'Unknown') . '\n' .
                   'Error Code: ' . ($errorInfo['error_code'] ?? 'None') . '\n' .
                   'Technical Message: ' . $errorInfo['message'] . '\n' .
                   'File: ' . $errorInfo['file'] . ':' . $errorInfo['line'] . '\n' .
                   'Context: ' . json_encode($errorInfo['context']) . '\n' .
                   'Trace: ' . $errorInfo['trace'] . '\n' .
                   str_repeat('-', 80) . '\n';
        
        file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);
    }
    
    /**
     * Create a license validation exception
     */
    public static function licenseError(string $message, array $context = []): self
    {
        return new self(
            $message,
            0,
            null,
            $context,
            'license_verification',
            'LICENSE_INVALID'
        );
    }
    
    /**
     * Create a database connection exception
     */
    public static function databaseError(string $message, array $context = []): self
    {
        return new self(
            $message,
            0,
            null,
            $context,
            'database_configuration',
            'DATABASE_CONNECTION'
        );
    }
    
    /**
     * Create a file permission exception
     */
    public static function permissionError(string $message, array $context = []): self
    {
        return new self(
            $message,
            0,
            null,
            $context,
            'system_requirements',
            'PERMISSION_DENIED'
        );
    }
    
    /**
     * Create a validation exception
     */
    public static function validationError(string $message, array $context = []): self
    {
        return new self(
            $message,
            0,
            null,
            $context,
            null,
            'VALIDATION_ERROR'
        );
    }
    
    /**
     * Create an API communication exception
     */
    public static function apiError(string $message, array $context = []): self
    {
        return new self(
            $message,
            0,
            null,
            $context,
            'license_verification',
            'API_ERROR'
        );
    }
}