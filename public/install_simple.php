<?php
/**
 * Installation simple sans dépendances de traduction
 */

// Démarrer la session
session_start();

// Fonction de vérification de licence simplifiée
function verifyLicense($serialKey) {
    if (empty($serialKey)) {
        return [
            'valid' => false,
            'message' => 'Clé de licence requise',
            'data' => null
        ];
    }

    // Valider le format
    if (!preg_match('/^[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}$/', strtoupper($serialKey))) {
        return [
            'valid' => false,
            'message' => 'Format de clé invalide (XXXX-XXXX-XXXX-XXXX)',
            'data' => null
        ];
    }

    $url = "https://licence.myvcard.fr/api/check-serial.php";
    $data = [
        'serial_key' => trim(strtoupper($serialKey)),
        'domain' => $_SERVER['HTTP_HOST'] ?? 'localhost',
        'ip_address' => $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1'
    ];
    
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
    curl_close($ch);
    
    if ($httpCode !== 200 || empty($response)) {
        return [
            'valid' => false,
            'message' => 'Erreur de connexion au serveur de licence',
            'data' => null
        ];
    }
    
    $result = json_decode($response, true);
    if (!$result || $result['status'] !== 'success' || !isset($result['data']['token'])) {
        return [
            'valid' => false,
            'message' => $result['message'] ?? 'Licence invalide',
            'data' => null
        ];
    }
    
    return [
        'valid' => true,
        'message' => 'Licence valide',
        'data' => $result['data']
    ];
}

// Traitement du formulaire
$step = isset($_POST['step']) ? (int)$_POST['step'] : 1;
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $step === 1) {
    if (!empty($_POST['serial_key'])) {
        $licenseCheck = verifyLicense($_POST['serial_key']);
        
        if ($licenseCheck['valid']) {
            $_SESSION['license_data'] = $licenseCheck['data'];
            $_SESSION['license_key'] = $_POST['serial_key'];
            $_SESSION['license_valid'] = true;
            $step = 2; // Passer à l'étape suivante
        } else {
            $errors[] = $licenseCheck['message'];
        }
    } else {
        $errors[] = 'Veuillez entrer une clé de licence';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Installation AdminLicence - Simple</title>
    <link rel="stylesheet" href="install/assets/css/install.css">
    <style>
        .simple-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 30px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        .simple-title {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }
        .simple-form {
            margin-bottom: 20px;
        }
        .simple-input {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            margin-bottom: 15px;
        }
        .simple-button {
            background: #3b82f6;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            width: 100%;
        }
        .simple-button:hover {
            background: #2563eb;
        }
        .simple-error {
            background: #fee2e2;
            color: #dc2626;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
        }
        .simple-success {
            background: #dcfce7;
            color: #16a34a;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
        }
        .simple-info {
            background: #e0f2fe;
            color: #0369a1;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="simple-container">
        <h1 class="simple-title">Installation AdminLicence</h1>
        
        <?php if ($step === 1): ?>
            <div class="simple-info">
                <strong>Étape 1 : Vérification de licence</strong><br>
                Entrez votre clé de licence pour continuer l'installation.<br>
                <strong>API :</strong> https://licence.myvcard.fr
            </div>
            
            <?php if (!empty($errors)): ?>
                <?php foreach ($errors as $error): ?>
                    <div class="simple-error"><?php echo htmlspecialchars($error); ?></div>
                <?php endforeach; ?>
            <?php endif; ?>
            
            <form method="post" class="simple-form">
                <input type="hidden" name="step" value="1">
                <input type="text" 
                       name="serial_key" 
                       class="simple-input"
                       placeholder="XXXX-XXXX-XXXX-XXXX" 
                       value="<?php echo htmlspecialchars($_POST['serial_key'] ?? ''); ?>"
                       required
                       style="text-transform: uppercase;">
                <button type="submit" class="simple-button">Vérifier la licence</button>
            </form>
            
            <div class="simple-info">
                <strong>Pour tester :</strong> Utilisez la clé <code>JQUV-QSDM-UT8G-BFHY</code>
            </div>
            
        <?php elseif ($step === 2): ?>
            <div class="simple-success">
                <strong>✅ Licence valide !</strong><br>
                Clé : <?php echo htmlspecialchars($_SESSION['license_key']); ?><br>
                Token : <?php echo htmlspecialchars(substr($_SESSION['license_data']['token'], 0, 10)); ?>...
            </div>
            
            <div class="simple-info">
                <strong>Étape 2 : Configuration de la base de données</strong><br>
                La licence a été vérifiée avec succès. Vous pouvez maintenant continuer avec l'installation complète.
            </div>
            
            <a href="install/install_new.php?step=2" class="simple-button" style="display: block; text-align: center; text-decoration: none;">
                Continuer l'installation complète
            </a>
            
            <br>
            <a href="install_simple.php" class="simple-button" style="background: #6b7280; display: block; text-align: center; text-decoration: none;">
                Recommencer
            </a>
        <?php endif; ?>
        
        <div style="margin-top: 30px; text-align: center; color: #666; font-size: 14px;">
            <a href="test_licence_debug.php" target="_blank">🔧 Debug de licence</a> | 
            <a href="test_bouton.html" target="_blank">🎨 Test des boutons</a>
        </div>
    </div>
</body>
</html> 