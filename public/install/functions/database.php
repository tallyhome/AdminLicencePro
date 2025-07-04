<?php
/**
 * Fonctions de gestion de la base de données pour l'installateur
 */

/**
 * Exécute les migrations de la base de données
 * 
 * @return bool True si les migrations ont été exécutées avec succès, false sinon
 */
function runMigrations() {
    try {
        writeToLog("Exécution des migrations");
        
        // Récupérer les informations de la base de données depuis la session
        $dbConfig = $_SESSION['db_config'] ?? [];
        if (empty($dbConfig)) {
            writeToLog("Informations de la base de données manquantes", 'ERROR');
            return false;
        }
        
        // Mettre à jour le fichier .env avec les informations de la base de données
        $envUpdates = [
            'DB_HOST' => $dbConfig['host'],
            'DB_PORT' => $dbConfig['port'],
            'DB_DATABASE' => $dbConfig['database'],
            'DB_USERNAME' => $dbConfig['username'],
            'DB_PASSWORD' => $dbConfig['password']
        ];
        
        if (!updateEnvFile($envUpdates)) {
            writeToLog("Impossible de mettre à jour le fichier .env avec les informations de la base de données", 'ERROR');
            return false;
        }
        
        // Méthode alternative pour exécuter les migrations directement via PDO
        try {
            // Se connecter à la base de données
            $dsn = "mysql:host={$dbConfig['host']};port={$dbConfig['port']};dbname={$dbConfig['database']}";
            $pdo = new PDO($dsn, $dbConfig['username'], $dbConfig['password'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
            
            // Vérifier si la table migrations existe
            $hasMigrationsTable = false;
            $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
            if (in_array('migrations', $tables)) {
                $hasMigrationsTable = true;
            }
            
            // Si la table migrations n'existe pas, la créer
            if (!$hasMigrationsTable) {
                writeToLog("Création de la table migrations");
                $pdo->exec("
                    CREATE TABLE `migrations` (
                        `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                        `migration` varchar(255) NOT NULL,
                        `batch` int(11) NOT NULL,
                        PRIMARY KEY (`id`)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
                ");
            }
            
            // Vérifier si exec() est disponible
            if (function_exists('exec') && !in_array('exec', explode(',', ini_get('disable_functions')))) {
                // Essayer d'exécuter la commande de migration
                $command = 'cd ' . escapeshellarg(ROOT_PATH) . ' && php artisan migrate --force 2>&1';
                exec($command, $output, $returnCode);
                
                if ($returnCode === 0) {
                    writeToLog("Migrations exécutées avec succès via artisan");
                    return true;
                } else {
                    writeToLog("Avertissement lors de l'exécution des migrations via artisan: " . implode("\n", $output), 'WARNING');
                }
            } else {
                writeToLog("Fonction exec() non disponible, migration manuelle", 'INFO');
            }
            
            // Migration manuelle sans exec()
            writeToLog("Exécution de la migration manuelle...", 'INFO');
            
            // Créer les tables essentielles manuellement
            $tables = [
                'users' => "CREATE TABLE IF NOT EXISTS `users` (
                    `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
                    `name` varchar(255) NOT NULL,
                    `email` varchar(255) NOT NULL,
                    `email_verified_at` timestamp NULL DEFAULT NULL,
                    `password` varchar(255) NOT NULL,
                    `remember_token` varchar(100) DEFAULT NULL,
                    `created_at` timestamp NULL DEFAULT NULL,
                    `updated_at` timestamp NULL DEFAULT NULL,
                    PRIMARY KEY (`id`),
                    UNIQUE KEY `users_email_unique` (`email`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",
                
                'admins' => "CREATE TABLE IF NOT EXISTS `admins` (
                    `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
                    `name` varchar(255) NOT NULL,
                    `email` varchar(255) NOT NULL,
                    `password` varchar(255) NOT NULL,
                    `created_at` timestamp NULL DEFAULT NULL,
                    `updated_at` timestamp NULL DEFAULT NULL,
                    PRIMARY KEY (`id`),
                    UNIQUE KEY `admins_email_unique` (`email`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",
                
                'projects' => "CREATE TABLE IF NOT EXISTS `projects` (
                    `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
                    `name` varchar(255) NOT NULL,
                    `description` text,
                    `status` varchar(50) DEFAULT 'active',
                    `created_at` timestamp NULL DEFAULT NULL,
                    `updated_at` timestamp NULL DEFAULT NULL,
                    PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",
                
                'serial_keys' => "CREATE TABLE IF NOT EXISTS `serial_keys` (
                    `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
                    `serial_key` varchar(255) NOT NULL,
                    `project_id` bigint(20) UNSIGNED DEFAULT NULL,
                    `status` varchar(50) DEFAULT 'active',
                    `created_at` timestamp NULL DEFAULT NULL,
                    `updated_at` timestamp NULL DEFAULT NULL,
                    PRIMARY KEY (`id`),
                    UNIQUE KEY `serial_keys_serial_key_unique` (`serial_key`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;"
            ];
            
            foreach ($tables as $tableName => $sql) {
                try {
                    $pdo->exec($sql);
                    writeToLog("Table '$tableName' créée avec succès", 'SUCCESS');
                } catch (PDOException $e) {
                    writeToLog("Erreur lors de la création de la table '$tableName': " . $e->getMessage(), 'WARNING');
                    // Continuer même en cas d'erreur (table peut déjà exister)
                }
            }
            
            writeToLog("Migration manuelle terminée avec succès");
            return true;
        } catch (PDOException $e) {
            writeToLog("Erreur PDO lors de la migration: " . $e->getMessage(), 'ERROR');
            return false;
        }
    } catch (Exception $e) {
        writeToLog("Erreur lors de l'exécution des migrations: " . $e->getMessage(), 'ERROR');
        return false;
    }
}

/**
 * Crée l'utilisateur administrateur
 * 
 * @return bool True si l'utilisateur a été créé avec succès, false sinon
 */
function createAdminUser() {
    try {
        writeToLog("Création de l'utilisateur administrateur");
        
        // Récupérer les informations de l'administrateur depuis la session
        $adminConfig = $_SESSION['admin_config'] ?? [];
        if (empty($adminConfig)) {
            writeToLog("Informations de l'administrateur manquantes", 'ERROR');
            return false;
        }
        
        // Se connecter à la base de données
        $dbConfig = $_SESSION['db_config'] ?? [];
        $dsn = "mysql:host={$dbConfig['host']};port={$dbConfig['port']};dbname={$dbConfig['database']}";
        $pdo = new PDO($dsn, $dbConfig['username'], $dbConfig['password'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
        
        // Vérifier si la table admins existe
        $tables = $pdo->query("SHOW TABLES LIKE 'admins'")->fetchAll();
        if (empty($tables)) {
            writeToLog("La table 'admins' n'existe pas", 'ERROR');
            return false;
        }
        
        // Vérifier si un administrateur existe déjà
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM admins");
        $stmt->execute();
        $adminCount = $stmt->fetchColumn();
        
        if ($adminCount > 0) {
            writeToLog("Un administrateur existe déjà, mise à jour des informations");
            
            // Mettre à jour l'administrateur existant
            $stmt = $pdo->prepare("UPDATE admins SET name = ?, email = ?, password = ?, updated_at = NOW() WHERE id = 1");
            $stmt->execute([
                $adminConfig['name'],
                $adminConfig['email'],
                password_hash($adminConfig['password'], PASSWORD_BCRYPT, ['cost' => 12])
            ]);
        } else {
            writeToLog("Création d'un nouvel administrateur");
            
            // Créer un nouvel administrateur
            $stmt = $pdo->prepare("INSERT INTO admins (name, email, password, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())");
            $stmt->execute([
                $adminConfig['name'],
                $adminConfig['email'],
                password_hash($adminConfig['password'], PASSWORD_BCRYPT, ['cost' => 12])
            ]);
        }
        
        writeToLog("Administrateur créé/mis à jour avec succès");
        return true;
    } catch (Exception $e) {
        writeToLog("Erreur lors de la création de l'administrateur: " . $e->getMessage(), 'ERROR');
        return false;
    }
}

/**
 * Importe un fichier SQL dans la base de données
 * 
 * @param string $file Chemin vers le fichier SQL
 * @return bool True si le fichier a été importé avec succès, false sinon
 */
function importSqlFile($file) {
    try {
        // Vérifier si le fichier existe et est lisible
        if (!file_exists($file)) {
            throw new Exception('Le fichier SQL n\'existe pas : ' . $file);
        }
        
        if (!is_readable($file)) {
            throw new Exception('Le fichier SQL n\'est pas lisible : ' . $file);
        }
        
        // Lire le contenu du fichier
        $sql = file_get_contents($file);
        if ($sql === false) {
            throw new Exception('Impossible de lire le fichier SQL : ' . $file);
        }
        
        // Se connecter à la base de données
        $dbConfig = $_SESSION['db_config'] ?? [];
        $dsn = "mysql:host={$dbConfig['host']};port={$dbConfig['port']};dbname={$dbConfig['database']}";
        $pdo = new PDO($dsn, $dbConfig['username'], $dbConfig['password'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
        
        // Exécuter les requêtes SQL
        $pdo->exec($sql);
        
        writeToLog("Fichier SQL importé avec succès : " . $file);
        return true;
    } catch (Exception $e) {
        writeToLog("Erreur lors de l'importation du fichier SQL : " . $e->getMessage(), 'ERROR');
        return false;
    }
}
