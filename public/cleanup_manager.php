<?php
/**
 * Gestionnaire de nettoyage du projet AdminLicence
 * Analyse et supprime les fichiers temporaires, logs, tests, etc.
 */

// Configuration de sécurité
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Chemin racine du projet
$projectRoot = dirname(__DIR__);

// Action demandée
$action = $_GET['action'] ?? 'analyze';
$section = $_GET['section'] ?? '';

// Définition des catégories de fichiers à nettoyer
$cleanupCategories = [
    'logs' => [
        'name' => '📁 Fichiers de logs',
        'description' => 'Fichiers de journalisation et de débogage',
        'patterns' => [
            'public/install/logs/*.log',
            'public/install/debug_*.log',
            'public/install/install_log.txt',
            'storage/logs/*.log'
        ],
        'files' => []
    ],
    'backups' => [
        'name' => '💾 Fichiers de sauvegarde',
        'description' => 'Sauvegardes temporaires et fichiers .backup',
        'patterns' => [
            'public/install/logs/backups/*',
            'app/Helpers/*.backup.*',
            '**/*.backup',
            '**/*.bak'
        ],
        'files' => []
    ],
    'test_files' => [
        'name' => '🧪 Fichiers de test et diagnostic',
        'description' => 'Scripts de test et de diagnostic temporaires',
        'patterns' => [
            'public/test_*.php',
            'public/diagnostic_*.php',
            'public/api-diagnostic.php',
            'public/api-key-test.php',
            'public/check_*.php',
            'public/install/test_*.php',
            'public/install/diagnostic_*.php',
            'public/install/debug_*.php',
            'public/install/direct-api-test.php'
        ],
        'files' => []
    ],
    'fix_files' => [
        'name' => '🔧 Fichiers de correction temporaires',
        'description' => 'Scripts de correction et de réparation temporaires',
        'patterns' => [
            'public/fix_*.php',
            'public/emergency_*.php',
            'public/final_*.php',
            'public/quick_*.php',
            'public/ultimate_*.php',
            'public/solution_*.php',
            'public/production_*.php',
            'public/install_*_fix.php',
            'public/install/fix_*.php',
            'public/install/install_*_fix*.php',
            'public/install/solution_*.php',
            'public/update_*.php'
        ],
        'files' => []
    ],
    'temp_files' => [
        'name' => '📄 Fichiers temporaires',
        'description' => 'Fichiers de session et temporaires',
        'patterns' => [
            'public/install/session_backup_*.json',
            'public/home_direct.php',
            'public/guide_upload_assets.html',
            'public/cleanup_*.php'
        ],
        'files' => []
    ],
    'cache_files' => [
        'name' => '🗂️ Fichiers de cache',
        'description' => 'Cache Laravel et fichiers compilés',
        'patterns' => [
            'storage/framework/views/*.php',
            'storage/framework/cache/data/*',
            'bootstrap/cache/*',
            '.vite/deps_temp_*/*'
        ],
        'files' => []
    ],
    'dev_files' => [
        'name' => '📦 Fichiers de développement',
        'description' => 'Archives et fichiers de développement',
        'patterns' => [
            'AdminLicence-*.zip',
            'add_missing_translations.php',
            'database/adminlicenceteste.sql'
        ],
        'files' => []
    ]
];

/**
 * Fonction pour scanner les fichiers selon les patterns
 */
function scanFiles($pattern, $projectRoot) {
    $files = [];
    
    // Convertir le pattern en chemin absolu
    $fullPattern = $projectRoot . '/' . $pattern;
    
    // Utiliser glob pour les patterns simples
    if (strpos($pattern, '**') === false) {
        $matches = glob($fullPattern);
        if ($matches) {
            foreach ($matches as $file) {
                if (is_file($file)) {
                    $files[] = $file;
                }
            }
        }
    } else {
        // Pour les patterns récursifs, utiliser une approche manuelle
        $basePattern = str_replace('**/', '', $pattern);
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($projectRoot, RecursiveDirectoryIterator::SKIP_DOTS)
        );
        
        foreach ($iterator as $file) {
            if ($file->isFile()) {
                $relativePath = str_replace($projectRoot . '/', '', $file->getPathname());
                $relativePath = str_replace('\\', '/', $relativePath);
                
                if (fnmatch($basePattern, basename($file->getPathname()))) {
                    $files[] = $file->getPathname();
                }
            }
        }
    }
    
    return $files;
}

/**
 * Analyser tous les fichiers
 */
