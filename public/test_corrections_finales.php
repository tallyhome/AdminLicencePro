<?php
/**
 * Test final de toutes les corrections apportées
 * Date: 2025-01-21
 */

echo "<h1>🔧 Test des corrections finales - AdminLicence 4.5.1</h1>";

echo "<h2>1. Test des traductions (Messages d'alerte)</h2>";

// Test simple des traductions sans Laravel
try {
    echo "<h3>🌐 Test lecture directe des fichiers de traduction</h3>";
    
    $languages = ['fr', 'en', 'nl', 'ru', 'zh', 'ja'];
    
    foreach ($languages as $lang) {
        echo "<h4>Langue : $lang</h4>";
        
        $translationFile = __DIR__ . "/../resources/locales/$lang/translation.json";
        
        if (file_exists($translationFile)) {
            $content = file_get_contents($translationFile);
            $translations = json_decode($content, true);
            
            if (isset($translations['common']['language_changed_successfully'])) {
                $message = $translations['common']['language_changed_successfully'];
                echo "<p><strong>Traduction trouvée :</strong> $message</p>";
                echo "<p style='color: green;'>✅ Fichier de traduction OK</p>";
            } else {
                echo "<p style='color: red;'>❌ Clé 'language_changed_successfully' manquante</p>";
            }
        } else {
            echo "<p style='color: red;'>❌ Fichier de traduction manquant</p>";
        }
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Erreur lecture traductions : " . $e->getMessage() . "</p>";
}

echo "<hr>";

echo "<h2>2. Test des fonctions de l'installateur</h2>";

// Test des fonctions de l'installateur
define('INSTALL_PATH', __DIR__ . '/install');
define('ROOT_PATH', __DIR__);

try {
    require_once INSTALL_PATH . '/config.php';
    require_once INSTALL_PATH . '/functions/language.php';
    require_once INSTALL_PATH . '/functions/core.php';
    
    echo "<h3>🔧 Fonctions disponibles</h3>";
    echo "<p><strong>initLanguage :</strong> " . (function_exists('initLanguage') ? '✅ Disponible' : '❌ Manquante') . "</p>";
    echo "<p><strong>getCurrentLanguage :</strong> " . (function_exists('getCurrentLanguage') ? '✅ Disponible' : '❌ Manquante') . "</p>";
    echo "<p><strong>t :</strong> " . (function_exists('t') ? '✅ Disponible' : '❌ Manquante') . "</p>";
    echo "<p><strong>getStepTitle :</strong> " . (function_exists('getStepTitle') ? '✅ Disponible' : '❌ Manquante') . "</p>";
    echo "<p><strong>getStepDescription :</strong> " . (function_exists('getStepDescription') ? '✅ Disponible' : '❌ Manquante') . "</p>";
    echo "<p><strong>verifierLicence :</strong> " . (function_exists('verifierLicence') ? '✅ Disponible' : '❌ Manquante') . "</p>";
    
    echo "<h3>🌐 Test des langues installateur</h3>";
    
    // Simuler le changement de langue
    $_SESSION['installer_language'] = 'fr';
    $currentLang = getCurrentLanguage();
    echo "<p><strong>Langue FR :</strong> $currentLang</p>";
    echo "<p><strong>Titre étape 1 FR :</strong> " . getStepTitle(1) . "</p>";
    echo "<p><strong>Description étape 1 FR :</strong> " . getStepDescription(1) . "</p>";
    
    $_SESSION['installer_language'] = 'en';
    $currentLang = getCurrentLanguage();
    echo "<p><strong>Langue EN :</strong> $currentLang</p>";
    echo "<p><strong>Titre étape 1 EN :</strong> " . getStepTitle(1) . "</p>";
    echo "<p><strong>Description étape 1 EN :</strong> " . getStepDescription(1) . "</p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Erreur installateur : " . $e->getMessage() . "</p>";
}

echo "<hr>";

echo "<h2>3. Test de validation de licence</h2>";

if (function_exists('verifierLicence')) {
    echo "<h3>🔐 Test avec clé valide</h3>";
    
    $testKey = 'JQUV-QSDM-UT8G-BFHY';
    $domain = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $ipAddress = $_SERVER['SERVER_ADDR'] ?? $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
    
    echo "<p><strong>Clé testée :</strong> $testKey</p>";
    echo "<p><strong>Domaine :</strong> $domain</p>";
    echo "<p><strong>IP :</strong> $ipAddress</p>";
    
    $result = verifierLicence($testKey, $domain, $ipAddress);
    
    echo "<p><strong>Résultat :</strong></p>";
    echo "<pre>";
    print_r($result);
    echo "</pre>";
    
    if ($result['valide']) {
        echo "<p style='color: green;'>✅ <strong>LICENCE VALIDE</strong></p>";
        echo "<p>✅ La validation fonctionne correctement</p>";
    } else {
        echo "<p style='color: red;'>❌ <strong>LICENCE INVALIDE</strong> : " . $result['message'] . "</p>";
    }
    
    echo "<h3>🔐 Test avec clé invalide</h3>";
    
    $invalidKey = 'ABCD-EFGH-IJKL-MNOP';
    $result2 = verifierLicence($invalidKey, $domain, $ipAddress);
    
    echo "<p><strong>Clé testée :</strong> $invalidKey</p>";
    echo "<p><strong>Résultat :</strong> " . ($result2['valide'] ? 'Valide' : 'Invalide') . "</p>";
    
    if (!$result2['valide']) {
        echo "<p style='color: green;'>✅ La validation rejette correctement les clés invalides</p>";
    } else {
        echo "<p style='color: red;'>❌ PROBLÈME : La validation accepte des clés invalides</p>";
    }
    
} else {
    echo "<p style='color: red;'>❌ Fonction verifierLicence non disponible</p>";
}

echo "<hr>";

echo "<h2>4. Test de comparaison avec install_ultra_simple.php</h2>";

echo "<h3>🔍 Analyse du problème de validation</h3>";
echo "<p>Comparaison entre install_new.php et install_ultra_simple.php :</p>";

// Lire le contenu d'install_ultra_simple.php pour voir la différence
$ultraSimpleFile = __DIR__ . '/install_ultra_simple.php';
if (file_exists($ultraSimpleFile)) {
    $content = file_get_contents($ultraSimpleFile);
    
    // Chercher la partie validation de licence
    if (strpos($content, 'verifierLicence') !== false) {
        echo "<p style='color: green;'>✅ install_ultra_simple.php utilise bien verifierLicence()</p>";
    } else {
        echo "<p style='color: red;'>❌ install_ultra_simple.php n'utilise pas verifierLicence()</p>";
    }
    
    // Vérifier la logique de validation
    if (strpos($content, '$licenseCheck[\'valide\']') !== false) {
        echo "<p style='color: green;'>✅ install_ultra_simple.php vérifie bien le résultat de validation</p>";
    }
} else {
    echo "<p style='color: red;'>❌ Fichier install_ultra_simple.php non trouvé</p>";
}

echo "<hr>";

echo "<h2>📋 Résumé des corrections</h2>";

echo "<div style='background: #f8f9fa; padding: 20px; border-radius: 5px; margin: 20px 0;'>";
echo "<h3>✅ Corrections appliquées :</h3>";
echo "<ol>";
echo "<li><strong>Messages d'alerte multilingues :</strong> Utilisation du TranslationService dans LanguageController</li>";
echo "<li><strong>Traductions ajoutées :</strong> 'language_changed_successfully' en FR et EN</li>";
echo "<li><strong>Sélecteur de langue installateur :</strong> Utilisation des fonctions getStepTitle() et getStepDescription()</li>";
echo "<li><strong>Interface installateur traduite :</strong> Utilisation de la fonction t() dans displayInstallationForm()</li>";
echo "<li><strong>Champ licence vierge :</strong> Suppression de la valeur pré-remplie</li>";
echo "<li><strong>Validation de licence :</strong> Utilisation de la fonction verifierLicence() réelle</li>";
echo "</ol>";
echo "</div>";

echo "<div style='background: #fff3cd; padding: 20px; border-radius: 5px; margin: 20px 0;'>";
echo "<h3>⚠️ Problèmes détectés :</h3>";
echo "<ul>";
echo "<li>❌ Messages d'alerte pas dans la bonne langue (ES → EN)</li>";
echo "<li>❌ Sélecteur de langue installateur non fonctionnel</li>";
echo "<li>❌ Validation de licence accepte les clés invalides</li>";
echo "</ul>";
echo "</div>";

echo "<h2>🚀 Tests recommandés</h2>";
echo "<ol>";
echo "<li><strong>Test changement de langue :</strong> <a href='/admin/login' target='_blank'>Aller à la page de connexion admin</a></li>";
echo "<li><strong>Test installateur :</strong> <a href='/install/install_new.php?language=en' target='_blank'>Tester l'installateur en anglais</a></li>";
echo "<li><strong>Test validation licence :</strong> Utiliser l'installateur avec différentes clés</li>";
echo "</ol>";

?> 