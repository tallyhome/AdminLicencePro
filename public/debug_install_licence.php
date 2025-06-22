<?php
/**
 * Debug spécifique pour la vérification de licence dans l'installation
 */

// Configuration stricte des erreurs
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>🔍 Diagnostic Installation - Vérification Licence</h1>";

// Démarrer la session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vérifier les fichiers requis
$requiredFiles = [
    'install/config.php',
    'install/functions/language.php', 
    'install/functions/core.php'
];

echo "<h2>1. Vérification des fichiers requis :</h2>";
foreach ($requiredFiles as $file) {
    $fullPath = __DIR__ . '/' . $file;
    if (file_exists($fullPath)) {
        echo "✅ <strong>$file</strong> - Trouvé<br>";
    } else {
        echo "❌ <strong>$file</strong> - MANQUANT<br>";
    }
}

// Inclure les fichiers si ils existent
try {
    require_once __DIR__ . '/install/config.php';
    echo "✅ <strong>config.php</strong> - Inclus<br>";
} catch (Exception $e) {
    echo "❌ <strong>config.php</strong> - Erreur: " . $e->getMessage() . "<br>";
}

try {
    require_once __DIR__ . '/install/functions/language.php';
    echo "✅ <strong>language.php</strong> - Inclus<br>";
} catch (Exception $e) {
    echo "❌ <strong>language.php</strong> - Erreur: " . $e->getMessage() . "<br>";
}

try {
    require_once __DIR__ . '/install/functions/core.php';
    echo "✅ <strong>core.php</strong> - Inclus<br>";
} catch (Exception $e) {
    echo "❌ <strong>core.php</strong> - Erreur: " . $e->getMessage() . "<br>";
}

echo "<h2>2. Test de la fonction verifierLicence :</h2>";

if (function_exists('verifierLicence')) {
    echo "✅ <strong>Fonction verifierLicence</strong> - Disponible<br>";
    
    // Test avec la clé de test
    $testKey = "JQUV-QSDM-UT8G-BFHY";
    $domain = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $ipAddress = $_SERVER['SERVER_ADDR'] ?? $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
    
    echo "<p><strong>Clé testée :</strong> $testKey</p>";
    echo "<p><strong>Domaine :</strong> $domain</p>";
    echo "<p><strong>IP :</strong> $ipAddress</p>";
    
    try {
        $result = verifierLicence($testKey, $domain, $ipAddress);
        
        echo "<h3>Résultat de verifierLicence() :</h3>";
        echo "<pre>";
        print_r($result);
        echo "</pre>";
        
        if ($result['valide']) {
            echo "<div style='padding: 15px; background: #d4edda; color: #155724; border-radius: 4px; margin: 20px 0;'>";
            echo "✅ <strong>LICENCE VALIDE :</strong> " . $result['message'];
            echo "</div>";
        } else {
            echo "<div style='padding: 15px; background: #f8d7da; color: #721c24; border-radius: 4px; margin: 20px 0;'>";
            echo "❌ <strong>LICENCE INVALIDE :</strong> " . $result['message'];
            echo "</div>";
        }
    } catch (Exception $e) {
        echo "<div style='padding: 15px; background: #f8d7da; color: #721c24; border-radius: 4px; margin: 20px 0;'>";
        echo "❌ <strong>ERREUR EXCEPTION :</strong> " . $e->getMessage();
        echo "</div>";
    }
} else {
    echo "❌ <strong>Fonction verifierLicence</strong> - NON DISPONIBLE<br>";
}

echo "<h2>3. Simulation du POST de l'installation :</h2>";

