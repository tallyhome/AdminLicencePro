<?php
/**
 * Test des traductions après correction
 * Version: 1.0.0
 * Date: 2025-06-21
 */

// Démarrer la session
session_start();

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test des Traductions - AdminLicence</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #eee;
        }
        .test-section {
            margin: 20px 0;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
            border-left: 4px solid #007bff;
        }
        .test-result {
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
        }
        .success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .info {
            background: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }
        .language-selector {
            text-align: center;
            margin: 20px 0;
        }
        .language-selector a {
            display: inline-block;
            padding: 8px 16px;
            margin: 0 5px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background 0.3s;
        }
        .language-selector a:hover,
        .language-selector a.active {
            background: #0056b3;
        }
        pre {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            overflow-x: auto;
            border: 1px solid #dee2e6;
        }
        .api-test {
            margin: 20px 0;
            padding: 20px;
            background: #fff3cd;
            border-radius: 8px;
            border-left: 4px solid #ffc107;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔧 Test des Traductions - AdminLicence</h1>
            <p>Vérification du système de traduction après correction</p>
        </div>

        <div class="language-selector">
            <h3>Sélectionner la langue :</h3>
            <a href="?lang=fr" class="<?php echo (!isset($_GET['lang']) || $_GET['lang'] === 'fr') ? 'active' : ''; ?>">🇫🇷 Français</a>
            <a href="?lang=en" class="<?php echo (isset($_GET['lang']) && $_GET['lang'] === 'en') ? 'active' : ''; ?>">🇬🇧 English</a>
        </div>

        <?php
        // Définir la langue
        $lang = $_GET['lang'] ?? 'fr';
        
        // Test 1: Vérification des fichiers de traduction
        echo '<div class="test-section">';
        echo '<h3>📁 Test 1: Fichiers de traduction</h3>';
        
        $langFiles = [
            'fr' => '../resources/lang/fr.json',
            'en' => '../resources/lang/en.json'
        ];
        
        foreach ($langFiles as $locale => $file) {
            if (file_exists($file)) {
                $content = file_get_contents($file);
                $json = json_decode($content, true);
                if ($json !== null) {
                    echo '<div class="test-result success">✅ ' . $locale . '.json: ' . count($json) . ' traductions chargées</div>';
                } else {
                    echo '<div class="test-result error">❌ ' . $locale . '.json: Erreur JSON - ' . json_last_error_msg() . '</div>';
                }
            } else {
                echo '<div class="test-result error">❌ ' . $locale . '.json: Fichier manquant</div>';
            }
        }
        echo '</div>';

        // Test 2: Test du système Laravel
        echo '<div class="test-section">';
        echo '<h3>🚀 Test 2: Système Laravel</h3>';
        
        try {
            // Tenter de charger l'autoloader Laravel
            if (file_exists('../vendor/autoload.php')) {
                require_once '../vendor/autoload.php';
                echo '<div class="test-result success">✅ Autoloader Laravel chargé</div>';
                
                // Tenter de démarrer l\'application Laravel
                if (file_exists('../bootstrap/app.php')) {
                    $app = require_once '../bootstrap/app.php';
                    echo '<div class="test-result success">✅ Application Laravel initialisée</div>';
                    
                    // Test de la fonction de traduction Laravel
                    try {
                        // Définir la langue
                        $app->setLocale($lang);
                        
                        // Test de quelques traductions
                        $testKeys = [
                            'auth.login',
                            'auth.email', 
                            'auth.password',
                            'menu.home',
                            'language.select'
                        ];
                        
                        echo '<h4>Traductions testées (' . $lang . '):</h4>';
                        foreach ($testKeys as $key) {
                            $translation = __($key);
                            if ($translation !== $key) {
                                echo '<div class="test-result success">✅ ' . $key . ' → ' . htmlspecialchars($translation) . '</div>';
                            } else {
                                echo '<div class="test-result error">❌ ' . $key . ' → Traduction manquante</div>';
                            }
                        }
                        
                    } catch (Exception $e) {
                        echo '<div class="test-result error">❌ Erreur lors du test des traductions: ' . htmlspecialchars($e->getMessage()) . '</div>';
                    }
                } else {
                    echo '<div class="test-result error">❌ bootstrap/app.php manquant</div>';
                }
            } else {
                echo '<div class="test-result error">❌ vendor/autoload.php manquant</div>';
            }
        } catch (Exception $e) {
            echo '<div class="test-result error">❌ Erreur Laravel: ' . htmlspecialchars($e->getMessage()) . '</div>';
        }
        echo '</div>';

        // Test 3: Test de l'installateur
        echo '<div class="test-section">';
        echo '<h3>⚙️ Test 3: Système d\'installation</h3>';
        
        try {
            // Simuler le système d'installation
            if (!defined('INSTALL_PATH')) {
                define('INSTALL_PATH', __DIR__ . '/install');
            }
            if (!defined('ROOT_PATH')) {
                define('ROOT_PATH', __DIR__);
            }
            
            // Vérifier les fichiers d'installation
            $installFiles = [
                'install/functions/language.php',
                'install/languages/fr.php',
                'install/languages/en.php'
            ];
            
            foreach ($installFiles as $file) {
                if (file_exists($file)) {
                    echo '<div class="test-result success">✅ ' . $file . ' existe</div>';
                } else {
                    echo '<div class="test-result error">❌ ' . $file . ' manquant</div>';
                }
            }
            
            // Test des fonctions d'installation
            if (file_exists('install/functions/language.php')) {
                require_once 'install/functions/language.php';
                
                // Simuler la session
                $_SESSION['installer_language'] = $lang;
                
                // Test des fonctions de traduction de l'installateur
                if (function_exists('getCurrentLanguage')) {
                    $currentLang = getCurrentLanguage();
                    echo '<div class="test-result info">ℹ️ Langue actuelle de l\'installateur: ' . $currentLang . '</div>';
                }
                
                if (function_exists('getStepTitle')) {
                    for ($i = 1; $i <= 4; $i++) {
                        $title = getStepTitle($i);
                        echo '<div class="test-result success">✅ Étape ' . $i . ': ' . htmlspecialchars($title) . '</div>';
                    }
                }
            }
            
        } catch (Exception $e) {
            echo '<div class="test-result error">❌ Erreur installateur: ' . htmlspecialchars($e->getMessage()) . '</div>';
        }
        echo '</div>';

        // Test 4: API de licence
        echo '<div class="api-test">';
        echo '<h3>🔑 Test 4: API de Licence</h3>';
        
        $testKey = 'JQUV-QSDM-UT8G-BFHY';
        echo '<p><strong>Clé de test:</strong> ' . $testKey . '</p>';
        
        // Test de l'API
        $url = "https://licence.myvcard.fr/api/check-serial.php";
        $data = [
            'serial_key' => $testKey,
            'domain' => $_SERVER['HTTP_HOST'] ?? 'localhost',
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1'
        ];
        
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Accept: application/json'
            ]
        ]);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($response) {
            $result = json_decode($response, true);
            if ($result && isset($result['status'])) {
                if ($result['status'] === 'success') {
                    echo '<div class="test-result success">✅ API: Licence validée avec succès</div>';
                    echo '<pre>' . json_encode($result, JSON_PRETTY_PRINT) . '</pre>';
                } else {
                    echo '<div class="test-result error">❌ API: ' . ($result['message'] ?? 'Erreur inconnue') . '</div>';
                }
            } else {
                echo '<div class="test-result error">❌ API: Réponse invalide</div>';
            }
        } else {
            echo '<div class="test-result error">❌ API: Pas de réponse</div>';
        }
        echo '</div>';
        ?>

        <div class="test-section">
            <h3>📋 Résumé</h3>
            <div class="test-result info">
                <strong>Tests effectués pour la langue: <?php echo strtoupper($lang); ?></strong><br>
                • Fichiers de traduction<br>
                • Système Laravel<br>
                • Système d'installation<br>
                • API de licence<br><br>
                <strong>Prochaines étapes:</strong><br>
                1. Vérifier <a href="install/install_new.php">l'installateur principal</a><br>
                2. Tester <a href="install_ultra_simple.php">l'installation simplifiée</a><br>
                3. Accéder à <a href="admin/login">l'administration</a>
            </div>
        </div>
    </div>
</body>
</html> 