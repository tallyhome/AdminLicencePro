<?php
/**
 * Page de statut des installations AdminLicence
 * Résumé de toutes les solutions disponibles
 */

// Configuration des erreurs
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Test de connexion API
function testApiConnection() {
    $url = "https://licence.myvcard.fr/api/check-serial.php";
    $data = [
        'serial_key' => 'JQUV-QSDM-UT8G-BFHY',
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
    
    if ($response && $httpCode === 200) {
        $result = json_decode($response, true);
        return $result['status'] === 'success' ? 'Fonctionnelle' : 'Problème API';
    }
    
    return 'Erreur connexion';
}

$apiStatus = testApiConnection();

// Test des fichiers d'installation
$installations = [
    'install/install_new.php' => [
        'nom' => 'Installation Originale (Corrigée)',
        'description' => 'Installation complète avec toutes les étapes',
        'statut' => file_exists(__DIR__ . '/install/install_new.php') ? 'Disponible' : 'Manquant',
        'couleur' => 'success'
    ],
    'install_ultra_simple.php' => [
        'nom' => 'Installation Ultra-Simple',
        'description' => 'Version simplifiée avec correction INSTALL_PATH',
        'statut' => file_exists(__DIR__ . '/install_ultra_simple.php') ? 'Disponible' : 'Manquant',
        'couleur' => 'success'
    ],
    'install_standalone.php' => [
        'nom' => 'Installation Autonome',
        'description' => 'Version 100% autonome sans dépendances',
        'statut' => file_exists(__DIR__ . '/install_standalone.php') ? 'Disponible' : 'Manquant',
        'couleur' => 'success'
    ],
    'debug_install_licence.php' => [
        'nom' => 'Diagnostic Licence',
        'description' => 'Test complet de l\'API de licence',
        'statut' => file_exists(__DIR__ . '/debug_install_licence.php') ? 'Disponible' : 'Manquant',
        'couleur' => 'info'
    ],
    'test_boutons_final.php' => [
        'nom' => 'Test Boutons Final',
        'description' => 'Test des boutons sans rectangles blancs',
        'statut' => file_exists(__DIR__ . '/test_boutons_final.php') ? 'Disponible' : 'Manquant',
        'couleur' => 'info'
    ]
];

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statut des Installations AdminLicence</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
            color: #333;
        }
        
        .container {
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            overflow: hidden;
        }
        
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-align: center;
            padding: 40px 20px;
        }
        
        .header h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
        }
        
        .header p {
            font-size: 1.1rem;
            opacity: 0.9;
        }
        
        .content {
            padding: 40px;
        }
        
        .api-status {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 30px;
            border-left: 4px solid #28a745;
        }
        
        .api-status.error {
            border-left-color: #dc3545;
            background: #f8d7da;
        }
        
        .installations-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .installation-card {
            background: white;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        .installation-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        }
        
        .installation-card h3 {
            color: #333;
            margin-bottom: 10px;
            font-size: 1.2rem;
        }
        
        .installation-card p {
            color: #666;
            margin-bottom: 15px;
            line-height: 1.5;
        }
        
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .status-success {
            background: #d4edda;
            color: #155724;
        }
        
        .status-info {
            background: #d1ecf1;
            color: #0c5460;
        }
        
        .status-error {
            background: #f8d7da;
            color: #721c24;
        }
        
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            margin-top: 10px;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }
        
        .btn-test {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        }
        
        .btn-test:hover {
            box-shadow: 0 4px 15px rgba(40, 167, 69, 0.4);
        }
        
        .summary {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 30px;
            text-align: center;
        }
        
        .summary h2 {
            color: #333;
            margin-bottom: 15px;
        }
        
        .summary p {
            color: #666;
            line-height: 1.6;
            margin-bottom: 20px;
        }
        
        .quick-links {
            display: flex;
            justify-content: center;
            gap: 15px;
            flex-wrap: wrap;
        }
        
        .timestamp {
            text-align: center;
            color: #666;
            font-size: 0.9rem;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
        }
        
        .problem-solved {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .problem-solved h3 {
            color: #155724;
            margin-bottom: 10px;
        }
        
        .problem-solved ul {
            color: #155724;
            margin-left: 20px;
        }
        
        .problem-solved li {
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎉 Statut des Installations</h1>
            <p>AdminLicence 4.5.1 - Toutes les solutions disponibles</p>
        </div>

        <div class="content">
            <!-- Statut API -->
            <div class="api-status <?php echo $apiStatus !== 'Fonctionnelle' ? 'error' : ''; ?>">
                <h3>🌐 Statut API de Licence</h3>
                <p><strong>Endpoint :</strong> https://licence.myvcard.fr/api/check-serial.php</p>
                <p><strong>Clé de test :</strong> JQUV-QSDM-UT8G-BFHY</p>
                <p><strong>Statut :</strong> <span class="status-badge status-<?php echo $apiStatus === 'Fonctionnelle' ? 'success' : 'error'; ?>"><?php echo $apiStatus; ?></span></p>
            </div>

            <!-- Problèmes résolus -->
            <div class="problem-solved">
                <h3>✅ Problèmes résolus avec succès</h3>
                <ul>
                    <li><strong>Erreur INSTALL_PATH :</strong> Constantes définies dans install_ultra_simple.php</li>
                    <li><strong>Boutons rectangles blancs :</strong> CSS corrigé, effets ::before/::after supprimés</li>
                    <li><strong>Fonctions t() manquantes :</strong> Toutes remplacées par du texte français</li>
                    <li><strong>API de licence :</strong> Endpoint corrigé vers /api/check-serial.php</li>
                    <li><strong>Validation licence :</strong> Logique assouplie, seul 'token' requis</li>
                </ul>
            </div>

            <!-- Grille des installations -->
            <h2 style="margin-bottom: 20px; color: #333;">📦 Installations Disponibles</h2>
            <div class="installations-grid">
                <?php foreach ($installations as $file => $info): ?>
                <div class="installation-card">
                    <h3><?php echo $info['nom']; ?></h3>
                    <p><?php echo $info['description']; ?></p>
                    <span class="status-badge status-<?php echo $info['couleur']; ?>"><?php echo $info['statut']; ?></span>
                    
                    <?php if ($info['statut'] === 'Disponible'): ?>
                        <br>
                        <a href="<?php echo $file; ?>" class="btn <?php echo $info['couleur'] === 'info' ? 'btn-test' : ''; ?>">
                            <?php echo $info['couleur'] === 'info' ? 'Tester' : 'Installer'; ?>
                        </a>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Résumé -->
            <div class="summary">
                <h2>🎯 Recommandation</h2>
                <p>
                    <strong>Pour une installation normale :</strong> Utilisez <code>install/install_new.php</code> (maintenant corrigée)<br>
                    <strong>Pour une installation simple :</strong> Utilisez <code>install_ultra_simple.php</code><br>
                    <strong>Pour des tests :</strong> Utilisez <code>debug_install_licence.php</code> et <code>test_boutons_final.php</code>
                </p>
                
                <div class="quick-links">
                    <a href="install/install_new.php" class="btn">Installation Principale</a>
                    <a href="install_ultra_simple.php" class="btn">Installation Simple</a>
                    <a href="debug_install_licence.php" class="btn btn-test">Test API</a>
                    <a href="test_boutons_final.php" class="btn btn-test">Test Boutons</a>
                </div>
            </div>

            <div class="timestamp">
                Dernière mise à jour : <?php echo date('d/m/Y H:i:s'); ?>
            </div>
        </div>
    </div>
</body>
</html> 