<?php

require_once __DIR__ . '/InstallationException.php';

/**
 * Database Manager Class
 * Handles all database-related operations during installation
 */
class DatabaseManager
{
    private $connection;
    private $config;
    
    public function __construct()
    {
        $this->config = [];
    }
    
    /**
     * Test database connection
     */
    public function testConnection(array $config): array
    {
        try {
            $this->config = $config;
            
            $dsn = "mysql:host={$config['host']};charset=utf8mb4";
            if (!empty($config['port'])) {
                $dsn .= ";port={$config['port']}";
            }
            
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
            ];
            
            $pdo = new PDO($dsn, $config['username'], $config['password'] ?? '', $options);
            
            // Test if database exists, create if not
            $dbExists = $this->checkDatabaseExists($pdo, $config['database']);
            if (!$dbExists) {
                $this->createDatabase($pdo, $config['database']);
            }
            
            // Test connection to the specific database
            $dsn .= ";dbname={$config['database']}";
            $this->connection = new PDO($dsn, $config['username'], $config['password'] ?? '', $options);
            
            return [
                'success' => true,
                'message' => 'Database connection successful',
                'database_exists' => $dbExists
            ];
            
        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => 'Database connection failed: ' . $e->getMessage(),
                'error_code' => $e->getCode()
            ];
        }
    }
    
    /**
     * Check if database exists
     */
    private function checkDatabaseExists(PDO $pdo, string $database): bool
    {
        try {
            $stmt = $pdo->prepare("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = ?");
            $stmt->execute([$database]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            return false;
        }
    }
    
    /**
     * Create database
     */
    private function createDatabase(PDO $pdo, string $database): void
    {
        $stmt = $pdo->prepare("CREATE DATABASE IF NOT EXISTS `{$database}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $stmt->execute();
    }
    
    /**
     * Run Laravel migrations
     */
    public function runMigrations(): array
    {
        try {
            $basePath = dirname(dirname(dirname(__DIR__)));
            
            // Change to application directory and run migrations
            $output = [];
            $returnCode = 0;
            
            $command = "cd {$basePath} && php artisan migrate --force 2>&1";
            exec($command, $output, $returnCode);
            
            if ($returnCode !== 0) {
                throw new Exception('Migration failed with return code: ' . $returnCode . '\nOutput: ' . implode('\n', $output));
            }
            
            return [
                'success' => true,
                'message' => 'Migrations executed successfully',
                'output' => $output
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'output' => $output ?? []
            ];
        }
    }
    
    /**
     * Run database seeders
     */
    public function runSeeders(): array
    {
        try {
            $basePath = dirname(dirname(dirname(__DIR__)));
            
            $output = [];
            $returnCode = 0;
            
            $command = "cd {$basePath} && php artisan db:seed --force 2>&1";
            exec($command, $output, $returnCode);
            
            if ($returnCode !== 0) {
                throw new Exception('Seeding failed with return code: ' . $returnCode . '\nOutput: ' . implode('\n', $output));
            }
            
            return [
                'success' => true,
                'message' => 'Database seeded successfully',
                'output' => $output
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'output' => $output ?? []
            ];
        }
    }
    
    /**
     * Create admin user
     */
    public function createAdminUser(array $userData): array
    {
        try {
            if (!$this->connection) {
                throw new Exception('No database connection available');
            }
            
            // Check if admin table exists
            $stmt = $this->connection->query("SHOW TABLES LIKE 'admins'");
            if ($stmt->rowCount() === 0) {
                throw new Exception('Admins table does not exist. Please run migrations first.');
            }
            
            // Check if admin already exists
            $stmt = $this->connection->prepare("SELECT id FROM admins WHERE email = ?");
            $stmt->execute([$userData['email']]);
            if ($stmt->rowCount() > 0) {
                throw new Exception('Admin user with this email already exists');
            }
            
            // Hash password
            $hashedPassword = password_hash($userData['password'], PASSWORD_DEFAULT);
            
            // Insert admin user
            $stmt = $this->connection->prepare("
                INSERT INTO admins (name, email, password, is_super_admin, email_verified_at, created_at, updated_at) 
                VALUES (?, ?, ?, 1, NOW(), NOW(), NOW())
            ");
            
            $stmt->execute([
                $userData['name'],
                $userData['email'],
                $hashedPassword
            ]);
            
            $adminId = $this->connection->lastInsertId();
            
            // Assign super admin role if roles table exists
            $this->assignSuperAdminRole($adminId);
            
            return [
                'success' => true,
                'message' => 'Admin user created successfully',
                'admin_id' => $adminId
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Assign super admin role to user
     */
    private function assignSuperAdminRole(int $adminId): void
    {
        try {
            // Check if roles table exists
            $stmt = $this->connection->query("SHOW TABLES LIKE 'roles'");
            if ($stmt->rowCount() === 0) {
                return; // Roles table doesn't exist, skip role assignment
            }
            
            // Check if super admin role exists
            $stmt = $this->connection->prepare("SELECT id FROM roles WHERE name = 'super-admin'");
            $stmt->execute();
            $role = $stmt->fetch();
            
            if (!$role) {
                // Create super admin role
                $stmt = $this->connection->prepare("
                    INSERT INTO roles (name, guard_name, created_at, updated_at) 
                    VALUES ('super-admin', 'admin', NOW(), NOW())
                ");
                $stmt->execute();
                $roleId = $this->connection->lastInsertId();
            } else {
                $roleId = $role['id'];
            }
            
            // Check if admin_role table exists
            $stmt = $this->connection->query("SHOW TABLES LIKE 'admin_role'");
            if ($stmt->rowCount() > 0) {
                // Assign role to admin
                $stmt = $this->connection->prepare("
                    INSERT IGNORE INTO admin_role (admin_id, role_id, created_at, updated_at) 
                    VALUES (?, ?, NOW(), NOW())
                ");
                $stmt->execute([$adminId, $roleId]);
            }
            
        } catch (Exception $e) {
            // Log error but don't fail the installation
            error_log('Failed to assign super admin role: ' . $e->getMessage());
        }
    }
    
    /**
     * Check database requirements
     */
    public function checkRequirements(): array
    {
        $checks = [];
        
        try {
            if (!$this->connection) {
                throw new Exception('No database connection available');
            }
            
            // Check MySQL version
            $stmt = $this->connection->query("SELECT VERSION() as version");
            $version = $stmt->fetch()['version'];
            $checks['mysql_version'] = [
                'name' => 'MySQL Version',
                'current' => $version,
                'required' => '>= 5.7',
                'status' => version_compare($version, '5.7', '>=')
            ];
            
            // Check InnoDB support
            $stmt = $this->connection->query("SHOW ENGINES");
            $engines = $stmt->fetchAll();
            $innodbSupported = false;
            foreach ($engines as $engine) {
                if ($engine['Engine'] === 'InnoDB' && in_array($engine['Support'], ['YES', 'DEFAULT'])) {
                    $innodbSupported = true;
                    break;
                }
            }
            $checks['innodb_support'] = [
                'name' => 'InnoDB Support',
                'status' => $innodbSupported
            ];
            
            // Check charset support
            $stmt = $this->connection->query("SHOW CHARACTER SET LIKE 'utf8mb4'");
            $checks['utf8mb4_support'] = [
                'name' => 'UTF8MB4 Support',
                'status' => $stmt->rowCount() > 0
            ];
            
            return [
                'success' => true,
                'checks' => $checks
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'checks' => $checks
            ];
        }
    }
    
    /**
     * Backup existing database
     */
    public function backupDatabase(): array
    {
        try {
            if (!$this->connection || empty($this->config)) {
                throw new Exception('No database connection or configuration available');
            }
            
            $backupDir = __DIR__ . '/../backups';
            if (!is_dir($backupDir)) {
                mkdir($backupDir, 0755, true);
            }
            
            $timestamp = date('Y-m-d_H-i-s');
            $backupFile = $backupDir . "/backup_{$this->config['database']}_{$timestamp}.sql";
            
            $command = sprintf(
                'mysqldump -h%s -u%s %s %s > %s',
                escapeshellarg($this->config['host']),
                escapeshellarg($this->config['username']),
                !empty($this->config['password']) ? '-p' . escapeshellarg($this->config['password']) : '',
                escapeshellarg($this->config['database']),
                escapeshellarg($backupFile)
            );
            
            $output = [];
            $returnCode = 0;
            exec($command, $output, $returnCode);
            
            if ($returnCode !== 0) {
                throw new Exception('Backup failed with return code: ' . $returnCode);
            }
            
            return [
                'success' => true,
                'message' => 'Database backup created successfully',
                'backup_file' => $backupFile
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Get database connection
     */
    public function getConnection(): ?PDO
    {
        return $this->connection;
    }
    
    /**
     * Close database connection
     */
    public function closeConnection(): void
    {
        $this->connection = null;
    }
}