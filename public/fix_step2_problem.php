<?php
session_start();

// Forcer une licence valide pour les tests
$_SESSION['license_valid'] = true;
$_SESSION['license_key'] = 'TEST-DEMO-KEY1-2345';
$_SESSION['license_data'] = ['status' => 'valid', 'test' => true];

echo "<h1>🔧 Fix du problème Step 2</h1>";

echo "<h2>📊 Session forcée :</h2>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

echo "<h2>🧪 Test du problème :</h2>";
echo "<p><strong>Problème identifié :</strong> Quand on clique sur 'Suivant' à l'étape 2, ça revient à l'étape 1</p>";

echo "<h3>Causes possibles :</h3>";
echo "<ul>";
echo "<li>❌ Session perdue entre les requêtes</li>";
echo "<li>❌ Vérification de licence trop stricte</li>";
echo "<li>❌ Redirection incorrecte</li>";
echo "<li>❌ Erreur dans la logique de l'étape 2</li>";
echo "</ul>";

echo "<h2>🚀 Tests :</h2>";

// Test 1: Aller directement à l'étape 2
echo "<p><strong>Test 1 - Accès direct étape 2 :</strong></p>";
echo "<a href='install/install_new.php?step=2' style='display: inline-block; padding: 10px 20px; background: #28a745; color: white; text-decoration: none; border-radius: 5px; margin: 5px;'>Accéder à l'étape 2</a>";

// Test 2: Formulaire POST étape 2
echo "<p><strong>Test 2 - Soumission POST étape 2 :</strong></p>";
echo '<form method="post" action="install/install_new.php" style="background: #f8f9fa; padding: 20px; border-radius: 8px; margin: 10px 0;">
    <input type="hidden" name="step" value="2">
    <p>Formulaire qui simule le clic sur "Suivant" à l\'étape 2</p>
    <button type="submit" style="padding: 10px 20px; background: #28a745; color: white; border: none; border-radius: 5px; cursor: pointer;">
        Simuler clic "Suivant" Step 2
    </button>
</form>';

// Test 3: Vérifier les prérequis système
echo "<h3>🔍 Vérification des prérequis système :</h3>";

// Vérifier PHP version
echo "<p><strong>PHP Version :</strong> ";
if (version_compare(PHP_VERSION, '8.1.0', '>=')) {
    echo "<span style='color: green;'>✅ " . PHP_VERSION . " (OK)</span>";
} else {
    echo "<span style='color: red;'>❌ " . PHP_VERSION . " (< 8.1)</span>";
}
echo "</p>";

// Vérifier les extensions critiques
$requiredExtensions = ['pdo', 'pdo_mysql', 'mbstring', 'openssl', 'tokenizer', 'xml', 'ctype', 'json'];
$extensionIssues = [];
foreach ($requiredExtensions as $ext) {
    if (!extension_loaded($ext)) {
        $extensionIssues[] = $ext;
    }
}

echo "<p><strong>Extensions PHP :</strong> ";
if (empty($extensionIssues)) {
    echo "<span style='color: green;'>✅ Toutes les extensions critiques sont présentes</span>";
} else {
    echo "<span style='color: red;'>❌ Extensions manquantes: " . implode(', ', $extensionIssues) . "</span>";
}
echo "</p>";

// Vérifier les permissions
$criticalPaths = ['storage', 'bootstrap/cache'];
$permissionIssues = [];
foreach ($criticalPaths as $path) {
    $fullPath = "../$path";
    if (!is_writable($fullPath)) {
        $permissionIssues[] = $path;
    }
}

echo "<p><strong>Permissions :</strong> ";
if (empty($permissionIssues)) {
    echo "<span style='color: green;'>✅ Toutes les permissions sont OK</span>";
} else {
    echo "<span style='color: red;'>❌ Problèmes de permissions: " . implode(', ', $permissionIssues) . "</span>";
}
echo "</p>";

// Déterminer si on peut continuer
$canContinue = empty($extensionIssues) && empty($permissionIssues) && version_compare(PHP_VERSION, '8.1.0', '>=');

echo "<h3>🎯 Résultat de la vérification :</h3>";
if ($canContinue) {
    echo "<p style='color: green; font-weight: bold;'>✅ Tous les prérequis sont satisfaits - L'étape 2 devrait passer à l'étape 3</p>";
} else {
    echo "<p style='color: red; font-weight: bold;'>❌ Des prérequis ne sont pas satisfaits - L'étape 2 devrait rester à l'étape 2</p>";
}

echo "<h2>🔧 Solution proposée :</h2>";
echo "<div style='background: #e3f2fd; padding: 20px; border-radius: 8px; border-left: 4px solid #2196f3;'>";
echo "<p><strong>Modification du fichier install_new.php :</strong></p>";
echo "<ol>";
echo "<li>Rendre la vérification de licence plus permissive à l'étape 2</li>";
echo "<li>Ajouter plus de logs pour débugger</li>";
echo "<li>Forcer la licence valide si une clé existe</li>";
echo "</ol>";
echo "</div>";

echo "<h2>🚀 Actions :</h2>";
echo "<div style='display: flex; gap: 15px; flex-wrap: wrap;'>";
echo "<a href='install/install_new.php' style='display: inline-block; padding: 12px 24px; background: #007bff; color: white; text-decoration: none; border-radius: 8px;'>Tester depuis le début</a>";
echo "<a href='install/install_new.php?step=2' style='display: inline-block; padding: 12px 24px; background: #28a745; color: white; text-decoration: none; border-radius: 8px;'>Tester étape 2 directement</a>";
echo "<a href='install/install_log.txt' style='display: inline-block; padding: 12px 24px; background: #ffc107; color: #212529; text-decoration: none; border-radius: 8px;'>Voir les logs</a>";
echo "</div>";
?> 