function analyzeFiles($categories, $projectRoot) {
    foreach ($categories as $key => &$category) {
        $category['files'] = [];
        $category['total_size'] = 0;
        
        foreach ($category['patterns'] as $pattern) {
            $files = scanFiles($pattern, $projectRoot);
            foreach ($files as $file) {
                if (file_exists($file) && !in_array($file, $category['files'])) {
                    $category['files'][] = $file;
                    $category['total_size'] += filesize($file);
                }
            }
        }
        
        $category['count'] = count($category['files']);
    }
    
    return $categories;
}

/**
 * Supprimer les fichiers d'une section
 */
function deleteSection($section, $categories, $projectRoot) {
    $deleted = [];
    $errors = [];
    
    if (!isset($categories[$section])) {
        return ['deleted' => [], 'errors' => ['Section non trouvée']];
    }
    
    foreach ($categories[$section]['files'] as $file) {
        if (file_exists($file)) {
            if (unlink($file)) {
                $deleted[] = str_replace($projectRoot . '/', '', $file);
            } else {
                $errors[] = 'Impossible de supprimer: ' . str_replace($projectRoot . '/', '', $file);
            }
        }
    }
    
    return ['deleted' => $deleted, 'errors' => $errors];
}

/**
 * Formater la taille en octets
 */
function formatBytes($size, $precision = 2) {
    $units = ['B', 'KB', 'MB', 'GB'];
    for ($i = 0; $size > 1024 && $i < count($units) - 1; $i++) {
        $size /= 1024;
    }
    return round($size, $precision) . ' ' . $units[$i];
}

// Traitement des actions
if ($action === 'delete' && !empty($section)) {
    $categories = analyzeFiles($cleanupCategories, $projectRoot);
    $result = deleteSection($section, $categories, $projectRoot);
    
    header('Content-Type: application/json');
    echo json_encode($result);
    exit;
}

