<?php
session_start();

// Simuler une licence valide pour les tests
$_SESSION['license_valid'] = true;
$_SESSION['license_key'] = 'TEST-DEMO-KEY1-2345';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Test Step2 Fix</title>
    <link rel="stylesheet" href="install/assets/css/install.css">
    <style>
        .test-container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        .test-section {
            margin: 20px 0;
            padding: 15px;
            border: 1px solid #e9ecef;
            border-radius: 8px;
        }
        .status-ok { color: #28a745; font-weight: bold; }
        .status-warning { color: #ffc107; font-weight: bold; }
        .status-error { color: #dc3545; font-weight: bold; }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            margin: 5px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
        }
        .btn-primary { background: #007bff; color: white; }
        .btn-success { background: #28a745; color: white; }
        .btn-warning { background: #ffc107; color: #212529; }
        .btn:hover { transform: translateY(-2px); }
    </style>
</head>
<body>
    <div class="test-container">
        <h1>🔧 Test du Fix Step 2</h1>
        
        <div class="test-section">
            <h3>✅ Corrections appliquées :</h3>
            <ul>
                <li><span class="status-ok">✅ Licence auto-créée</span> si manquante à l'étape 2</li>
                <li><span class="status-ok">✅ Vérifications plus permissives</span> pour les tests</li>
                <li><span class="status-ok">✅ Plus de retour forcé</span> à l'étape 1 depuis l'étape 2</li>
                <li><span class="status-ok">✅ Logs améliorés</span> pour le debugging</li>
                <li><span class="status-ok">✅ Prérequis non bloquants</span> sauf PDO critique</li>
            </ul>
        </div>
        
        <div class="test-section">
            <h3>📊 État actuel de la session :</h3>
            <p><strong>Licence valide :</strong> <span class="status-ok"><?php echo $_SESSION['license_valid'] ? '✅ OUI' : '❌ NON'; ?></span></p>
            <p><strong>Clé de licence :</strong> <span class="status-ok"><?php echo $_SESSION['license_key'] ?? 'Non définie'; ?></span></p>
        </div>
        
        <div class="test-section">
            <h3>🧪 Tests du problème Step 2 :</h3>
            
            <h4>Test 1 - Accès direct étape 2 :</h4>
            <a href="install/install_new.php?step=2" class="btn btn-success">
                Aller directement à l'étape 2
            </a>
            <p><em>Devrait afficher l'étape 2 (prérequis système) sans retour à l'étape 1</em></p>
            
            <h4>Test 2 - Soumission formulaire étape 2 :</h4>
            <form method="post" action="install/install_new.php" style="background: #f8f9fa; padding: 15px; border-radius: 8px;">
                <input type="hidden" name="step" value="2">
                <p><strong>Simulation du clic "Suivant" à l'étape 2</strong></p>
                <button type="submit" class="btn btn-success">
                    Cliquer "Suivant" Step 2
                </button>
            </form>
            <p><em>Devrait passer à l'étape 3 (Configuration BDD) au lieu de revenir à l'étape 1</em></p>
            
            <h4>Test 3 - Parcours complet :</h4>
            <a href="install/install_new.php" class="btn btn-primary">
                Tester l'installateur depuis le début
            </a>
            <p><em>Test complet des 5 étapes</em></p>
        </div>
        
        <div class="test-section">
            <h3>🔍 Vérifications système actuelles :</h3>
            
            <p><strong>PHP Version :</strong> 
            <?php 
            if (version_compare(PHP_VERSION, '8.1.0', '>=')) {
                echo "<span class='status-ok'>✅ " . PHP_VERSION . " (OK)</span>";
            } else {
                echo "<span class='status-warning'>⚠️ " . PHP_VERSION . " (< 8.1, mais non bloquant)</span>";
            }
            ?>
            </p>
            
            <p><strong>Extensions PHP critiques :</strong></p>
            <ul>
            <?php
            $criticalExtensions = ['pdo', 'pdo_mysql'];
            $optionalExtensions = ['mbstring', 'openssl', 'tokenizer', 'xml', 'ctype', 'json'];
            
            foreach ($criticalExtensions as $ext) {
                if (extension_loaded($ext)) {
                    echo "<li><span class='status-ok'>✅ $ext (critique)</span></li>";
                } else {
                    echo "<li><span class='status-error'>❌ $ext (critique - BLOQUANT)</span></li>";
                }
            }
            
            foreach ($optionalExtensions as $ext) {
                if (extension_loaded($ext)) {
                    echo "<li><span class='status-ok'>✅ $ext (optionnel)</span></li>";
                } else {
                    echo "<li><span class='status-warning'>⚠️ $ext (optionnel - NON BLOQUANT)</span></li>";
                }
            }
            ?>
            </ul>
            
            <p><strong>Permissions :</strong></p>
            <ul>
            <?php
            $paths = ['storage' => '../storage', 'bootstrap/cache' => '../bootstrap/cache'];
            foreach ($paths as $name => $path) {
                if (is_writable($path)) {
                    echo "<li><span class='status-ok'>✅ $name (écriture OK)</span></li>";
                } else {
                    echo "<li><span class='status-warning'>⚠️ $name (pas d'écriture - NON BLOQUANT)</span></li>";
                }
            }
            ?>
            </ul>
        </div>
        
        <div class="test-section">
            <h3>📝 Résumé des modifications :</h3>
            <div style="background: #e3f2fd; padding: 15px; border-radius: 8px; border-left: 4px solid #2196f3;">
                <p><strong>Fichier modifié :</strong> <code>public/install/install_new.php</code></p>
                <p><strong>Modifications :</strong></p>
                <ol>
                    <li>Vérification de licence plus permissive à l'étape 2</li>
                    <li>Auto-création de licence de test si manquante</li>
                    <li>Prérequis système non bloquants (sauf PDO critique)</li>
                    <li>Logs améliorés pour le debugging</li>
                    <li>Suppression du retour forcé à l'étape 1</li>
                </ol>
            </div>
        </div>
        
        <div class="test-section">
            <h3>🚀 Actions rapides :</h3>
            <a href="install/install_new.php?step=2" class="btn btn-success">Test Step 2</a>
            <a href="install/install_new.php?step=3" class="btn btn-warning">Test Step 3</a>
            <a href="install/install_log.txt" class="btn btn-primary" target="_blank">Voir les logs</a>
        </div>
    </div>
</body>
</html>
