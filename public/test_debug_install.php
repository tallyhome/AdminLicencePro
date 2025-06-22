<?php
/**
 * Diagnostic des problèmes de l'installateur AdminLicence
 * Date: 2025-01-21
 */

session_start();

echo "<h1>🔍 Diagnostic des problèmes de l'installateur</h1>";

// Inclure les fonctions nécessaires
define('INSTALL_PATH', __DIR__ . '/install');
define('ROOT_PATH', __DIR__);

require_once INSTALL_PATH . '/config.php';
require_once INSTALL_PATH . '/functions/language.php';
require_once INSTALL_PATH . '/functions/core.php';

echo "<h2>1. 🌐 Test du sélecteur de langue</h2>";

echo "<h3>Variables de session actuelles :</h3>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

echo "<h3>Paramètres GET :</h3>";
echo "<pre>";
print_r($_GET);
echo "</pre>";

echo "<h3>Test des fonctions de langue :</h3>";

// Tester le changement de langue
if (isset($_GET['test_lang'])) {
    $_SESSION['installer_language'] = $_GET['test_lang'];
    echo "<p><strong>✅ Langue forcée à :</strong> " . $_GET['test_lang'] . "</p>";
}

$currentLang = getCurrentLanguage();
echo "<p><strong>Langue actuelle :</strong> $currentLang</p>";

echo "<p><strong>Nom de la langue :</strong> " . getCurrentLanguageName() . "</p>";

echo "<h3>Test des traductions :</h3>";
echo "<p><strong>license_key (FR) :</strong> " . t('license_key') . "</p>";

// Changer vers l'anglais pour tester
$_SESSION['installer_language'] = 'en';
echo "<p><strong>license_key (EN) :</strong> " . t('license_key') . "</p>";

// Revenir au français
$_SESSION['installer_language'] = 'fr';

echo "<h3>Test des liens de langue :</h3>";
echo "<div style='border: 1px solid #ccc; padding: 10px;'>";
echo getLanguageLinks();
echo "</div>";

echo "<h3>Test des titres d'étapes :</h3>";
echo "<p><strong>Étape 1 FR :</strong> " . getStepTitle(1) . "</p>";
$_SESSION['installer_language'] = 'en';
echo "<p><strong>Étape 1 EN :</strong> " . getStepTitle(1) . "</p>";
$_SESSION['installer_language'] = 'fr';

echo "<hr>";

echo "<h2>2. 🔐 Test de validation de licence</h2>";

echo "<h3>Test avec clé valide (JQUV-QSDM-UT8G-BFHY) :</h3>";
$testKey = 'JQUV-QSDM-UT8G-BFHY';
$domain = $_SERVER['HTTP_HOST'] ?? 'localhost';
$ipAddress = $_SERVER['SERVER_ADDR'] ?? $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';

echo "<p><strong>Clé :</strong> $testKey</p>";
echo "<p><strong>Domaine :</strong> $domain</p>";
echo "<p><strong>IP :</strong> $ipAddress</p>";

$result = verifierLicence($testKey, $domain, $ipAddress);

echo "<h4>Résultat :</h4>";
echo "<pre>";
print_r($result);
echo "</pre>";

if ($result['valide']) {
    echo "<p style='color: green;'>✅ <strong>LICENCE VALIDE</strong></p>";
} else {
    echo "<p style='color: red;'>❌ <strong>LICENCE INVALIDE</strong> : " . $result['message'] . "</p>";
}

echo "<h3>Test avec clé invalide (ABCD-EFGH-IJKL-MNOP) :</h3>";
$invalidKey = 'ABCD-EFGH-IJKL-MNOP';

echo "<p><strong>Clé :</strong> $invalidKey</p>";

$result2 = verifierLicence($invalidKey, $domain, $ipAddress);

echo "<h4>Résultat :</h4>";
echo "<pre>";
print_r($result2);
echo "</pre>";