// Analyse des fichiers
$categories = analyzeFiles($cleanupCategories, $projectRoot);
$totalFiles = array_sum(array_column($categories, 'count'));
$totalSize = array_sum(array_column($categories, 'total_size'));

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionnaire de nettoyage - AdminLicence</title>
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
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .header {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .header h1 {
            font-size: 2.5em;
            margin-bottom: 10px;
        }
        
        .header p {
            opacity: 0.9;
            font-size: 1.1em;
        }
        
        .stats {
            background: #f8f9fa;
            padding: 20px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }
        
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }
        
        .stat-number {
            font-size: 2em;
            font-weight: bold;
            color: #e74c3c;
        }
        
        .stat-label {
            color: #7f8c8d;
            margin-top: 5px;
        }
        
        .content {
            padding: 30px;
        }
        
        .section {
            background: #f8f9fa;
            border-radius: 10px;
            margin-bottom: 20px;
            overflow: hidden;
            border: 1px solid #e9ecef;
        }
        
        .section-header {
            background: white;
            padding: 20px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .section-info h3 {
            color: #2c3e50;
            margin-bottom: 5px;
        }
        
        .section-meta {
            color: #7f8c8d;
            font-size: 0.9em;
        }
        
        .section-stats {
            text-align: right;
        }
        
        .file-count {
            font-size: 1.5em;
            font-weight: bold;
            color: #e74c3c;
        }
        
        .file-size {
            color: #7f8c8d;
            font-size: 0.9em;
        }
        
        .section-content {
            padding: 20px;
            display: none;
        }
        
        .section.expanded .section-content {
            display: block;
        }
        
        .file-list {
            max-height: 300px;
            overflow-y: auto;
            background: white;
            border-radius: 5px;
            border: 1px solid #e9ecef;
        }
        
        .file-item {
            padding: 10px 15px;
            border-bottom: 1px solid #f1f3f4;
            font-family: 'Courier New', monospace;
            font-size: 0.9em;
        }
        
        .file-item:last-child {
            border-bottom: none;
        }
        
        .file-item:hover {
            background: #f8f9fa;
        }
        
        .actions {
            margin-top: 15px;
            display: flex;
            gap: 10px;
        }
        
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn-toggle {
            background: #3498db;
            color: white;
        }
        
        .btn-toggle:hover {
            background: #2980b9;
        }
        
        .btn-delete {
            background: #e74c3c;
            color: white;
        }
        
        .btn-delete:hover {
            background: #c0392b;
        }
        
        .btn-delete:disabled {
            background: #bdc3c7;
            cursor: not-allowed;
        }
        
        .alert {
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
        }
        
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .loading {
            display: none;
            text-align: center;
            padding: 20px;
        }
        
        .spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #3498db;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 0 auto 10px;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🧹 Gestionnaire de nettoyage</h1>
            <p>Analysez et supprimez les fichiers temporaires du projet AdminLicence</p>
        </div>
        
        <div class="stats">
            <div class="stat-card">
                <div class="stat-number"><?= $totalFiles ?></div>
                <div class="stat-label">Fichiers à nettoyer</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= formatBytes($totalSize) ?></div>
                <div class="stat-label">Espace à libérer</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= count($categories) ?></div>
                <div class="stat-label">Catégories</div>
            </div>
        </div>
        
        <div class="content">
            <div id="alerts"></div>
            
            <?php foreach ($categories as $key => $category): ?>
                <div class="section" data-section="<?= $key ?>">
                    <div class="section-header">
                        <div class="section-info">
                            <h3><?= $category['name'] ?></h3>
                            <div class="section-meta"><?= $category['description'] ?></div>
                        </div>
                        <div class="section-stats">
                            <div class="file-count"><?= $category['count'] ?></div>
                            <div class="file-size"><?= formatBytes($category['total_size']) ?></div>
                        </div>
                    </div>
                    
                    <?php if ($category['count'] > 0): ?>
                        <div class="section-content">
                            <div class="file-list">
                                <?php foreach ($category['files'] as $file): ?>
                                    <div class="file-item">
                                        <?= str_replace($projectRoot . '/', '', $file) ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            
                            <div class="actions">
                                <button class="btn btn-toggle" onclick="toggleSection('<?= $key ?>')">
                                    Masquer les fichiers
                                </button>
                                <button class="btn btn-delete" onclick="deleteSection('<?= $key ?>')">
                                    Supprimer cette section (<?= $category['count'] ?> fichiers)
                                </button>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
            
            <div class="loading" id="loading">
                <div class="spinner"></div>
                <p>Suppression en cours...</p>
            </div>
        </div>
    </div>
    
    <script>
        function toggleSection(sectionKey) {
            const section = document.querySelector(`[data-section="${sectionKey}"]`);
            const btn = section.querySelector('.btn-toggle');
            
            if (section.classList.contains('expanded')) {
                section.classList.remove('expanded');
                btn.textContent = 'Afficher les fichiers';
            } else {
                section.classList.add('expanded');
                btn.textContent = 'Masquer les fichiers';
            }
        }
        
        function deleteSection(sectionKey) {
            if (!confirm('Êtes-vous sûr de vouloir supprimer tous les fichiers de cette section ?')) {
                return;
            }
            
            const loading = document.getElementById('loading');
            const section = document.querySelector(`[data-section="${sectionKey}"]`);
            const deleteBtn = section.querySelector('.btn-delete');
            
            loading.style.display = 'block';
            deleteBtn.disabled = true;
            
            fetch(`?action=delete&section=${sectionKey}`)
                .then(response => response.json())
                .then(data => {
                    loading.style.display = 'none';
                    
                    let alertHtml = '';
                    
                    if (data.deleted.length > 0) {
                        alertHtml += `<div class="alert alert-success">`;
                        alertHtml += `<strong>Succès!</strong> ${data.deleted.length} fichier(s) supprimé(s):<br>`;
                        alertHtml += data.deleted.map(file => `• ${file}`).join('<br>');
                        alertHtml += `</div>`;
                    }
                    
                    if (data.errors.length > 0) {
                        alertHtml += `<div class="alert alert-error">`;
                        alertHtml += `<strong>Erreurs:</strong><br>`;
                        alertHtml += data.errors.join('<br>');
                        alertHtml += `</div>`;
                    }
                    
                    document.getElementById('alerts').innerHTML = alertHtml;
                    
                    // Recharger la page après 3 secondes
                    setTimeout(() => {
                        window.location.reload();
                    }, 3000);
                })
                .catch(error => {
                    loading.style.display = 'none';
                    deleteBtn.disabled = false;
                    
                    document.getElementById('alerts').innerHTML = 
                        `<div class="alert alert-error"><strong>Erreur:</strong> ${error.message}</div>`;
                });
        }
        
        // Auto-expand sections with files
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.section').forEach(section => {
                const fileCount = parseInt(section.querySelector('.file-count').textContent);
                if (fileCount > 0) {
                    section.classList.add('expanded');
                    const btn = section.querySelector('.btn-toggle');
                    if (btn) btn.textContent = 'Masquer les fichiers';
                }
            });
        });
    </script>
</body>
</html>