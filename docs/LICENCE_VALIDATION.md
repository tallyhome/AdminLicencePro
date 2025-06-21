# Système de Validation de Licence - AdminLicence

## Problème Identifié

Le système acceptait précédemment toutes les clés de licence soumises car :
1. L'API externe `https://licence.myvcard.fr/api/ultra-simple.php` retournait des erreurs 404
2. La gestion d'erreur permettait le passage en cas d'échec de l'API
3. Aucune validation locale stricte n'était en place

## Solution Implémentée

### Mode Développement (Actuel)

Le système utilise maintenant une validation locale stricte avec des clés prédéfinies :

**Clés de licence valides :**
- `ADMIN-LICE-NCE2-025V` - Licence principale pour AdminLicence
- `TEST-LICE-NCE2-025V` - Licence de test
- `DEMO-LICE-NCE2-025V` - Licence de démonstration

**Avantages :**
- Validation immédiate sans dépendance externe
- Contrôle total sur les licences acceptées
- Logs détaillés pour le débogage
- Pas de risque d'accepter des clés invalides

### Basculer vers le Mode Production

Pour utiliser la vérification API réelle :

1. **Ouvrir le fichier :** `public/install/functions/core.php`

2. **Commenter la section Mode Développement :**
   ```php
   /*
   // MODE DÉVELOPPEMENT : Vérification locale stricte
   // ... (toute la section)
   */
   ```

3. **Décommenter la section Vérification API :**
   ```php
   // VÉRIFICATION API
   $url = "https://licence.myvcard.fr/api/ultra-simple.php";
   // ... (reste du code API)
   ```

4. **Vérifier que l'API fonctionne :**
   - Tester la connectivité vers `https://licence.myvcard.fr/api/ultra-simple.php`
   - Vérifier les logs dans `public/install/logs/install_log.txt`

## Format des Clés de Licence

Toutes les clés doivent respecter le format : `XXXX-XXXX-XXXX-XXXX`
- 4 groupes de 4 caractères alphanumériques
- Séparés par des tirets
- Insensible à la casse (automatiquement converti en majuscules)

## Logs et Débogage

Tous les événements de validation sont enregistrés dans :
`public/install/logs/install_log.txt`

**Types de logs :**
- Début de vérification avec la clé fournie
- Validation du format
- Résultat de la vérification (valide/invalide)
- Erreurs d'API (en mode production)

## Sécurité

- Les clés invalides sont rejetées immédiatement
- Validation du format avant toute vérification
- Logs détaillés pour audit
- Pas de failles de sécurité liées aux erreurs d'API

## Tests

Pour tester le système :

1. **Clé valide :** `ADMIN-LICE-NCE2-025V`
2. **Clé invalide :** `INVALID-KEY-TEST-1234`
3. **Format invalide :** `ABC-DEF` (trop court)
4. **Clé vide :** (champ vide)

## Maintenance

- **Ajouter une nouvelle clé valide :** Modifier le tableau `$validLicenses` dans `core.php`
- **Changer le mode :** Suivre les instructions de basculement ci-dessus
- **Vérifier les logs :** Consulter régulièrement `install_log.txt`

---

**Note :** Ce système garantit qu'aucune clé invalide ne sera acceptée, résolvant ainsi le problème initial où toutes les clés étaient acceptées.