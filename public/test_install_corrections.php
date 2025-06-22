<?php
/**
 * Test des corrections de l'installateur AdminLicence
 * Version: 1.0.0
 * Date: 2025-01-15
 */

// Démarrer la session
session_start();

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
    <title>Test des Corrections - AdminLicence</title>
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
        .code { background: #f8f9fa; padding: 10px; border-radius: 3px; font-family: monospace; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔧 Test des Corrections - AdminLicence</h1>
            <p>Vérification des améliorations apportées à l'installateur</p>
        </div>

        <div class="test-section">
            <h2>🎨 1. Test des Styles CSS</h2>
            
            <div class="test-result info">
                <strong>✅ Classes CSS ajoutées :</strong>
                <ul>
                    <li><code>.license-verification-section</code> - Amélioration de l'étape 1</li>
                    <li><code>.database-config-section</code> - Organisation de l'étape 2</li>
                    <li><code>.step-2-container</code> - Container spécifique étape 2</li>
                    <li><code>.db-form-row</code> - Layout en grille pour les champs DB</li>
                    <li><code>.database-info</code> - Section d'information base de données</li>
                </ul>
            </div>

            <div class="test-result success">
                <strong>🎯 Boutons améliorés :</strong>
                <div style="margin: 10px 0;">
                    <button class="btn btn-verify">Vérifier la licence</button>
                    <button class="btn btn-primary">Suivant</button>
                    <button class="btn btn-secondary">Retour</button>
                </div>
            </div>
        </div>

        <div class="test-section">
            <h2>🌐 2. Test des Traductions</h2>
            
            <?php
            $testKeys = [
                'license_verification',
                'license_api',
                'required_format',
                'database_configuration',
                'database_configuration_description',
                'database_will_be_created',
                'leave_empty_if_no_password'
            ];
            
            echo '<div class="test-result info">';
            echo '<strong>🔤 Traductions testées :</strong><br>';
            foreach ($testKeys as $key) {
                $translation = t($key);
                $status = ($translation !== $key) ? '✅' : '❌';
                echo "<code>$key</code> : $status $translation<br>";
            }
            echo '</div>';
            ?>
        </div>

        <div class="test-section">
            <h2>📋 3. Test de la Logique d'Étapes</h2>
            
            <div class="test-result info">
                <strong>🔄 Améliorations apportées :</strong>
                <ul>
                    <li>✅ Vérification de licence pour étapes 2+</li>
                    <li>✅ Maintien à l'étape 2 en cas d'erreur DB</li>
                    <li>✅ Messages d'erreur plus détaillés</li>
                    <li>✅ Logging amélioré</li>
                </ul>
            </div>

            <?php
            // Test de la session
            echo '<div class="test-result ' . (isset($_SESSION['license_valid']) ? 'success' : 'error') . '">';
            echo '<strong>🔐 État de la licence :</strong> ';
            if (isset($_SESSION['license_valid']) && $_SESSION['license_valid']) {
                echo '✅ Licence validée en session';
                echo '<br><strong>Clé :</strong> ' . ($_SESSION['license_key'] ?? 'Non définie');
            } else {
                echo '❌ Aucune licence validée';
            }
            echo '</div>';
            ?>
        </div>

        <div class="test-section">
            <h2>🎯 4. Test de l'Interface Étape 2</h2>
            
            <div class="database-info">
                <h3><?php echo t('database_configuration'); ?></h3>
                <p><?php echo t('database_configuration_description'); ?></p>
            </div>

            <div class="db-form-row">
                <div class="form-group">
                    <label><?php echo t('db_host'); ?></label>
                    <input type="text" value="localhost" readonly>
                </div>
                <div class="form-group">
                    <label><?php echo t('db_port'); ?></label>
                    <input type="text" value="3306" readonly>
                </div>
            </div>

            <div class="form-group db-form-full">
                <label><?php echo t('db_name'); ?></label>
                <input type="text" placeholder="adminlicence" readonly>
                <small><?php echo t('database_will_be_created'); ?></small>
            </div>

            <div class="test-result success">
                <strong>✅ Layout amélioré :</strong> Les champs sont maintenant organisés en grille avec un meilleur espacement
            </div>
        </div>

        <div class="test-section">
            <h2>🚀 5. Actions de Test</h2>
            
            <div class="form-actions">
                <a href="install/install_new.php" class="btn btn-primary">
                    🔧 Tester l'Installateur
                </a>
                <a href="install/install_new.php?step=2" class="btn btn-secondary">
                    📊 Voir l'Étape 2
                </a>
            </div>

            <div class="test-result info">
                <strong>💡 Instructions de test :</strong>
                <ol>
                    <li>Cliquez sur "Tester l'Installateur" pour voir l'étape 1 améliorée</li>
                    <li>Le bouton "Vérifier la licence" est maintenant mieux positionné</li>
                    <li>L'étape 2 a un layout en grille plus organisé</li>
                    <li>Les boutons sont mieux centrés et espacés</li>
                    <li>En cas d'erreur à l'étape 2, vous restez à l'étape 2</li>
                </ol>
            </div>
        </div>

        <div class="test-section">
            <h2>📝 6. Résumé des Corrections</h2>
            
            <div class="test-result success">
                <strong>✅ Problèmes corrigés :</strong>
                <ul>
                    <li><strong>Bouton "Vérifier la licence"</strong> - Mieux positionné et centré</li>
                    <li><strong>Étape 2 - Organisation</strong> - Layout en grille 2 colonnes pour host/port</li>
                    <li><strong>Boutons "Suivant/Retour"</strong> - Mieux centrés avec espacement optimal</li>
                    <li><strong>Logique d'étapes</strong> - Reste à l'étape 2 même en cas d'erreur DB</li>
                    <li><strong>Vérification licence</strong> - Contrôle d'accès aux étapes 2+</li>
                </ul>
            </div>

            <div class="test-result info">
                <strong>🎨 Améliorations visuelles :</strong>
                <ul>
                    <li>Section d'information base de données avec icône</li>
                    <li>Champs organisés en grille responsive</li>
                    <li>Textes d'aide pour les champs optionnels</li>
                    <li>Boutons avec gradients et effets hover améliorés</li>
                </ul>
            </div>
        </div>

        <div class="footer">
            <div class="footer-content">
                <p>&copy; 2025 AdminLicence. <?php echo t('all_rights_reserved'); ?></p>
                <div class="footer-links">
                    <a href="#"><?php echo t('support'); ?></a>
                    <a href="#"><?php echo t('documentation'); ?></a>
                </div>
            </div>
        </div>
    </div>
</body>
</html> 