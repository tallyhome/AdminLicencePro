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
    <title>Test Réorganisation des Étapes</title>
    <link rel="stylesheet" href="install/assets/css/install.css">
    <style>
        .test-container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        .test-section {
            margin: 30px 0;
            padding: 20px;
            border: 1px solid #e9ecef;
            border-radius: 8px;
        }
        .test-section h3 {
            color: #2c3e50;
            margin-bottom: 15px;
        }
        .step-preview {
            display: inline-block;
            margin: 10px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
            border: 2px solid #dee2e6;
            text-decoration: none;
            color: #495057;
            transition: all 0.3s;
        }
        .step-preview:hover {
            border-color: #007bff;
            background: #e3f2fd;
            transform: translateY(-2px);
        }
        .step-preview.current {
            border-color: #28a745;
            background: #d4edda;
        }
        .comparison-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .comparison-table th,
        .comparison-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #dee2e6;
        }
        .comparison-table th {
            background: #f8f9fa;
            font-weight: 600;
        }
        .old-structure {
            color: #dc3545;
        }
        .new-structure {
            color: #28a745;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
        }
        .btn-primary {
            background: #007bff;
            color: white;
        }
        .btn-secondary {
            background: #6c757d;
            color: white;
        }
        .btn-warning {
            background: #ffc107;
            color: #212529;
        }
    </style>
</head>
<body>
    <div class="test-container">
        <h1>🔧 Test de la Réorganisation des Étapes</h1>
        
        <div class="test-section">
            <h3>📊 Comparaison Ancienne vs Nouvelle Structure</h3>
            <table class="comparison-table">
                <thead>
                    <tr>
                        <th>Étape</th>
                        <th class="old-structure">❌ Ancienne Structure</th>
                        <th class="new-structure">✅ Nouvelle Structure</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>1</strong></td>
                        <td class="old-structure">Vérification de la licence</td>
                        <td class="new-structure">Vérification de la licence</td>
                    </tr>
                    <tr>
                        <td><strong>2</strong></td>
                        <td class="old-structure">Configuration BDD</td>
                        <td class="new-structure">Prérequis système</td>
                    </tr>
                    <tr>
                        <td><strong>3</strong></td>
                        <td class="old-structure">Configuration Admin</td>
                        <td class="new-structure">Configuration BDD</td>
                    </tr>
                    <tr>
                        <td><strong>4</strong></td>
                        <td class="old-structure">Finalisation</td>
                        <td class="new-structure">Configuration Admin</td>
                    </tr>
                    <tr>
                        <td><strong>5</strong></td>
                        <td class="old-structure">-</td>
                        <td class="new-structure">Finalisation</td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <div class="test-section">
            <h3>🧪 Test des Nouvelles Étapes</h3>
            <p>Cliquez sur chaque étape pour la tester :</p>
            
            <a href="install/install_new.php?step=1" class="step-preview">
                <strong>Étape 1</strong><br>
                Vérification de la licence
            </a>
            
            <a href="install/install_new.php?step=2" class="step-preview">
                <strong>Étape 2</strong><br>
                Prérequis système
            </a>
            
            <a href="install/install_new.php?step=3" class="step-preview">
                <strong>Étape 3</strong><br>
                Configuration BDD
            </a>
            
            <a href="install/install_new.php?step=4" class="step-preview">
                <strong>Étape 4</strong><br>
                Configuration Admin
            </a>
            
            <a href="install/install_new.php?step=5" class="step-preview">
                <strong>Étape 5</strong><br>
                Finalisation
            </a>
        </div>
        
        <div class="test-section">
            <h3>✅ Problèmes Résolus</h3>
            <ul style="list-style: none; padding: 0;">
                <li style="padding: 8px 0; border-bottom: 1px solid #eee;">
                    <strong>✅ Étape 2 :</strong> Maintenant "Prérequis système" au lieu de "Configuration BDD"
                </li>
                <li style="padding: 8px 0; border-bottom: 1px solid #eee;">
                    <strong>✅ Étape 3 :</strong> Maintenant "Configuration BDD" au lieu de "Configuration Admin"
                </li>
                <li style="padding: 8px 0; border-bottom: 1px solid #eee;">
                    <strong>✅ Étape 4 :</strong> Maintenant "Configuration Admin" au lieu de "Finalisation"
                </li>
                <li style="padding: 8px 0; border-bottom: 1px solid #eee;">
                    <strong>✅ Étape 5 :</strong> Nouvelle étape "Finalisation"
                </li>
                <li style="padding: 8px 0;">
                    <strong>✅ Interface :</strong> Amélioration de l'espacement et du design
                </li>
            </ul>
        </div>
        
        <div class="test-section">
            <h3>🎯 Fonctionnalités de l'Étape 2 (Prérequis Système)</h3>
            <ul>
                <li><strong>Vérification PHP :</strong> Version >= 8.1</li>
                <li><strong>Extensions critiques :</strong> PDO, PDO MySQL, Mbstring, OpenSSL, etc.</li>
                <li><strong>Extensions optionnelles :</strong> cURL, GD, ZIP</li>
                <li><strong>Permissions :</strong> storage/, bootstrap/cache/, .env</li>
                <li><strong>Interface :</strong> Statuts colorés (Vert=OK, Orange=Warning, Rouge=Erreur)</li>
            </ul>
        </div>
        
        <div class="test-section">
            <h3>🚀 Actions de Test</h3>
            <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                <a href="install/install_new.php" class="btn btn-primary">
                    Tester l'installateur complet
                </a>
                <a href="install/install_new.php?step=2" class="btn btn-secondary">
                    Tester les prérequis système
                </a>
                <a href="install/install_new.php?force=1" class="btn btn-warning">
                    Forcer la réinstallation
                </a>
            </div>
        </div>
        
        <div class="test-section">
            <h3>📝 Notes Techniques</h3>
            <div style="background: #f8f9fa; padding: 15px; border-radius: 8px;">
                <p><strong>Fichiers modifiés :</strong></p>
                <ul>
                    <li><code>public/install/functions/language.php</code> - Ajout traductions prérequis</li>
                    <li><code>public/install/functions/ui.php</code> - Nouvelle étape 2 + réorganisation</li>
                    <li><code>public/install/install_new.php</code> - Logique des 5 étapes</li>
                    <li><code>public/install/assets/css/install.css</code> - Styles prérequis système</li>
                    <li><code>public/install/templates/header.php</code> - Indicateur 5 étapes</li>
                </ul>
            </div>
        </div>
    </div>
</body>
</html> 