// Simuler ce qui se passe dans l'installation
if (isset($_POST['test_license'])) {
    echo "<h3>Test POST reçu :</h3>";
    
    $serial_key = $_POST['serial_key'] ?? '';
    echo "<p><strong>Clé reçue :</strong> '$serial_key'</p>";
    
    if (empty($serial_key)) {
        echo "<div style='padding: 15px; background: #f8d7da; color: #721c24; border-radius: 4px;'>";
        echo "❌ <strong>ERREUR :</strong> Clé de licence vide";
        echo "</div>";
    } else {
        $domain = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $ipAddress = $_SERVER['SERVER_ADDR'] ?? $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
        
        echo "<p><strong>Domaine :</strong> $domain</p>";
        echo "<p><strong>IP :</strong> $ipAddress</p>";
        
        if (function_exists('verifierLicence')) {
            $licenseCheck = verifierLicence($serial_key, $domain, $ipAddress);
            
            echo "<h4>Résultat de la vérification :</h4>";
            echo "<pre>";
            print_r($licenseCheck);
            echo "</pre>";
            
            if (!$licenseCheck['valide']) {
                echo "<div style='padding: 15px; background: #f8d7da; color: #721c24; border-radius: 4px;'>";
                echo "❌ <strong>LICENCE INVALIDE :</strong> " . $licenseCheck['message'];
                echo "</div>";
                echo "<p><strong>Étape actuelle :</strong> 1 (reste à l'étape licence)</p>";
            } else {
                echo "<div style='padding: 15px; background: #d4edda; color: #155724; border-radius: 4px;'>";
                echo "✅ <strong>LICENCE VALIDE :</strong> " . $licenseCheck['message'];
                echo "</div>";
                echo "<p><strong>Étape suivante :</strong> 2 (passage à la configuration BDD)</p>";
                
                // Stocker en session comme dans l'installation
                $_SESSION['license_data'] = $licenseCheck['donnees'];
                $_SESSION['license_key'] = $serial_key;
                $_SESSION['license_valid'] = true;
                
                echo "<p><strong>Session mise à jour :</strong></p>";
                echo "<pre>";
                print_r($_SESSION);
                echo "</pre>";
            }
        } else {
            echo "<div style='padding: 15px; background: #f8d7da; color: #721c24; border-radius: 4px;'>";
            echo "❌ <strong>ERREUR :</strong> Fonction verifierLicence non disponible";
            echo "</div>";
        }
    }
}

echo "<h2>4. Formulaire de test :</h2>";
?>

<form method="POST" style="background: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0;">
    <h3>Tester la vérification de licence :</h3>
    <div style="margin-bottom: 15px;">
        <label for="serial_key" style="display: block; margin-bottom: 5px; font-weight: 600;">Clé de licence :</label>
        <input type="text" 
               id="serial_key" 
               name="serial_key" 
               value="JQUV-QSDM-UT8G-BFHY" 
               style="width: 300px; padding: 8px; border: 1px solid #ccc; border-radius: 4px; text-transform: uppercase;">
    </div>
    <button type="submit" name="test_license" value="1" style="padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer;">
        Tester la licence
    </button>
</form>

<?php
echo "<h2>5. Informations système :</h2>";
echo "<p><strong>Version PHP :</strong> " . PHP_VERSION . "</p>";
echo "<p><strong>Extensions cURL :</strong> " . (extension_loaded('curl') ? 'Disponible' : 'NON DISPONIBLE') . "</p>";
echo "<p><strong>Extensions JSON :</strong> " . (extension_loaded('json') ? 'Disponible' : 'NON DISPONIBLE') . "</p>";
echo "<p><strong>Allow URL fopen :</strong> " . (ini_get('allow_url_fopen') ? 'Activé' : 'Désactivé') . "</p>";

echo "<h2>6. Logs d'installation :</h2>";
$logFile = __DIR__ . '/install/install_log.txt';
if (file_exists($logFile)) {
    echo "<p><strong>Fichier de log trouvé :</strong> $logFile</p>";
    $logs = file_get_contents($logFile);
    echo "<textarea style='width: 100%; height: 200px; font-family: monospace; font-size: 12px;'>";
    echo htmlspecialchars($logs);
    echo "</textarea>";
} else {
    echo "<p><strong>Fichier de log :</strong> Non trouvé à $logFile</p>";
}

echo "<hr>";
echo "<p><a href='install/install_new.php'>← Retour à l'installation originale</a></p>";
echo "<p><a href='install_fixed.php'>← Retour à l'installation corrigée</a></p>";
echo "<p><a href='test_boutons_final.php'>Test des boutons</a></p>";
?> 