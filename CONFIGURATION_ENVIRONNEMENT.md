# Configuration de l'Environnement - AdminLicence

## Étapes de Configuration

### 1. Configuration du Fichier .env

Créez un fichier `.env` à la racine du projet avec le contenu suivant :

```env
# Application
APP_NAME=AdminLicence
APP_ENV=local
APP_KEY=base64:VOTRE_CLE_GENEREE_ICI
APP_DEBUG=true
APP_URL=http://localhost:8000

# Base de données
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=adminlicence
DB_USERNAME=root
DB_PASSWORD=votre_mot_de_passe

# Stripe Configuration (Mode Test)
STRIPE_KEY=pk_test_VOTRE_CLE_PUBLIQUE_STRIPE
STRIPE_SECRET=sk_test_VOTRE_CLE_SECRETE_STRIPE
STRIPE_WEBHOOK_SECRET=whsec_VOTRE_SECRET_WEBHOOK

# PayPal Configuration (Mode Sandbox)
PAYPAL_CLIENT_ID=VOTRE_CLIENT_ID_PAYPAL
PAYPAL_CLIENT_SECRET=VOTRE_SECRET_PAYPAL
PAYPAL_MODE=sandbox
PAYPAL_WEBHOOK_ID=VOTRE_WEBHOOK_ID_PAYPAL

# Email (optionnel pour les tests)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=votre_email@gmail.com
MAIL_PASSWORD=votre_mot_de_passe_app
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=votre_email@gmail.com
MAIL_FROM_NAME="${APP_NAME}"

# Autres
LOG_CHANNEL=stack
LOG_LEVEL=debug
CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync
```

### 2. Génération de la Clé d'Application

```bash
php artisan key:generate
```

### 3. Configuration de la Base de Données

1. **Créer la base de données :**
```sql
CREATE DATABASE adminlicence CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

2. **Lancer les migrations :**
```bash
php artisan migrate
```

3. **Lancer les seeders (optionnel) :**
```bash
php artisan db:seed --class=PaymentMethodSeeder
```

### 4. Configuration Stripe

1. **Créer un compte Stripe :**
   - Allez sur https://dashboard.stripe.com/register
   - Créez un compte développeur

2. **Récupérer les clés API :**
   - Connectez-vous au Dashboard Stripe
   - Allez dans "Développeurs" > "Clés API"
   - Copiez la clé publique (`pk_test_...`) et la clé secrète (`sk_test_...`)

3. **Configurer les webhooks :**
   - Allez dans "Développeurs" > "Webhooks"
   - Créez un endpoint : `http://votre-domaine.com/stripe/webhook`
   - Sélectionnez les événements : `payment_intent.succeeded`, `customer.subscription.updated`, etc.

### 5. Configuration PayPal

1. **Créer un compte PayPal Developer :**
   - Allez sur https://developer.paypal.com/
   - Créez une application

2. **Récupérer les identifiants :**
   - Client ID et Client Secret depuis le dashboard
   - Configurez en mode "Sandbox" pour les tests

3. **Configurer les webhooks :**
   - Créez un webhook endpoint : `http://votre-domaine.com/paypal/webhook`

### 6. Permissions et Sécurité

```bash
# Donner les permissions appropriées
chmod -R 755 storage bootstrap/cache
chmod -R 777 storage/logs

# Nettoyer les caches
php artisan config:clear
php artisan route:clear
php artisan cache:clear
```

## Installation des Dépendances

### 1. Dépendances PHP

```bash
# Installer toutes les dépendances
composer install --no-dev --optimize-autoloader

# Installer Stripe (déjà fait)
composer require stripe/stripe-php

# Installer PayPal (si le problème SSL est résolu)
composer require paypal/paypal-checkout-sdk
```

### 2. Dépendances JavaScript

```bash
# Installer les dépendances Node.js
npm install

# Compiler les assets
npm run build
```

## Résolution des Problèmes SSL

### Option 1: Désactiver SSL temporairement

```bash
composer config --global disable-tls true
composer require paypal/paypal-checkout-sdk
composer config --global disable-tls false
```

### Option 2: Configurer les certificats

1. **Télécharger cacert.pem :**
```bash
curl -o cacert.pem https://curl.se/ca/cacert.pem
```

2. **Configurer PHP :**
Ajouter dans `php.ini` :
```ini
curl.cainfo = "C:\path\to\cacert.pem"
openssl.cafile = "C:\path\to\cacert.pem"
```

### Option 3: Utiliser le script PowerShell

```powershell
.\install-paypal-sdk.ps1
```

## Tests de Fonctionnement

### 1. Vérifier les Routes

```bash
php artisan route:list | Select-String "payment-methods"
```

### 2. Tester l'Application

```bash
# Démarrer le serveur
php artisan serve

# Tester les URLs :
# http://localhost:8000/client/payment-methods
# http://localhost:8000/client/billing/subscription
# http://localhost:8000/client/billing/invoices
```

### 3. Vérifier les Logs

```bash
# Voir les logs en temps réel
tail -f storage/logs/laravel.log
```

## Variables d'Environnement Importantes

| Variable | Description | Exemple |
|----------|-------------|---------|
| `STRIPE_KEY` | Clé publique Stripe | `pk_test_...` |
| `STRIPE_SECRET` | Clé secrète Stripe | `sk_test_...` |
| `PAYPAL_CLIENT_ID` | ID client PayPal | `AXxxx...` |
| `PAYPAL_CLIENT_SECRET` | Secret PayPal | `ELxxx...` |
| `PAYPAL_MODE` | Mode PayPal | `sandbox` ou `live` |
| `APP_URL` | URL de l'application | `http://localhost:8000` |

## Commandes Utiles

```bash
# Nettoyer tout
php artisan optimize:clear

# Vérifier la configuration
php artisan config:show

# Tester la base de données
php artisan migrate:status

# Lancer les tests
php artisan test

# Voir les routes
php artisan route:list

# Générer des données de test
php artisan tinker
```

## Sécurité en Production

⚠️ **Important pour la production :**

1. **Changer les clés API :**
   - Utiliser les clés de production Stripe (`pk_live_...`)
   - Utiliser le mode `live` pour PayPal

2. **Configurer HTTPS :**
   - Obligatoire pour les webhooks
   - Nécessaire pour la sécurité des paiements

3. **Sécuriser les variables :**
   - Ne jamais committer le fichier `.env`
   - Utiliser des variables d'environnement serveur

4. **Configurer les domaines :**
   - Restreindre les domaines autorisés dans Stripe/PayPal
   - Configurer les CORS appropriés

## Support

En cas de problème :

1. **Vérifier les logs :** `storage/logs/laravel.log`
2. **Vérifier la configuration :** `php artisan config:show`
3. **Nettoyer les caches :** `php artisan optimize:clear`
4. **Consulter la documentation :** `docs/PAYMENT_INTEGRATION.md`

L'application est maintenant prête à être utilisée avec Stripe. PayPal nécessite la résolution du problème SSL pour être pleinement fonctionnel. 