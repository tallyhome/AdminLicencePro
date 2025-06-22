<?php
/**
 * Test final des boutons - Version qui fonctionne à 100%
 */
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Final des Boutons</title>
    <link rel="stylesheet" href="install/assets/css/install.css">
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background: #f8f9fa;
        }
        .test-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .test-section {
            margin-bottom: 40px;
            padding: 20px;
            border: 1px solid #e9ecef;
            border-radius: 8px;
        }
        .test-result {
            margin-top: 15px;
            padding: 10px;
            border-radius: 5px;
            font-weight: bold;
        }
        .success { background: #d4edda; color: #155724; }
        .error { background: #f8d7da; color: #721c24; }
        .warning { background: #fff3cd; color: #856404; }
        .info { background: #d1ecf1; color: #0c5460; }
        
        /* Test pour voir si les boutons deviennent blancs */
        .btn-test {
            margin: 10px;
            padding: 12px 24px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.2s;
            position: relative;
            overflow: visible;
        }
        
        .btn-verify-test {
            background: #28a745;
            color: white;
        }
        
        .btn-primary-test {
            background: #007bff;
            color: white;
        }
        
        /* Forcer la suppression de tous les pseudo-éléments */
        .btn-test::before, .btn-test::after,
        .btn-verify-test::before, .btn-verify-test::after,
        .btn-primary-test::before, .btn-primary-test::after {
            display: none !important;
            content: none !important;
            background: none !important;
            opacity: 0 !important;
            visibility: hidden !important;
        }
        
        .btn-test:hover::before, .btn-test:hover::after,
        .btn-verify-test:hover::before, .btn-verify-test:hover::after,
        .btn-primary-test:hover::before, .btn-primary-test:hover::after {
            display: none !important;
            content: none !important;
            background: transparent !important;
            opacity: 0 !important;
            visibility: hidden !important;
        }
    </style>
</head>
<body>
    <div class="test-container">
        <h1>🧪 Test Final des Boutons</h1>
        <p><strong>URL de ce test :</strong> <code>http://localhost:8000/public/test_boutons_final.php</code></p>
        
        <!-- Test 1: Boutons avec le CSS d'installation -->
        <div class="test-section">
            <h2>1. Test des boutons avec CSS d'installation</h2>
            <p>Ces boutons utilisent les classes CSS de l'installation :</p>
            
            <button class="btn btn-verify">Bouton Vérifier (original)</button>
            <button class="btn btn-primary">Bouton Primaire (original)</button>
            <button class="btn btn-secondary">Bouton Secondaire (original)</button>
            
            <div class="test-result info">
                <strong>Instructions :</strong> Survolez ces boutons. S'ils deviennent blancs, le problème persiste.
            </div>
        </div>
        
        <!-- Test 2: Boutons avec CSS corrigé -->
        <div class="test-section">
            <h2>2. Test des boutons avec CSS corrigé</h2>
            <p>Ces boutons utilisent le CSS corrigé :</p>
            
            <button class="btn-test btn-verify-test">Bouton Vérifier (corrigé)</button>
            <button class="btn-test btn-primary-test">Bouton Primaire (corrigé)</button>
            
            <div class="test-result success">
                <strong>Résultat attendu :</strong> Ces boutons ne doivent jamais devenir blancs au survol.
            </div>
        </div>
        
        <!-- Test 3: Formulaire de licence réel -->
        <div class="test-section">
            <h2>3. Test du formulaire de licence</h2>
            <p>Test avec la clé de licence valide :</p>
            
            <form method="POST" action="install_fixed.php" style="margin-top: 20px;">
                <input type="hidden" name="step" value="1">
                <div style="margin-bottom: 15px;">
                    <label for="serial_key" style="display: block; margin-bottom: 5px; font-weight: 600;">Clé de licence :</label>
                    <input type="text" 
                           id="serial_key" 
                           name="serial_key" 
                           value="JQUV-QSDM-UT8G-BFHY" 
                           style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; text-transform: uppercase;">
                </div>
                <button type="submit" class="btn btn-verify">Tester la licence</button>
            </form>
            
            <div class="test-result warning">
                <strong>Note :</strong> Ce formulaire pointe vers l'installation corrigée.
            </div>
        </div>
        
        <!-- Test 4: Diagnostic de l'API -->
        <div class="test-section">
            <h2>4. Test direct de l'API de licence</h2>
            
            <?php
            // Test direct de l'API
            $testKey = "JQUV-QSDM-UT8G-BFHY";
            $url = "https://licence.myvcard.fr/api/check-serial.php";
            
            $data = [
                'serial_key' => $testKey,
                'domain' => $_SERVER['HTTP_HOST'] ?? 'localhost',
                'ip_address' => $_SERVER['SERVER_ADDR'] ?? '127.0.0.1'
            ];
            
            echo "<p><strong>Test avec la clé :</strong> $testKey</p>";
            echo "<p><strong>URL API :</strong> $url</p>";
            
            $ch = curl_init($url);
            curl_setopt_array($ch, [
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => json_encode($data),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_HTTPHEADER => [
                    'Content-Type: application/json',
                    'Accept: application/json'
                ]
            ]);
            
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            curl_close($ch);
            
            if ($response && $httpCode == 200) {
                $decoded = json_decode($response, true);
                if ($decoded && $decoded['status'] === 'success') {
                    echo '<div class="test-result success">✅ <strong>API fonctionne :</strong> Licence valide détectée</div>';
                    echo '<details><summary>Voir la réponse complète</summary><pre>' . json_encode($decoded, JSON_PRETTY_PRINT) . '</pre></details>';
                } else {
                    echo '<div class="test-result error">❌ <strong>API erreur :</strong> ' . ($decoded['message'] ?? 'Réponse invalide') . '</div>';
                }
            } else {
                echo '<div class="test-result error">❌ <strong>Erreur de connexion :</strong> ' . ($error ?: "Code HTTP: $httpCode") . '</div>';
            }
            ?>
        </div>
        
        <!-- Liens de navigation -->
        <div class="test-section">
            <h2>5. Liens de test</h2>
            <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                <a href="install_fixed.php" class="btn btn-primary">Installation Corrigée</a>
                <a href="install/install_new.php" class="btn btn-secondary">Installation Originale</a>
                <a href="../" class="btn btn-secondary">Retour à l'accueil</a>
            </div>
        </div>
    </div>
    
    <script>
        // Test JavaScript pour détecter les problèmes de boutons
        document.addEventListener('DOMContentLoaded', function() {
            const buttons = document.querySelectorAll('.btn, .btn-test');
            
            buttons.forEach(button => {
                button.addEventListener('mouseenter', function() {
                    // Vérifier si le bouton devient transparent ou blanc
                    const style = window.getComputedStyle(this);
                    const bgColor = style.backgroundColor;
                    const opacity = style.opacity;
                    
                    if (opacity < 0.5 || bgColor === 'rgba(0, 0, 0, 0)' || bgColor === 'rgb(255, 255, 255)') {
                        console.warn('Bouton problématique détecté:', this, 'Background:', bgColor, 'Opacity:', opacity);
                    }
                });
            });
            
            console.log('Test des boutons initialisé - Vérifiez la console pour les problèmes détectés');
        });
    </script>
</body>
</html> 