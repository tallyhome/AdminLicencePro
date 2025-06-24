<?php
/**
 * Test pour vérifier que le contrôleur de licence utilise la nouvelle détection d'IP
 */

// Simuler l'environnement Laravel minimal
require_once __DIR__ . '/../vendor/autoload.php';

// Inclure notre helper IP
require_once __DIR__ . '/../app/Helpers/IPHelper.php';

echo "<!DOCTYPE html>
<html>
<head>
    <title>Test Contrôleur Licence - Détection IP</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background-color: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; }
        .section { margin: 20px 0; padding: 20px; border: 1px solid #ddd; border-radius: 8px; background: white; }
        .success { background-color: #d4edda; border-color: #c3e6cb; }
        .warning { background-color: #fff3cd; border-color: #ffeaa7; }
        .error { background-color: #f8d7da; border-color: #f5c6cb; }
        .info { background-color: #d1ecf1; border-color: #bee5eb; }
        .code { background-color: #f8f9fa; padding: 15px; border-radius: 5px; font-family: monospace; font-size: 12px; overflow-x: auto; }
        h1 { color: #333; text-align: center; }
        h2 { color: #2196F3; border-bottom: 2px solid #2196F3; padding-bottom: 5px; }
        .status-ok { color: #4CAF50; font-weight: bold; }
        .status-warning { color: #FF9800; font-weight: bold; }
        .status-error { color: #f44336; font-weight: bold; }
        .result-box { border: 3px solid #4CAF50; border-radius: 10px; padding: 20px; margin: 20px 0; background-color: #f0f8f0; }
    </style>
</head>
<body>
    <div class='container'>
        <h1>🔍 Test Contrôleur Licence - Détection IP</h1>
        <p style='text-align: center; color: #666;'>Vérification que le contrôleur utilise la nouvelle détection d'IP robuste</p>";

// TEST 1: Vérifier que la classe IPHelper est accessible
echo "<div class='section'>
    <h2>📦 Test 1: Accessibilité de la Classe IPHelper</h2>";

try {
    if (class_exists('App\\Helpers\\IPHelper')) {
        echo "<div class='success'>✅ Classe App\\Helpers\\IPHelper trouvée</div>";
        
        // Tester la méthode collectServerIPRobust
        if (method_exists('App\\Helpers\\IPHelper', 'collectServerIPRobust')) {
            echo "<div class='success'>✅ Méthode collectServerIPRobust disponible</div>";
            
            // Tester l'appel
            $ipInfo = \App\Helpers\IPHelper::collectServerIPRobust(false);
            echo "<div class='code'>Résultat de collectServerIPRobust():\n" . json_encode($ipInfo, JSON_PRETTY_PRINT) . "</div>";
            
            if (!empty($ipInfo['ip']) && $ipInfo['ip'] !== '127.0.0.1') {
                echo "<div class='success'>✅ IP publique détectée: {$ipInfo['ip']}</div>";
            } else {
                echo "<div class='warning'>⚠️ IP locale détectée: {$ipInfo['ip']}</div>";
            }
        } else {
            echo "<div class='error'>❌ Méthode collectServerIPRobust non trouvée</div>";
        }
    } else {
        echo "<div class='error'>❌ Classe App\\Helpers\\IPHelper non trouvée</div>";
    }
} catch (Exception $e) {
    echo "<div class='error'>❌ Erreur: " . $e->getMessage() . "</div>";
}

echo "</div>";

// TEST 2: Vérifier le contenu du contrôleur
echo "<div class='section'>
    <h2>📄 Test 2: Analyse du Contrôleur de Licence</h2>";

$controllerPath = __DIR__ . '/../app/Http/Controllers/Admin/LicenseController.php';
if (file_exists($controllerPath)) {
    $controllerContent = file_get_contents($controllerPath);
    
    // Vérifier les imports
    $hasIPHelperImport = strpos($controllerContent, 'use App\\Helpers\\IPHelper;') !== false;
    echo "<div class='" . ($hasIPHelperImport ? 'success' : 'error') . "'>";
    echo ($hasIPHelperImport ? '✅' : '❌') . " Import IPHelper: " . ($hasIPHelperImport ? 'Présent' : 'Manquant');
    echo "</div>";
    
    // Vérifier les appels à collectServerIPRobust
    $robustCalls = substr_count($controllerContent, 'IPHelper::collectServerIPRobust');
    echo "<div class='" . ($robustCalls > 0 ? 'success' : 'error') . "'>";
    echo ($robustCalls > 0 ? '✅' : '❌') . " Appels à collectServerIPRobust: $robustCalls";
    echo "</div>";
    
    // Vérifier les anciens appels avec IP vide
    $emptyIPCalls = substr_count($controllerContent, "\$ipAddress = '';");
    echo "<div class='" . ($emptyIPCalls == 0 ? 'success' : 'warning') . "'>";
    echo ($emptyIPCalls == 0 ? '✅' : '⚠️') . " Anciens appels IP vide: $emptyIPCalls";
    echo "</div>";
    
    // Vérifier les logs de diagnostic
    $logCalls = substr_count($controllerContent, 'LICENCE CONTROLLER');
    echo "<div class='" . ($logCalls > 0 ? 'success' : 'warning') . "'>";
    echo ($logCalls > 0 ? '✅' : '⚠️') . " Logs de diagnostic: $logCalls";
    echo "</div>";
    
} else {
    echo "<div class='error'>❌ Fichier contrôleur non trouvé</div>";
}

echo "</div>";

// TEST 3: Simulation d'appel de contrôleur
echo "<div class='section'>
    <h2>🎮 Test 3: Simulation d'Appel de Contrôleur</h2>";

try {
    // Simuler la logique du contrôleur
    echo "<div class='code'>Simulation de la logique du contrôleur:\n\n";
    
    // Étape 1: Récupérer le domaine
    $domain = $_SERVER['HTTP_HOST'] ?? 'localhost';
    echo "1. Domaine détecté: $domain\n";
    
    // Étape 2: Utiliser notre nouvelle détection d'IP
    if (class_exists('App\\Helpers\\IPHelper')) {
        $ipInfo = \App\Helpers\IPHelper::collectServerIPRobust(false);
        $ipAddress = $ipInfo['ip'];
        echo "2. IP détectée: $ipAddress\n";
        echo "3. Confiance: {$ipInfo['confidence']}%\n";
        echo "4. Raison: {$ipInfo['reason']}\n";
        echo "5. Type: " . ($ipInfo['is_local'] ? 'Locale' : 'Publique') . "\n";
        
        // Étape 3: Simuler les données qui seraient envoyées
        $simulatedData = [
            'serial_key' => 'TEST-XXXX-XXXX-XXXX',
            'domain' => $domain,
            'ip_address' => $ipAddress,
            'validation_mode' => 'domain_and_ip_robust'
        ];
        
        echo "\n6. Données qui seraient envoyées au serveur de licence:\n";
        echo json_encode($simulatedData, JSON_PRETTY_PRINT);
        
    } else {
        echo "ERREUR: Classe IPHelper non disponible\n";
    }
    
    echo "</div>";
    
} catch (Exception $e) {
    echo "<div class='error'>❌ Erreur lors de la simulation: " . $e->getMessage() . "</div>";
}

echo "</div>";

// TEST 4: Comparaison avant/après
echo "<div class='section'>
    <h2>📊 Test 4: Comparaison Avant/Après</h2>";

echo "<table style='width: 100%; border-collapse: collapse;'>
    <tr style='background-color: #f2f2f2;'>
        <th style='border: 1px solid #ddd; padding: 8px;'>Aspect</th>
        <th style='border: 1px solid #ddd; padding: 8px;'>Avant (Problème)</th>
        <th style='border: 1px solid #ddd; padding: 8px;'>Après (Correction)</th>
    </tr>
    <tr>
        <td style='border: 1px solid #ddd; padding: 8px;'>Détection IP</td>
        <td style='border: 1px solid #ddd; padding: 8px; color: #f44336;'>\$ipAddress = '' (vide)</td>
        <td style='border: 1px solid #ddd; padding: 8px; color: #4CAF50;'>IPHelper::collectServerIPRobust()</td>
    </tr>
    <tr>
        <td style='border: 1px solid #ddd; padding: 8px;'>IP transmise</td>
        <td style='border: 1px solid #ddd; padding: 8px; color: #f44336;'>Chaîne vide ou 'Non spécifiée'</td>
        <td style='border: 1px solid #ddd; padding: 8px; color: #4CAF50;'>";

if (class_exists('App\\Helpers\\IPHelper')) {
    $testIP = \App\Helpers\IPHelper::collectServerIPRobust(false);
    echo $testIP['ip'] . " (confiance: {$testIP['confidence']}%)";
} else {
    echo "IPHelper non disponible";
}

echo "</td>
    </tr>
    <tr>
        <td style='border: 1px solid #ddd; padding: 8px;'>Stratégies</td>
        <td style='border: 1px solid #ddd; padding: 8px; color: #f44336;'>Aucune stratégie</td>
        <td style='border: 1px solid #ddd; padding: 8px; color: #4CAF50;'>9 stratégies + services externes</td>
    </tr>
    <tr>
        <td style='border: 1px solid #ddd; padding: 8px;'>Logs</td>
        <td style='border: 1px solid #ddd; padding: 8px; color: #f44336;'>Aucun log spécifique</td>
        <td style='border: 1px solid #ddd; padding: 8px; color: #4CAF50;'>Logs détaillés avec diagnostic</td>
    </tr>
</table>";

echo "</div>";

// RÉSULTAT FINAL
echo "<div class='result-box'>
    <h2 style='color: #4CAF50; text-align: center; margin-top: 0;'>🎉 RÉSULTAT DU TEST</h2>";

$allGood = true;
$issues = [];

// Vérifier les conditions
if (!class_exists('App\\Helpers\\IPHelper')) {
    $allGood = false;
    $issues[] = "Classe IPHelper non accessible";
}

if (!file_exists($controllerPath)) {
    $allGood = false;
    $issues[] = "Contrôleur non trouvé";
} else {
    $content = file_get_contents($controllerPath);
    if (strpos($content, 'use App\\Helpers\\IPHelper;') === false) {
        $allGood = false;
        $issues[] = "Import IPHelper manquant";
    }
    if (substr_count($content, 'IPHelper::collectServerIPRobust') < 3) {
        $allGood = false;
        $issues[] = "Appels collectServerIPRobust insuffisants";
    }
}

if ($allGood) {
    echo "<div class='status-ok' style='text-align: center; font-size: 18px;'>
        ✅ CORRECTION APPLIQUÉE AVEC SUCCÈS<br>
        Le contrôleur utilise maintenant la détection IP robuste
    </div>";
} else {
    echo "<div class='status-error' style='text-align: center; font-size: 18px;'>
        ❌ PROBLÈMES DÉTECTÉS<br>
        " . implode('<br>', $issues) . "
    </div>";
}

echo "<div style='text-align: center; margin-top: 20px;'>
    <strong>Prochaine étape:</strong> Tester la page /admin/settings/license<br>
    <strong>URL de test:</strong> <a href='http://127.0.0.1:8000/admin/settings/license' target='_blank'>http://127.0.0.1:8000/admin/settings/license</a>
</div>";

echo "</div>";

echo "<div class='section info'>
    <h2>📋 Instructions de Test</h2>
    <ol>
        <li><strong>Accéder à la page de licence:</strong> http://127.0.0.1:8000/admin/settings/license</li>
        <li><strong>Entrer une clé de licence:</strong> Utiliser une clé de test valide</li>
        <li><strong>Vérifier les logs:</strong> Consulter storage/logs/laravel.log pour voir les logs 'LICENCE CONTROLLER'</li>
        <li><strong>Confirmer l'IP:</strong> Vérifier que l'IP publique est maintenant transmise au serveur</li>
    </ol>
</div>";

echo "</div></body></html>";
?>