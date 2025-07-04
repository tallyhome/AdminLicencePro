# Script d'installation du SDK PayPal pour AdminLicence
# Résolution des problèmes SSL sous Windows

Write-Host "=== Installation SDK PayPal pour AdminLicence ===" -ForegroundColor Green

# Vérifier si Composer est disponible
if (-not (Get-Command composer -ErrorAction SilentlyContinue)) {
    Write-Host "Erreur: Composer n'est pas installé ou pas dans le PATH" -ForegroundColor Red
    exit 1
}

# Méthode 1: Essayer d'installer avec les options SSL désactivées
Write-Host "Tentative 1: Installation avec options SSL personnalisées..." -ForegroundColor Yellow

try {
    # Désactiver temporairement la vérification SSL pour Composer
    $env:COMPOSER_DISABLE_NETWORK = "0"
    
    # Essayer avec le SDK PayPal moderne
    Write-Host "Installation de paypal/paypal-checkout-sdk..." -ForegroundColor Cyan
    & composer require paypal/paypal-checkout-sdk --no-interaction --prefer-dist
    
    if ($LASTEXITCODE -eq 0) {
        Write-Host "✅ SDK PayPal installé avec succès!" -ForegroundColor Green
        exit 0
    }
} catch {
    Write-Host "❌ Échec de l'installation automatique" -ForegroundColor Red
}

# Méthode 2: Essayer avec l'ancien SDK
Write-Host "Tentative 2: Installation de l'ancien SDK PayPal..." -ForegroundColor Yellow

try {
    & composer require paypal/rest-api-sdk-php --no-interaction --prefer-dist
    
    if ($LASTEXITCODE -eq 0) {
        Write-Host "✅ SDK PayPal (ancien) installé avec succès!" -ForegroundColor Green
        exit 0
    }
} catch {
    Write-Host "❌ Échec de l'installation de l'ancien SDK" -ForegroundColor Red
}

# Méthode 3: Configuration manuelle des certificats
Write-Host "Tentative 3: Configuration des certificats SSL..." -ForegroundColor Yellow

try {
    # Télécharger le fichier cacert.pem si nécessaire
    $certPath = "$env:TEMP\cacert.pem"
    
    if (-not (Test-Path $certPath)) {
        Write-Host "Téléchargement des certificats SSL..." -ForegroundColor Cyan
        Invoke-WebRequest -Uri "https://curl.se/ca/cacert.pem" -OutFile $certPath -UseBasicParsing
    }
    
    # Configurer Composer pour utiliser ce certificat
    & composer config --global cafile $certPath
    
    # Essayer à nouveau l'installation
    & composer require paypal/paypal-checkout-sdk --no-interaction
    
    if ($LASTEXITCODE -eq 0) {
        Write-Host "✅ SDK PayPal installé après configuration SSL!" -ForegroundColor Green
        exit 0
    }
} catch {
    Write-Host "❌ Échec après configuration SSL" -ForegroundColor Red
}

# Méthode 4: Installation manuelle
Write-Host "Tentative 4: Préparation pour installation manuelle..." -ForegroundColor Yellow

Write-Host @"
❌ L'installation automatique a échoué. Solutions manuelles:

1. SOLUTION TEMPORAIRE - Désactiver SSL:
   composer config --global disable-tls true
   composer require paypal/paypal-checkout-sdk
   composer config --global disable-tls false

2. SOLUTION RECOMMANDÉE - Configurer SSL:
   - Télécharger: https://curl.se/ca/cacert.pem
   - Placer dans: C:\php\extras\ssl\cacert.pem
   - Modifier php.ini: curl.cainfo = "C:\php\extras\ssl\cacert.pem"

3. SOLUTION ALTERNATIVE - Installation manuelle:
   - Télécharger: https://github.com/paypal/Checkout-PHP-SDK/archive/refs/heads/develop.zip
   - Extraire dans: vendor/paypal/paypal-checkout-sdk/
   - Ajouter à composer.json manuellement

4. SOLUTION DOCKER:
   - Utiliser un environnement Docker avec SSL configuré
   - Exemple: docker run --rm -v $(pwd):/app composer require paypal/paypal-checkout-sdk

Pour continuer sans PayPal, l'application fonctionne déjà avec Stripe.
"@ -ForegroundColor Cyan

# Vérifier si Stripe est installé
$stripeInstalled = & composer show stripe/stripe-php 2>$null
if ($stripeInstalled) {
    Write-Host "✅ Stripe SDK est installé et fonctionnel" -ForegroundColor Green
} else {
    Write-Host "⚠️ Stripe SDK n'est pas installé" -ForegroundColor Yellow
}

Write-Host "=== Fin du script d'installation ===" -ForegroundColor Green 