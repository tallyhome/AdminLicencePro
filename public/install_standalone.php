<?php
/**
 * Installation Autonome AdminLicence
 * Version 100% autonome sans dépendances externes
 */

// Configuration des erreurs
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Démarrer la session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Variables
$step = isset($_POST['step']) ? (int)$_POST['step'] : (isset($_GET['step']) ? (int)$_GET['step'] : 1);
$errors = [];
$success_message = '';

/**
 * Fonction autonome de vérification de licence
 */
function verifierLicenceAutonome($cleSeriale, $domaine = null, $adresseIP = null) {
    if (empty($cleSeriale)) {
        return [
            'valide' => false,
            'message' => 'Clé de licence requise',
            'donnees' => null
        ];
    }

    // Valider le format de la clé de licence
    if (!preg_match('/^[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}$/', strtoupper($cleSeriale))) {
        return [
            'valide' => false,
            'message' => 'Format de clé de licence invalide (XXXX-XXXX-XXXX-XXXX)',
            'donnees' => null
        ];
    }

    // URL de l'API de vérification
    $url = "https://licence.myvcard.fr/api/check-serial.php";
    
    // Données à envoyer
    $donnees = [
        'serial_key' => trim(strtoupper($cleSeriale)),
        'domain' => $domaine ?: ($_SERVER['HTTP_HOST'] ?? 'localhost'),
        'ip_address' => $adresseIP ?: ($_SERVER['REMOTE_ADDR'] ?? '127.0.0.1')
    ];
     
    try {
        // Initialiser cURL
        $ch = curl_init($url);
        if ($ch === false) {
            throw new Exception('Erreur lors de l\'initialisation de la connexion');
        }
        
        // Configurer cURL
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($donnees),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS => 3,
            CURLOPT_USERAGENT => 'AdminLicence/4.5.1 Installation',
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Accept: application/json'
            ]
        ]);
        
        // Exécuter la requête
        $reponse = curl_exec($ch);
        $erreur = curl_error($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        curl_close($ch);
        
        if ($erreur) {
            throw new Exception("Erreur cURL: " . $erreur);
        }
        
        if (empty($reponse)) {
            throw new Exception("Réponse vide du serveur de licences");
        }
        
        // Décoder la réponse JSON
        $resultat = json_decode($reponse, true);
        if (json_last_error() !== JSON_ERROR_NONE || !is_array($resultat)) {
            throw new Exception("Erreur de décodage JSON: " . json_last_error_msg());
        }
        
        // Vérifier le statut de la réponse
        if (!isset($resultat['status'])) {
            throw new Exception("Réponse sans statut");
        }
        
        if ($resultat['status'] === 'error') {
            return [
                'valide' => false,
                'message' => $resultat['message'] ?? 'Clé de licence invalide',
                'donnees' => null
            ];
        }
        
        // Vérification du statut success et des données
        if ($resultat['status'] === 'success' && isset($resultat['data'])) {
            $data = $resultat['data'];
            
            // Vérifier si les données essentielles sont présentes (token est requis)
            if (isset($data['token'])) {
                $isValid = true;
                $message = 'Licence valide';
                
                // Vérifier si la licence n'est pas expirée (si expires_at est fourni)
                if (!empty($data['expires_at']) && $data['expires_at'] !== null) {
                    try {
                        $expirationDate = new DateTime($data['expires_at']);
                        $currentDate = new DateTime();
                        if ($currentDate > $expirationDate) {
                            $isValid = false;
                            $message = 'Licence expirée';
                        }
                    } catch (Exception $e) {
                        // Si la date est invalide, on considère la licence comme valide
                    }
                }
                
                if ($isValid) {
                    return [
                        'valide' => true,
                        'message' => $message,
                        'donnees' => $data
                    ];
                } else {
                    return [
                        'valide' => false,
                        'message' => $message,
                        'donnees' => null
                    ];
                }
            } else {
                return [
                    'valide' => false,
                    'message' => 'Clé de licence invalide',
                    'donnees' => null
                ];
            }
        } else {
            return [
                'valide' => false,
                'message' => $resultat['message'] ?? 'Clé de licence invalide',
                'donnees' => null
            ];
        }
        
    } catch (Exception $e) {
        return [
            'valide' => false,
            'message' => "Erreur de connexion au serveur de licence: " . $e->getMessage(),
            'donnees' => null
        ];
    }
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    switch ($step) {
        case 1: // Vérification de la licence
            if (empty($_POST['serial_key'])) {
                $errors[] = 'La clé de licence est requise';
            } else {
                $serial_key = trim(strtoupper($_POST['serial_key']));
                $domain = $_SERVER['HTTP_HOST'] ?? 'localhost';
                $ipAddress = $_SERVER['SERVER_ADDR'] ?? $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
                
                // Vérifier la licence
                $licenseCheck = verifierLicenceAutonome($serial_key, $domain, $ipAddress);
                
                if (!$licenseCheck['valide']) {
                    $errors[] = $licenseCheck['message'];
                } else {
                    // Licence valide - stocker et passer à l'étape 2
                    $_SESSION['license_data'] = $licenseCheck['donnees'];
                    $_SESSION['license_key'] = $serial_key;
                    $_SESSION['license_valid'] = true;
                    
                    $success_message = "✅ Licence valide ! Installation peut continuer.";
                    $step = 2; // FORCER le passage à l'étape 2
                }
            }
            break;
            
        case 2: // Finalisation
            $_SESSION['installation_complete'] = true;
            $success_message = "✅ Installation terminée avec succès !";
            $step = 3;
            break;
    }
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Installation Autonome AdminLicence</title>
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
        }
        
        .container {
            max-width: 600px;
            margin: 50px auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            overflow: hidden;
        }
        
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-align: center;
            padding: 30px;
        }
        
        .header h1 {
            font-size: 2rem;
            margin-bottom: 10px;
        }
        
        .content {
            padding: 30px;
        }
        
        .alert {
            padding: 15px;
            margin: 20px 0;
            border-radius: 8px;
            border-left: 4px solid;
        }
        
        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border-left-color: #dc3545;
        }
        
        .alert-success {
            background: #d4edda;
            color: #155724;
            border-left-color: #28a745;
        }
        
        .alert-info {
            background: #d1ecf1;
            color: #0c5460;
            border-left-color: #17a2b8;
        }
        
        .form-group {
            margin: 20px 0;
        }
        
        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }
        
        .form-input {
            width: 100%;
            padding: 12px;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        
        .form-input:focus {
            outline: none;
            border-color: #667eea;
        }
        
        .btn {
            padding: 12px 24px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            transition: transform 0.2s, box-shadow 0.2s;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        
        .btn-verify {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        }
        
        .btn-verify:hover {
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.4);
        }
        
        .step-indicator {
            display: flex;
            justify-content: center;
            margin: 30px 0;
            gap: 10px;
        }
        
        .step-item {
            padding: 12px 20px;
            background: #e9ecef;
            border-radius: 25px;
            font-weight: 600;
            color: #6c757d;
            font-size: 14px;
        }
        
        .step-item.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .step-item.completed {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
        }
        
        .text-center {
            text-align: center;
        }
        
        .success-icon {
            font-size: 4rem;
            margin-bottom: 20px;
        }
        
        .footer {
            text-align: center;
            padding: 20px;
            background: #f8f9fa;
            color: #6c757d;
            font-size: 14px;
        }
        
        .footer a {
            color: #667eea;
            text-decoration: none;
            margin: 0 10px;
        }
        
        .footer a:hover {
            text-decoration: underline;
        }
        
        .debug-section {
            margin-top: 30px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
            border: 1px solid #e9ecef;
        }
        
        .debug-section summary {
            cursor: pointer;
            font-weight: 600;
            color: #495057;
        }
        
        .debug-section pre {
            background: white;
            padding: 15px;
            border-radius: 4px;
            overflow-x: auto;
            font-size: 12px;
            margin-top: 10px;
            border: 1px solid #dee2e6;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Installation Autonome AdminLicence</h1>
            <p>Version 100% autonome - Étape <?php echo $step; ?></p>
        </div>

        <div class="content">
            <!-- Indicateur d'étapes -->
            <div class="step-indicator">
                <div class="step-item <?php echo $step >= 1 ? ($step > 1 ? 'completed' : 'active') : ''; ?>">
                    1. Licence
                </div>
                <div class="step-item <?php echo $step >= 2 ? ($step > 2 ? 'completed' : 'active') : ''; ?>">
                    2. Finalisation
                </div>
                <div class="step-item <?php echo $step >= 3 ? 'active' : ''; ?>">
                    3. Terminé
                </div>
            </div>

            <!-- Affichage des erreurs -->
            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <strong>❌ Erreur<?php echo count($errors) > 1 ? 's' : ''; ?> :</strong>
                    <ul style="margin: 10px 0 0 20px;">
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <!-- Affichage des messages de succès -->
            <?php if (!empty($success_message)): ?>
                <div class="alert alert-success">
                    <?php echo $success_message; ?>
                </div>
            <?php endif; ?>

            <?php if ($step == 1): ?>
                <!-- Étape 1: Vérification de la licence -->
                <h2>Vérification de la licence</h2>
                <p style="color: #6c757d; margin-bottom: 20px;">Entrez votre clé de licence pour continuer l'installation</p>
                
                <div class="alert alert-info">
                    <strong>ℹ️ Information :</strong> Votre clé sera vérifiée sur <code>https://licence.myvcard.fr</code>
                </div>

                <form method="post" action="">
                    <input type="hidden" name="step" value="1">
                    
                    <div class="form-group">
                        <label for="serial_key" class="form-label">Clé de licence *</label>
                        <input type="text" 
                               id="serial_key" 
                               name="serial_key" 
                               class="form-input"
                               required 
                               placeholder="XXXX-XXXX-XXXX-XXXX" 
                               value="<?php echo htmlspecialchars($_POST['serial_key'] ?? 'JQUV-QSDM-UT8G-BFHY'); ?>"
                               style="text-transform: uppercase;">
                        <small style="color: #6c757d; font-size: 14px;">Entrez la clé de licence fournie lors de votre achat</small>
                    </div>
                    
                    <div class="text-center" style="margin-top: 30px;">
                        <button type="submit" class="btn btn-verify">Vérifier la licence</button>
                    </div>
                </form>

            <?php elseif ($step == 2): ?>
                <!-- Étape 2: Finalisation -->
                <h2>Finalisation de l'installation</h2>
                <p style="color: #6c757d; margin-bottom: 20px;">Licence validée avec succès ! Cliquez sur "Finaliser" pour terminer l'installation.</p>

                <div class="alert alert-success">
                    <strong>✅ Licence validée :</strong> <?php echo htmlspecialchars($_SESSION['license_key'] ?? ''); ?>
                </div>

                <form method="post" action="">
                    <input type="hidden" name="step" value="2">
                    
                    <div class="text-center">
                        <button type="submit" class="btn">Finaliser l'installation</button>
                    </div>
                </form>

            <?php elseif ($step == 3): ?>
                <!-- Étape 3: Installation terminée -->
                <div class="text-center">
                    <div class="success-icon">🎉</div>
                    <h2 style="color: #28a745; margin-bottom: 20px;">Installation terminée !</h2>
                    <p style="color: #6c757d; margin-bottom: 30px;">AdminLicence a été installé avec succès.</p>

                    <div class="alert alert-success">
                        <strong>Licence :</strong> <?php echo htmlspecialchars($_SESSION['license_key'] ?? 'Non configurée'); ?><br>
                        <strong>Installation :</strong> Terminée avec succès
                    </div>

                    <a href="../" class="btn">Accéder à l'application</a>
                </div>

            <?php endif; ?>

            <!-- Debug info -->
            <details class="debug-section">
                <summary>🔧 Informations de débogage</summary>
                <div>
                    <p><strong>Étape actuelle :</strong> <?php echo $step; ?></p>
                    <p><strong>Session :</strong></p>
                    <pre><?php echo json_encode($_SESSION, JSON_PRETTY_PRINT); ?></pre>
                    
                    <p><strong>POST Data :</strong></p>
                    <pre><?php echo json_encode($_POST, JSON_PRETTY_PRINT); ?></pre>
                    
                    <p><strong>Extensions PHP :</strong></p>
                    <ul>
                        <li>cURL: <?php echo extension_loaded('curl') ? '✅ Disponible' : '❌ Non disponible'; ?></li>
                        <li>JSON: <?php echo extension_loaded('json') ? '✅ Disponible' : '❌ Non disponible'; ?></li>
                    </ul>
                </div>
            </details>
        </div>

        <div class="footer">
            <p>&copy; 2025 AdminLicence. Tous droits réservés.</p>
            <div style="margin-top: 10px;">
                <a href="debug_install_licence.php">Diagnostic complet</a>
                <a href="test_boutons_final.php">Test des boutons</a>
                <a href="install/install_new.php">Installation originale</a>
            </div>
        </div>
    </div>

    <script>
        // Auto-formatage de la clé de licence
        document.addEventListener('DOMContentLoaded', function() {
            const serialKeyInput = document.getElementById('serial_key');
            if (serialKeyInput) {
                serialKeyInput.addEventListener('input', function(e) {
                    let value = e.target.value.replace(/[^A-Z0-9]/g, '').toUpperCase();
                    let formatted = '';
                    
                    for (let i = 0; i < value.length && i < 16; i++) {
                        if (i > 0 && i % 4 === 0) {
                            formatted += '-';
                        }
                        formatted += value[i];
                    }
                    
                    e.target.value = formatted;
                });
            }
            
            console.log('Installation autonome chargée - Étape:', <?php echo $step; ?>);
        });
    </script>
</body>
</html> 