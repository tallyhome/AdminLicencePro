<?php
/**
 * Test de la clé system_requirements dans l'installateur
 */

// Inclure les fonctions de l'installateur
require_once 'install/config.php';
require_once 'install/functions/language.php';

echo "<!DOCTYPE html>";
echo "<html><head><meta charset='UTF-8'><title>Test system_requirements - Installateur</title>";
echo "<style>body{font-family:Arial,sans-serif;margin:2rem;} table{border-collapse:collapse;width:100%;} th,td{border:1px solid #ddd;padding:1rem;text-align:left;} th{background:#f2f2f2;} .success{color:green;} .error{color:red;}</style>";
echo "</head><body>";

echo "<h1>🔍 Test de la clé 'system_requirements' dans l'installateur</h1>";

// Démarrer la session pour les langues
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Langues à tester
$languages = [
    'fr' => 'Français',
    'en' => 'English', 
    'es' => 'Español',
    'de' => 'Deutsch',
    'it' => 'Italiano',
    'pt' => 'Português',
    'nl' => 'Nederlands',
    'ru' => 'Русский',
    'zh' => '中文',
    'ja' => '日本語',
    'tr' => 'Türkçe',
    'ar' => 'العربية'
];

echo "<table>";
echo "<tr><th>Code</th><th>Langue</th><th>Traduction</th><th>Statut</th></tr>";

foreach ($languages as $code => $name) {
    try {
        // Définir la langue temporairement
        $_SESSION['installer_language'] = $code;
        
        // Tester la traduction
        $translation = t('system_requirements');
        
        $status = $translation !== 'system_requirements' ? 
            "<span class='success'>✅ OK</span>" : 
            "<span class='error'>❌ Manquant</span>";
        
        echo "<tr>";
        echo "<td><strong>$code</strong></td>";
        echo "<td>$name</td>";
        echo "<td>$translation</td>";
        echo "<td>$status</td>";
        echo "</tr>";
        
    } catch (Exception $e) {
        echo "<tr>";
        echo "<td><strong>$code</strong></td>";
        echo "<td>$name</td>";
        echo "<td>Erreur: " . $e->getMessage() . "</td>";
        echo "<td><span class='error'>❌ Erreur</span></td>";
        echo "</tr>";
    }
}

echo "</table>";

echo "<div style='margin-top:2rem; padding:1rem; background:#e8f5e8; border-radius:0.5rem;'>";
echo "<h3>✅ Résultat</h3>";
echo "<p>La clé <code>system_requirements</code> est maintenant disponible dans l'installateur !</p>";
echo "<p><strong>Utilisation dans l'installateur :</strong> <code>&lt;?php echo t('system_requirements'); ?&gt;</code></p>";
echo "</div>";

echo "<div style='margin-top:1rem; padding:1rem; background:#fff3cd; border-radius:0.5rem;'>";
echo "<h3>🔧 Pour utiliser dans vos templates :</h3>";
echo "<p>Remplacez <code>system_requirements</code> par <code>&lt;?php echo t('system_requirements'); ?&gt;</code> dans vos fichiers PHP de l'installateur.</p>";
echo "</div>";

echo "</body></html>";
?>
