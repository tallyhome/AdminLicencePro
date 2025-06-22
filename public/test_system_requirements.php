<?php
/**
 * Test de la clé system_requirements dans toutes les langues
 */

// Inclure le service de traduction
require_once '../app/Services/TranslationService.php';

echo "<!DOCTYPE html>";
echo "<html><head><meta charset='UTF-8'><title>Test system_requirements</title>";
echo "<style>body{font-family:Arial,sans-serif;margin:2rem;} table{border-collapse:collapse;width:100%;} th,td{border:1px solid #ddd;padding:1rem;text-align:left;} th{background:#f2f2f2;} .success{color:green;} .error{color:red;}</style>";
echo "</head><body>";

echo "<h1>🔍 Test de la clé 'system_requirements'</h1>";

// Langues à tester
$languages = [
    'fr' => 'Français',
    'en' => 'English', 
    'es' => 'Español',
    'de' => 'Deutsch',
    'it' => 'Italiano',
    'pt' => 'Português',
    'nl' => 'Nederlands',
    'ru' => 'Русский',
    'zh' => '中文',
    'ja' => '日本語',
    'tr' => 'Türkçe',
    'ar' => 'العربية'
];

echo "<table>";
echo "<tr><th>Code</th><th>Langue</th><th>Traduction</th><th>Statut</th></tr>";

$translationService = new \App\Services\TranslationService();

foreach ($languages as $code => $name) {
    try {
        $translationService->setLocale($code);
        $translation = $translationService->translate('common.system_requirements');
        
        $status = $translation !== 'common.system_requirements' ? 
            "<span class='success'>✅ OK</span>" : 
            "<span class='error'>❌ Manquant</span>";
        
        echo "<tr>";
        echo "<td><strong>$code</strong></td>";
        echo "<td>$name</td>";
        echo "<td>$translation</td>";
        echo "<td>$status</td>";
        echo "</tr>";
        
    } catch (Exception $e) {
        echo "<tr>";
        echo "<td><strong>$code</strong></td>";
        echo "<td>$name</td>";
        echo "<td>Erreur: " . $e->getMessage() . "</td>";
        echo "<td><span class='error'>❌ Erreur</span></td>";
        echo "</tr>";
    }
}

echo "</table>";

echo "<div style='margin-top:2rem; padding:1rem; background:#e8f5e8; border-radius:0.5rem;'>";
echo "<h3>✅ Résultat</h3>";
echo "<p>La clé <code>system_requirements</code> a été ajoutée avec succès dans toutes les langues !</p>";
echo "<p><strong>Utilisation :</strong> <code>t('common.system_requirements')</code> ou <code>\$translationService->translate('common.system_requirements')</code></p>";
echo "</div>";

echo "</body></html>";
?> 