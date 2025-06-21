<?php
/**
 * AJAX handler for database connection testing
 * Amélioré avec validation CSRF et gestion d'erreurs
 */

session_start();

// Include utility functions
require_once '../includes/functions.php';
require_once '../functions/language.php';
require_once '../classes/DatabaseManager.php';

// Set content type to JSON
header('Content-Type: application/json');

// Vérification CSRF
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
    http_response_code(403);
    echo json_encode([
        'success' => false,
        'message' => t('csrf_token_invalid')
    ]);
    exit;
}

// Check if database configuration is provided
if (!isset($_POST['db_host']) || !isset($_POST['db_name']) || !isset($_POST['db_username'])) {
    echo json_encode([
        'success' => false,
        'message' => t('missing_database_parameters'),
    ]);
    exit;
}

try {
    // Récupérer et valider les paramètres
    $host = $_POST['db_host'] ?? 'localhost';
    $port = (int)($_POST['db_port'] ?? 3306);
    $database = $_POST['db_name'] ?? '';
    $username = $_POST['db_username'] ?? '';
    $password = $_POST['db_password'] ?? '';
    
    // Validation du port
    if ($port < 1 || $port > 65535) {
        throw new Exception(t('invalid_port_number'));
    }
    
    // Validation du nom de la base de données
    if (!preg_match('/^[a-zA-Z0-9_]+$/', $database)) {
        throw new Exception(t('invalid_database_name'));
    }
    
    // Créer une instance du gestionnaire de base de données
    $dbManager = new DatabaseManager();
    
    // Tester la connexion
    $connectionResult = $dbManager->testConnection($host, $port, $username, $password, $database);
    
    if ($connectionResult['success']) {
        // Vérifier les exigences de la base de données
        $requirements = $dbManager->checkDatabaseRequirements($host, $port, $username, $password);
        
        // Stocker la configuration en session
        $_SESSION['db_config'] = [
            'host' => $host,
            'port' => $port,
            'database' => $database,
            'username' => $username,
            'password' => $password
        ];
        $_SESSION['db_tested'] = true;
        
        $response = [
            'success' => true,
            'message' => t('database_connection_successful'),
            'details' => [
                'mysql_version' => $requirements['mysql_version'],
                'innodb_support' => $requirements['innodb_support'],
                'utf8mb4_support' => $requirements['utf8mb4_support'],
                'database_exists' => $connectionResult['database_exists']
            ]
        ];
        
        // Ajouter des avertissements si nécessaire
        $warnings = [];
        if (!$requirements['innodb_support']) {
            $warnings[] = t('innodb_not_supported');
        }
        if (!$requirements['utf8mb4_support']) {
            $warnings[] = t('utf8mb4_not_supported');
        }
        if (version_compare($requirements['mysql_version'], '5.7.0', '<')) {
            $warnings[] = t('mysql_version_warning');
        }
        
        if (!empty($warnings)) {
            $response['warnings'] = $warnings;
        }
        
    } else {
        $response = [
            'success' => false,
            'message' => $connectionResult['message'],
            'error_code' => $connectionResult['error_code'] ?? null
        ];
    }
    
} catch (Exception $e) {
    $response = [
        'success' => false,
        'message' => $e->getMessage(),
        'error_code' => $e->getCode()
    ];
    
    // Log l'erreur pour le débogage
    error_log('Database connection test error: ' . $e->getMessage());
}

// Return the result
echo json_encode($response, JSON_UNESCAPED_UNICODE);
