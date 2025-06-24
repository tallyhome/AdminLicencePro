<?php
// SOLUTION FINALE SIMPLE
$results = [];

if ($_POST["fix"] ?? false) {
    // 1. Créer .env avec permissions maximales
    $env = "../.env";
    file_put_contents($env, "APP_NAME=AdminLicence\nAPP_ENV=production\nAPP_DEBUG=false\nAPP_URL=https://adminlicence.eu\n\nDB_CONNECTION=mysql\nDB_HOST=127.0.0.1\nDB_PORT=3306\nDB_DATABASE=\nDB_USERNAME=\nDB_PASSWORD=\n\nCACHE_DRIVER=file\nSESSION_DRIVER=file");
    chmod($env, 0777);
    $results[] = "✅ .env créé avec permissions 777";
    
    // 2. Patch direct installateur
    $install = "install/install_new.php";
    $content = file_get_contents($install);
    
    // Remplacer la gestion session étape 3
    $content = str_replace(
        "\$_SESSION[\"db_config\"] = [",
        "\$_SESSION[\"db_host\"] = \$_POST[\"db_host\"];\n\$_SESSION[\"db_name\"] = \$_POST[\"db_name\"];\n\$_SESSION[\"db_user\"] = \$_POST[\"db_user\"];\n\$_SESSION[\"db_config\"] = [",
        $content
    );
    
    // Remplacer la gestion session étape 4  
    $content = str_replace(
        "\$_SESSION[\"admin_config\"] = [",
        "\$_SESSION[\"admin_name\"] = \$_POST[\"admin_name\"];\n\$_SESSION[\"admin_email\"] = \$_POST[\"admin_email\"];\n\$_SESSION[\"admin_config\"] = [",
        $content
    );
    
    file_put_contents($install, $content);
    $results[] = "✅ Installateur patché";
}

echo "<!DOCTYPE html><html><head><title>Fix Final</title><style>body{font-family:Arial;margin:20px;}.success{background:#d4edda;padding:15px;margin:10px 0;border-radius:5px;}.btn{padding:15px 30px;background:#dc3545;color:white;border:none;border-radius:5px;cursor:pointer;}</style></head><body>";
echo "<h1>🔥 Solution Finale Simple</h1>";

foreach($results as $r) echo "<div class=\"success\">$r</div>";

if(empty($results)) {
    echo "<form method=\"POST\"><button name=\"fix\" value=\"1\" class=\"btn\">🔧 CORRIGER MAINTENANT</button></form>";
}

echo "<p><a href=\"install/install_new.php\">🔙 Tester installateur</a></p>";
echo "<p>État .env: " . (is_writable("../.env") ? "✅ OK" : "❌ Erreur") . "</p>";
echo "</body></html>";
?>
