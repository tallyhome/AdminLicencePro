<?php
// Script simple pour vérifier les traductions manquantes
$languages = ['fr', 'en', 'es', 'de', 'it', 'pt', 'nl', 'ru', 'zh', 'ja', 'tr', 'ar'];
$missing = [];

foreach ($languages as $lang) {
    $file = "../resources/locales/$lang/translation.json";
    if (file_exists($file)) {
        $data = json_decode(file_get_contents($file), true);
        if (!isset($data['common']['system_requirements'])) {
            $missing[] = $lang;
        }
    } else {
        $missing[] = $lang . ' (fichier manquant)';
    }
}

echo "Langues manquantes system_requirements:\n";
foreach ($missing as $lang) {
    echo "- $lang\n";
}

if (empty($missing)) {
    echo "✅ Toutes les langues ont la clé system_requirements\n";
} 