<?php
/**
 * Test direct du processus d'installation
 */

// Inclure les fonctions nécessaires
require_once __DIR__ . '/install/config.php';
require_once __DIR__ . '/install/functions/core.php';
require_once __DIR__ . '/install/functions/language.php';

// Démarrer la session
session_start();

// Initialiser la langue
$currentLang = initLanguage();

echo "<h1>Test direct de l'installation</h1>";

// Simuler une soumission de formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "<h2>Traitement du formulaire</h2>";
    
    $serial_key = $_POST['serial_key'] ?? '';
    echo "<p><strong>Clé reçue:</strong> $serial_key</p>";
    
    if (empty($serial_key)) {
        echo "<p class='error'>❌ Clé de licence non fournie</p>";
    } else {
        // Vérifier la licence
        $domain = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $ipAddress = $_SERVER['SERVER_ADDR'] ?? '127.0.0.1';
        
        echo "<p><strong>Domaine:</strong> $domain</p>";
        echo "<p><strong>IP:</strong> $ipAddress</p>";
        
        $licenseCheck = verifierLicence($serial_key, $domain, $ipAddress);
        
        echo "<p><strong>Résultat de la vérification:</strong></p>";
        echo "<pre>" . json_encode($licenseCheck, JSON_PRETTY_PRINT) . "</pre>";
        
        if (!$licenseCheck['valide']) {
            echo "<p class='error'>❌ Licence invalide: " . $licenseCheck['message'] . "</p>";
        } else {
            echo "<p class='success'>✅ Licence valide !</p>";
            echo "<p><strong>Données:</strong> " . json_encode($licenseCheck['donnees']) . "</p>";
            
            // Stocker en session
            $_SESSION['license_data'] = $licenseCheck['donnees'];
            $_SESSION['license_key'] = $serial_key;
            $_SESSION['license_valid'] = true;
            
            echo "<p class='success'>✅ Données stockées en session</p>";
        }
    }
    
    echo "<hr>";
}

?>

<h2>Formulaire de test</h2>
<form method="POST" action="">
    <div style="margin-bottom: 15px;">
        <label for="serial_key">Clé de licence:</label><br>
        <input type="text" 
               id="serial_key" 
               name="serial_key" 
               value="JQUV-QSDM-UT8G-BFHY" 
               placeholder="XXXX-XXXX-XXXX-XXXX"
               style="width: 300px; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
    </div>
    
    <button type="submit" style="padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer;">
        Vérifier la licence
    </button>
</form>

<h2>Session actuelle</h2>
<pre><?php print_r($_SESSION); ?></pre>

<h2>Logs récents</h2>
<?php
$logFile = __DIR__ . '/install/install_log.txt';
if (file_exists($logFile)) {
    $logs = file_get_contents($logFile);
    echo "<pre>" . htmlspecialchars(substr($logs, -1000)) . "</pre>";
} else {
    echo "<p>Fichier de log non trouvé</p>";
}
?>

<style>
body { font-family: Arial, sans-serif; margin: 20px; }
h1, h2 { color: #333; }
pre { background: #f5f5f5; padding: 10px; border-radius: 5px; overflow-x: auto; }
.error { color: red; font-weight: bold; }
.success { color: green; font-weight: bold; }
form { background: #f9f9f9; padding: 20px; border-radius: 5px; max-width: 500px; }
</style> 