<?php

// Chemin vers le fichier .env
$envFile = __DIR__ . '/.env';

// Vérifier si le fichier existe
if (!file_exists($envFile)) {
    die("Le fichier .env n'existe pas.\n");
}

// Lire le contenu du fichier
$content = file_get_contents($envFile);

// Clé de licence à configurer
$licenseKey = 'WA6I-SETH-ZR6L-D8CT';

// Vérifier si la clé existe déjà
if (preg_match('/INSTALLATION_LICENSE_KEY=([^\n]*)/', $content, $matches)) {
    // Remplacer la valeur existante
    $content = preg_replace('/INSTALLATION_LICENSE_KEY=([^\n]*)/', "INSTALLATION_LICENSE_KEY={$licenseKey}", $content);
    echo "Clé de licence mise à jour dans le fichier .env\n";
} else {
    // Ajouter la clé si elle n'existe pas
    $content .= "\nINSTALLATION_LICENSE_KEY={$licenseKey}\n";
    echo "Clé de licence ajoutée au fichier .env\n";
}

// Écrire le contenu mis à jour dans le fichier
file_put_contents($envFile, $content);

// Exécuter les commandes Artisan pour vider les caches
echo "Vidage des caches...\n";

// Vider le cache de configuration
system('php artisan config:clear');
echo "Cache de configuration vidé\n";

// Vider le cache de l'application
system('php artisan cache:clear');
echo "Cache de l'application vidé\n";

// Vider le cache des vues
system('php artisan view:clear');
echo "Cache des vues vidé\n";

// Vider le cache des routes
system('php artisan route:clear');
echo "Cache des routes vidé\n";

// Mettre à jour la variable d'environnement en mémoire
if (function_exists('putenv')) {
    putenv("INSTALLATION_LICENSE_KEY={$licenseKey}");
    echo "Variable d'environnement mise à jour en mémoire\n";
}

// Régénérer le cache de configuration
system('php artisan config:cache');
echo "Cache de configuration régénéré\n";

echo "\nOpération terminée. La clé de licence a été configurée et les caches ont été vidés.\n";