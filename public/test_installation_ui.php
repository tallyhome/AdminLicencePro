<?php
/**
 * Test de l'interface utilisateur de l'installation
 */

// Inclure les fonctions nécessaires
require_once __DIR__ . '/install/config.php';
require_once __DIR__ . '/install/functions/language.php';
require_once __DIR__ . '/install/functions/core.php';
require_once __DIR__ . '/install/functions/ui.php';

// Démarrer la session
session_start();

// Initialiser la langue
$currentLang = initLanguage();

echo "<h1>Test de l'interface utilisateur de l'installation</h1>";

echo "<h2>1. Test d'affichage des erreurs</h2>";

// Simuler des erreurs
$errors = [
    'Clé de licence invalide',
    'Format de clé incorrect',
    'Erreur de connexion au serveur'
];

echo "<h3>Erreurs simulées:</h3>";
foreach ($errors as $error) {
    echo '<div class="alert alert-danger" style="background: #f8d7da; color: #721c24; padding: 10px; margin: 10px 0; border-radius: 4px;">' . htmlspecialchars($error) . '</div>';
}

echo "<h2>2. Test du formulaire de licence</h2>";

// Afficher le formulaire comme dans l'installation
echo '<div style="max-width: 800px; margin: 20px auto; padding: 30px; background: white; border-radius: 10px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">';

// Simuler une erreur de licence
echo '<div class="alert alert-danger" style="background: #f8d7da; color: #721c24; padding: 15px; margin-bottom: 20px; border-radius: 4px; border: 1px solid #f5c6cb;">
    ⚠️ <strong>Erreur de licence:</strong> La clé de licence fournie n\'est pas valide
</div>';

echo '<div class="license-info" style="background: #e3f2fd; padding: 20px; border-radius: 8px; margin-bottom: 30px;">
    <h3 style="color: #1976d2; margin-top: 0;">Vérification de licence</h3>
    <p><strong>Information:</strong> Veuillez entrer votre clé de licence pour continuer l\'installation</p>
    <p><strong>API de licence:</strong> <code>https://licence.myvcard.fr</code></p>
</div>

<form method="post" action="install_new.php" data-step="1" class="install-form">
    <input type="hidden" name="step" value="1">
    
    <div class="form-group" style="margin-bottom: 20px;">
        <label for="serial_key" style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">Clé de licence</label>
        <input type="text" 
               id="serial_key" 
               name="serial_key" 
               required 
               placeholder="FCGI-WC2S-H3PE-QJQG" 
               pattern="[A-Za-z0-9]{4}-[A-Za-z0-9]{4}-[A-Za-z0-9]{4}-[A-Za-z0-9]{4}" 
               value="JQUV-QSDM-UT8G-BFHY"
               style="width: 100%; padding: 12px; border: 2px solid #e1e5e9; border-radius: 6px; font-size: 16px; text-transform: uppercase;">
        <small style="color: #666; font-size: 0.9em; display: block; margin-top: 5px;">Entrez la clé de licence fournie lors de votre achat</small>
    </div>
    
    <div class="form-actions" style="display: flex; justify-content: space-between; align-items: center; margin-top: 30px;">
        <div></div>
        <button type="submit" class="btn btn-verify" style="background: #28a745; color: white; padding: 12px 24px; border: none; border-radius: 6px; font-size: 16px; font-weight: 600; cursor: pointer; transition: background-color 0.2s;">
            Vérifier la licence
        </button>
    </div>
</form>';

echo '</div>';

echo "<h2>3. Test de simulation d'une soumission avec erreur</h2>";

// Simuler exactement ce qui se passe dans install_new.php
$_POST['step'] = 1;
$_POST['serial_key'] = 'INVALID-KEY-TEST-1234';

echo "<p><strong>Clé testée:</strong> " . $_POST['serial_key'] . "</p>";

// Vérifier la licence
$domain = 'localhost';
$ipAddress = '127.0.0.1';
$licenseCheck = verifierLicence($_POST['serial_key'], $domain, $ipAddress);

echo "<p><strong>Résultat:</strong></p>";
echo "<pre>" . json_encode($licenseCheck, JSON_PRETTY_PRINT) . "</pre>";

if (!$licenseCheck['valide']) {
    echo '<div class="alert alert-danger" style="background: #f8d7da; color: #721c24; padding: 15px; margin: 20px 0; border-radius: 4px; border: 1px solid #f5c6cb;">
        ❌ <strong>Licence invalide:</strong> ' . htmlspecialchars($licenseCheck['message']) . '
    </div>';
} else {
    echo '<div class="alert alert-success" style="background: #d4edda; color: #155724; padding: 15px; margin: 20px 0; border-radius: 4px; border: 1px solid #c3e6cb;">
        ✅ <strong>Licence valide !</strong>
    </div>';
}

echo "<h2>4. Lien vers l'installation réelle</h2>";
echo '<div style="text-align: center; margin: 30px 0;">
    <a href="public/install/install_new.php" 
       style="display: inline-block; background: #007bff; color: white; padding: 15px 30px; text-decoration: none; border-radius: 6px; font-weight: 600; font-size: 16px;">
        🚀 Aller à l\'installation réelle
    </a>
</div>';

?>

<style>
body { 
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; 
    line-height: 1.6; 
    margin: 0; 
    padding: 20px; 
    background: #f8f9fa; 
    color: #333; 
}

h1, h2, h3 { 
    color: #2c3e50; 
    margin-bottom: 20px; 
}

pre { 
    background: #f8f9fa; 
    padding: 15px; 
    border-radius: 6px; 
    overflow-x: auto; 
    border: 1px solid #e9ecef;
}

.btn:hover {
    background: #218838 !important;
    transform: translateY(-1px);
}

.form-input:focus {
    border-color: #007bff !important;
    box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1) !important;
}
</style> 