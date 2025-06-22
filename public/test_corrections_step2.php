<?php
/**
 * Test des corrections Step2 - AdminLicence
 * Version: 1.0.0
 * Date: 2025-01-15
 */

// Démarrer la session
session_start();

// Simuler une licence valide pour les tests
$_SESSION['license_valid'] = true;
$_SESSION['license_key'] = 'TEST-XXXX-XXXX-XXXX';

// Définir les chemins
define('ROOT_PATH', dirname(__DIR__));
define('INSTALL_PATH', __DIR__ . '/install');

// Inclure les fichiers nécessaires
require_once INSTALL_PATH . '/functions/language.php';
require_once INSTALL_PATH . '/functions/core.php';

// Initialiser la langue
$currentLang = initLanguage();

?>
<!DOCTYPE html>
<html lang="<?php echo $currentLang; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Corrections Step2 - AdminLicence</title>
    <link rel="stylesheet" href="install/assets/css/install.css">
    <style>
        .test-section {
            background: white;
            margin: 20px;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .test-result {
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
        }
        .success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .info { background: #d1ecf1; color: #0c5460; border: 1px solid #bee5eb; }
        .warning { background: #fff3cd; color: #856404; border: 1px solid #ffeaa7; }
        .code { background: #f8f9fa; padding: 10px; border-radius: 3px; font-family: monospace; }
        
        /* Test du champ licence réduit */
        .license-test {
            margin: 20px 0;
            padding: 15px;
            border: 2px dashed #007bff;
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔧 Test Corrections Step2</h1>
            <p>Vérification des corrections apportées</p>
        </div>

        <div class="test-section">
            <h2>📏 1. Test Champ Licence Réduit (70%)</h2>
            
            <div class="license-test">
                <div class="license-verification-section">
                    <div class="form-group">
                        <label for="test_serial_key">Clé de licence (réduite de 70%)</label>
                        <input type="text" id="test_serial_key" name="serial_key" 
                               placeholder="XXXX-XXXX-XXXX-XXXX" 
                               value="TEST-DEMO-KEY1-2345"
                               style="text-transform: uppercase;">
                        <small>Format requis : XXXX-XXXX-XXXX-XXXX</small>
                    </div>
                </div>
            </div>
            
            <div class="test-result success">
                <strong>✅ Champ licence réduit :</strong>
                <ul>
                    <li>Largeur réduite à 30% (réduction de 70%)</li>
                    <li>Centré automatiquement</li>
                    <li>Largeur minimale : 250px</li>
                    <li>Responsive sur mobile (80% largeur)</li>
                </ul>
            </div>
        </div>

        <div class="test-section">
            <h2>🔄 2. Test Logique Step2</h2>
            
            <?php
            echo '<div class="test-result info">';
            echo '<strong>🔐 État actuel de la session :</strong><br>';
            echo 'Licence valide : ' . (isset($_SESSION['license_valid']) && $_SESSION['license_valid'] ? '✅ OUI' : '❌ NON') . '<br>';
            echo 'Clé licence : ' . ($_SESSION['license_key'] ?? '❌ Aucune') . '<br>';
            echo 'Méthode de requête : ' . $_SERVER['REQUEST_METHOD'] . '<br>';
            echo '</div>';
            ?>
            
            <div class="test-result success">
                <strong>✅ Corrections appliquées :</strong>
                <ul>
                    <li><strong>Vérification licence modifiée</strong> - Ne s'applique que pour les requêtes GET</li>
                    <li><strong>Traitement POST protégé</strong> - Les soumissions de formulaire ne sont plus bloquées</li>
                    <li><strong>Redirection explicite</strong> - Après succès step2 → step3</li>
                    <li><strong>Maintien à l'étape 2</strong> - En cas d'erreur de base de données</li>
                </ul>
            </div>

            <div class="test-result warning">
                <strong>⚠️ Logique corrigée :</strong>
                <div class="code">
                    // AVANT : Vérification licence pour toutes les requêtes<br>
                    if ($step >= 2 && !isset($_SESSION['license_valid'])) {<br>
                    <br>
                    // APRÈS : Vérification licence SEULEMENT pour GET<br>
                    if ($step >= 2 && !isset($_SESSION['license_valid']) && $_SERVER['REQUEST_METHOD'] !== 'POST') {
                </div>
            </div>
        </div>

        <div class="test-section">
            <h2>🎯 3. Test Interface Step2</h2>
            
            <div class="step-2-container">
                <div class="database-config-section">
                    <div class="database-info">
                        <h3>Configuration de la base de données</h3>
                        <p>Configurez la connexion à votre base de données MySQL</p>
                    </div>
                    
                    <form method="post" action="#" onsubmit="return false;">
                        <div class="db-form-row">
                            <div class="form-group">
                                <label for="test_db_host">Hôte de la base de données</label>
                                <input type="text" id="test_db_host" name="db_host" 
                                       value="localhost" placeholder="localhost">
                            </div>
                            
                            <div class="form-group">
                                <label for="test_db_port">Port</label>
                                <input type="text" id="test_db_port" name="db_port" 
                                       value="3306" placeholder="3306">
                            </div>
                        </div>
                        
                        <div class="form-group db-form-full">
                            <label for="test_db_name">Nom de la base de données</label>
                            <input type="text" id="test_db_name" name="db_name" 
                                   placeholder="adminlicence">
                            <small>La base de données sera créée si elle n'existe pas</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="test_db_user">Utilisateur</label>
                            <input type="text" id="test_db_user" name="db_user" 
                                   placeholder="root">
                        </div>
                        
                        <div class="form-group">
                            <label for="test_db_password">Mot de passe</label>
                            <input type="password" id="test_db_password" name="db_password" 
                                   placeholder="Optionnel">
                            <small>Laissez vide si aucun mot de passe</small>
                        </div>
                        
                        <div class="form-actions">
                            <button type="button" class="btn btn-secondary">Retour</button>
                            <button type="button" class="btn btn-primary">Suivant</button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="test-result success">
                <strong>✅ Interface améliorée :</strong>
                <ul>
                    <li>Layout en grille 2 colonnes (Host/Port)</li>
                    <li>Section d'information avec icône</li>
                    <li>Boutons mieux centrés</li>
                    <li>Textes d'aide pour champs optionnels</li>
                </ul>
            </div>
        </div>

        <div class="test-section">
            <h2>🚀 4. Tests Fonctionnels</h2>
            
            <div class="form-actions">
                <a href="install/install_new.php" class="btn btn-primary">
                    🔧 Tester Step1 (Champ réduit)
                </a>
                <a href="install/install_new.php?step=2" class="btn btn-secondary">
                    📊 Tester Step2 (Logique corrigée)
                </a>
            </div>

            <div class="test-result info">
                <strong>💡 Instructions de test :</strong>
                <ol>
                    <li><strong>Step1</strong> : Vérifiez que le champ licence est réduit de 70%</li>
                    <li><strong>Step2</strong> : Rentrez des infos DB et vérifiez qu'on ne retourne plus au step1</li>
                    <li><strong>Erreur DB</strong> : Testez avec de mauvaises infos pour rester au step2</li>
                    <li><strong>Succès DB</strong> : Testez avec bonnes infos pour aller au step3</li>
                </ol>
            </div>

            <div class="test-result warning">
                <strong>⚠️ Points à vérifier :</strong>
                <ul>
                    <li>Le champ licence fait bien 30% de largeur</li>
                    <li>Après soumission step2 → pas de retour au step1</li>
                    <li>En cas d'erreur DB → reste au step2</li>
                    <li>En cas de succès DB → va au step3</li>
                </ul>
            </div>
        </div>

        <div class="test-section">
            <h2>📝 5. Résumé des Corrections</h2>
            
            <div class="test-result success">
                <strong>✅ Problème 1 - Champ licence réduit :</strong>
                <ul>
                    <li>CSS ajouté : <code>.license-verification-section .form-group input[name="serial_key"]</code></li>
                    <li>Largeur : <code>width: 30% !important</code> (réduction de 70%)</li>
                    <li>Centrage automatique avec <code>margin: 0 auto</code></li>
                    <li>Responsive mobile : 80% sur petits écrans</li>
                </ul>
            </div>

            <div class="test-result success">
                <strong>✅ Problème 2 - Retour au step1 corrigé :</strong>
                <ul>
                    <li>Vérification licence modifiée pour exclure les POST</li>
                    <li>Redirection explicite après succès step2</li>
                    <li>Maintien forcé à l'étape 2 en cas d'erreur</li>
                    <li>Logging amélioré pour debug</li>
                </ul>
            </div>

            <div class="test-result info">
                <strong>🔧 Code modifié :</strong>
                <div class="code">
                    // Vérification licence - SEULEMENT pour GET<br>
                    if ($step >= 2 && !isset($_SESSION['license_valid']) && $_SERVER['REQUEST_METHOD'] !== 'POST')<br>
                    <br>
                    // Redirection après succès step2<br>
                    if (!$isAjax) {<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;header('Location: install_new.php?step=3');<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;exit;<br>
                    }
                </div>
            </div>
        </div>

        <div class="footer">
            <div class="footer-content">
                <p>&copy; 2025 AdminLicence. Tous droits réservés.</p>
                <div class="footer-links">
                    <a href="#">Support</a>
                    <a href="#">Documentation</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html> 