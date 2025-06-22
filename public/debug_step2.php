<?php
session_start();

echo "<h1>🔍 Debug Step 2 - Analyse du problème</h1>";

echo "<h2>📊 État de la session :</h2>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

echo "<h2>📝 Variables POST :</h2>";
echo "<pre>";
print_r($_POST);
echo "</pre>";

echo "<h2>📝 Variables GET :</h2>";
echo "<pre>";
print_r($_GET);
echo "</pre>";

echo "<h2>🔧 Tests de validation :</h2>";

// Test 1: Vérifier si la licence est valide
echo "<p><strong>Test 1 - Licence valide :</strong> ";
if (isset($_SESSION["license_valid"]) && $_SESSION["license_valid"]) {
    echo "<span style=\"color: green;\">✅ OUI</span>";
} else {
    echo "<span style=\"color: red;\">❌ NON</span>";
}
echo "</p>";

// Test 2: Vérifier la clé de licence
echo "<p><strong>Test 2 - Clé de licence :</strong> ";
if (isset($_SESSION["license_key"])) {
    echo "<span style=\"color: green;\">✅ " . $_SESSION["license_key"] . "</span>";
} else {
    echo "<span style=\"color: red;\">❌ MANQUANTE</span>";
}
echo "</p>";

// Simuler une licence valide pour le test
echo "<h2>🧪 Simulation licence valide :</h2>";
$_SESSION["license_valid"] = true;
$_SESSION["license_key"] = "TEST-DEMO-KEY1-2345";
$_SESSION["license_data"] = ["status" => "valid", "test" => true];

echo "<p><span style=\"color: green;\">✅ Licence simulée ajoutée en session</span></p>";

echo "<h2>🚀 Actions de test :</h2>";
echo "<a href=\"install/install_new.php?step=1\" style=\"display: inline-block; padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; margin: 5px;\">Tester Step 1</a>";
echo "<a href=\"install/install_new.php?step=2\" style=\"display: inline-block; padding: 10px 20px; background: #28a745; color: white; text-decoration: none; border-radius: 5px; margin: 5px;\">Tester Step 2</a>";
echo "<a href=\"install/install_new.php?step=3\" style=\"display: inline-block; padding: 10px 20px; background: #ffc107; color: black; text-decoration: none; border-radius: 5px; margin: 5px;\">Tester Step 3</a>";

echo "<h2>📋 Test manuel Step 2 :</h2>";
echo "<form method=\"post\" action=\"install/install_new.php\" style=\"background: #f8f9fa; padding: 20px; border-radius: 8px;\">
    <input type=\"hidden\" name=\"step\" value=\"2\">
    <p><strong>Formulaire Step 2 (Prérequis système)</strong></p>
    <button type=\"submit\" style=\"padding: 10px 20px; background: #28a745; color: white; border: none; border-radius: 5px; cursor: pointer;\">
        Soumettre Step 2
    </button>
</form>";
?>