if (!$result2['valide']) {
    echo "<p style='color: green;'>✅ <strong>LICENCE CORRECTEMENT REJETÉE</strong></p>";
} else {
    echo "<p style='color: red;'>❌ <strong>PROBLÈME : LICENCE INVALIDE ACCEPTÉE</strong></p>";
}

echo "<hr>";

echo "<h2>3. 📋 Logs de l'installateur</h2>";

$logFile = INSTALL_PATH . '/install_log.txt';
if (file_exists($logFile)) {
    echo "<h3>Dernières entrées du log :</h3>";
    $logContent = file_get_contents($logFile);
    $logLines = explode("\n", $logContent);
    $lastLines = array_slice($logLines, -20); // 20 dernières lignes
    
    echo "<pre style='background: #f8f9fa; padding: 15px; border-radius: 4px; max-height: 400px; overflow-y: auto;'>";
    foreach ($lastLines as $line) {
        if (!empty(trim($line))) {
            echo htmlspecialchars($line) . "\n";
        }
    }
    echo "</pre>";
} else {
    echo "<p>❌ Aucun fichier de log trouvé</p>";
}

echo "<hr>";

echo "<h2>4. 🔧 Tests de l'interface</h2>";

echo "<h3>Test des liens de changement de langue :</h3>";
echo "<p><a href='?test_lang=fr' style='color: #007bff; text-decoration: none; margin-right: 10px;'>🇫🇷 Français</a></p>";
echo "<p><a href='?test_lang=en' style='color: #007bff; text-decoration: none; margin-right: 10px;'>🇬🇧 English</a></p>";

echo "<h3>Test de l'installateur :</h3>";
echo "<p><a href='install/install_new.php' target='_blank' style='color: #007bff; text-decoration: none;'>🔗 Ouvrir l'installateur dans un nouvel onglet</a></p>";
echo "<p><a href='install/install_new.php?language=en' target='_blank' style='color: #007bff; text-decoration: none;'>🔗 Ouvrir l'installateur en anglais</a></p>";

echo "<hr>";

echo "<h2>📊 Résumé du diagnostic</h2>";

echo "<div style='background: #f8f9fa; padding: 20px; border-radius: 5px; margin: 20px 0;'>";
echo "<h3>🔍 Problèmes détectés :</h3>";

// Vérifier si le sélecteur de langue fonctionne
$langTest = true;
$_SESSION['installer_language'] = 'en';
$titleEn = getStepTitle(1);
$_SESSION['installer_language'] = 'fr';
$titleFr = getStepTitle(1);

if ($titleEn === $titleFr) {
    echo "<p>❌ <strong>Sélecteur de langue :</strong> Les traductions ne changent pas</p>";
    $langTest = false;
} else {
    echo "<p>✅ <strong>Sélecteur de langue :</strong> Les traductions fonctionnent</p>";
}

// Vérifier la validation de licence
if ($result['valide'] && !$result2['valide']) {
    echo "<p>✅ <strong>Validation de licence :</strong> Fonctionne correctement</p>";
} else {
    echo "<p>❌ <strong>Validation de licence :</strong> Problème détecté</p>";
}

echo "</div>";

echo "<h2>🔧 Actions correctives</h2>";

if (!$langTest) {
    echo "<div style='background: #fff3cd; padding: 15px; border-radius: 4px; margin: 10px 0;'>";
    echo "<h4>Problème de langue :</h4>";
    echo "<p>Le sélecteur de langue ne fonctionne pas. Causes possibles :</p>";
    echo "<ul>";
    echo "<li>Les liens de langue ne modifient pas la session correctement</li>";
    echo "<li>La fonction getCurrentLanguage() ne lit pas la session</li>";
    echo "<li>Les traductions ne sont pas chargées</li>";
    echo "</ul>";
    echo "</div>";
}

echo "<p style='margin-top: 30px;'>";
echo "<a href='install/install_new.php' style='background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px;'>🚀 Tester l'installateur maintenant</a>";
echo "</p>";

?> 