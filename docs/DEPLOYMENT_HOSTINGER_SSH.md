# 🚀 Guide Complet de Déploiement & Administration SSH sur Hostinger
## Rayanova T.D — Export Agroalimentaire International

Ce guide documente la procédure officielle, étape par étape, pour déployer, mettre à jour, inspecter et administrer la plateforme **Rayanova T.D** sur l'infrastructure **Hostinger** via **SSH**.

---

## 📑 Sommaire
1. [Architecture & Prérequis Hostinger](#1-architecture--prérequis-hostinger)
2. [Étape 1 : Préparation sur hPanel (Base de données, PHP, SSH)](#étape-1--préparation-sur-hpanel)
3. [Étape 2 : Configuration du Document Root (Web Root)](#étape-2--configuration-du-document-root)
4. [Étape 3 : Premier Déploiement via SSH (Initialisation)](#étape-3--premier-déploiement-via-ssh)
5. [Étape 4 : Déploiements Futurs Automatisés (Script `deploy.sh`)](#étape-4--déploiements-futurs-automatisés)
6. [🛠️ Guide de Navigation & Administration du Serveur via SSH](#guide-de-navigation--administration-du-serveur-via-ssh)
7. [⏰ Configuration de la Tâche Cron (Scheduler Laravel)](#configuration-de-la-tâche-cron)
8. [🔒 Activation SSL (HTTPS)](#activation-ssl-https)
9. [🚨 Diagnostic & Résolution des Problèmes Fréquents](#diagnostic--résolution-des-problèmes-fréquents)

---

## 1. Architecture & Prérequis Hostinger

Vos identifiants de connexion SSH pour **Rayanova T.D** :
- **Commande SSH :** `ssh -p 65002 u346640129@147.93.54.167`
- **Port SSH :** `65002`
- **Utilisateur SSH :** `u346640129`
- **Hôte / IP :** `147.93.54.167`
- **Chemin du Domaine :** `/home/u346640129/domains/rayanovatd.com/public_html`
- **Version PHP requise :** **PHP 8.2** ou **PHP 8.3**
- **Moteur de base de données :** MySQL / MariaDB (utf8mb4)

---

## Étape 1 : Préparation sur hPanel

### 1.1 Vérifier l'accès SSH & PHP 8.3
1. Dans hPanel : **Avancé** → **Configuration PHP** → Sélectionnez **PHP 8.3**.
2. Dans **Extensions PHP**, vérifiez : `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `curl`, `fileinfo`, `gd`.

### 1.2 Créer la Base de Données MySQL
1. Allez dans **Bases de données** → **Gestion**.
2. Créez une nouvelle base de données MySQL :
   - **Nom de la base :** ex: `rayanova` (le nom complet sera `u346640129_rayanova`).
   - **Nom d'utilisateur :** ex: `admin` (nom complet : `u346640129_admin`).
   - **Mot de passe :** Choisissez un mot de passe sécurisé.
3. Conservez ces identifiants pour le fichier `.env`.

---

## Étape 2 : Déploiement Direct dans `public_html` (Protégé par `.htaccess`)

Le projet inclut un fichier `.htaccess` à la racine qui **sécurise à 100%** votre installation en bloquant l'accès à `.env`, `.git` et aux dossiers internes (`app`, `database`, `storage`, etc.), tout en redirigeant le trafic visiteurs vers `/public/`.

Connectez-vous en SSH :
```bash
ssh -p 65002 u346640129@147.93.54.167
```

### 2.1 Nettoyer et Cloner le Projet dans `public_html`
```bash
cd /home/u346640129/domains/rayanovatd.com/public_html

# Supprimer les fichiers par défaut de Hostinger (default.php, etc.)
rm -rf * .htaccess

# Cloner le projet directement dans public_html (avec le point . à la fin)
git clone https://github.com/zinehamdi/RayanovaTD.git .
```

### 2.2 Configurer le Fichier d'Environnement `.env`
```bash
cp .env.production.example .env
nano .env
```
Renseignez vos identifiants MySQL et vos paramètres :
```env
APP_NAME="Rayanova T.D"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://rayanovatd.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=u346640129_rayanova
DB_USERNAME=u346640129_admin
DB_PASSWORD="VOTRE_MOT_DE_PASSE_MYSQL"
```
Sauvegardez avec `Ctrl + O` puis `Entrée`, et quittez avec `Ctrl + X`.

### 2.3 Générer la Clé & Installer les Dépendances
```bash
composer install --no-dev --optimize-autoloader
php artisan key:generate
```

### 2.4 Créer les Tables, le Compte Admin et les Données Initiales
```bash
php artisan storage:link
php artisan migrate --seed --force
php artisan optimize
chmod -R 775 storage bootstrap/cache
```

```
> **Ce que fait cette commande :**
> - Crée toutes les tables MySQL (utilisateurs, rôles RBAC, produits, certificats, hero CMS, cotations RFQ).
> - Crée le compte Super Admin initial : `admin@rayanovatd.com` (mot de passe par défaut : `Password123!` à changer après première connexion).
> - Insère le catalogue des 4 commodités d'exportation d'élite (Huile d'olive Trésor, Dattes Deglet Nour, Tomates séchées, Thon rouge).

### 3.7 Lier le dossier de stockage (Storage Symlink)
```bash
php artisan storage:link
```

### 3.8 Optimiser et mettre en cache les configurations
```bash
php artisan optimize
```

### 3.9 Ajuster les permissions des fichiers
```bash
chmod -R 775 storage bootstrap/cache
```

Félicitations ! Votre site est en ligne et opérationnel.

---

## Étape 4 : Déploiements Futurs Automatisés

Pour chaque future mise à jour (nouveau code poussé sur `main`), nous avons inclus un script automatisé clé en main : [deploy.sh](file:///Users/zinehamdi/Sites/localhost/RayanovaTD/deploy.sh).

### Option A : Lancer le déploiement directement sur le serveur via SSH
Connectez-vous en SSH et exécutez simplement :
```bash
cd ~/domains/rayanovatd.com/rayanova
bash deploy.sh
```

### Option B : Déployer en 1 seule ligne de commande depuis votre Mac !
Depuis le terminal de votre ordinateur portable, sans même ouvrir une session interactive :
```bash
ssh -p 65002 u987654321@VOTRE_IP_SERVEUR "cd ~/domains/rayanovatd.com/rayanova && bash deploy.sh"
```

### Ce que le script `deploy.sh` exécute automatiquement en 15 secondes :
1. Détection automatique des binaires PHP 8.3, Composer et NPM.
2. Activation du mode maintenance (`php artisan down`) pour éviter les erreurs visiteurs.
3. Téléchargement des dernières modifications depuis GitHub (`git pull origin main`).
4. Installation optimisée des packages PHP (`composer install --no-dev --optimize-autoloader`).
5. Compilation des assets Vite (`npm run build`).
6. Exécution des nouvelles migrations (`php artisan migrate --force`).
7. Régénération propre des caches de production (`config:cache`, `route:cache`, `view:cache`).
8. Vérification du symlink `storage/` et ajustement des permissions `775`.
9. Réouverture du site en direct (`php artisan up`).

---

## 🛠️ Guide de Navigation & Administration du Serveur via SSH

Ce mémo vous permet de naviguer et tester le serveur avec aisance.

### 1. Se repérer et lister les fichiers
```bash
# Savoir dans quel dossier vous vous trouvez actuellement
pwd

# Lister tous les fichiers avec permissions, taille et date
ls -la

# Voir la taille occupée par le projet
du -sh .
```

### 2. Éditer des fichiers sur le serveur
```bash
# Modifier le fichier .env
nano .env

# Utilisation de nano :
# - Flèches pour naviguer
# - Écrire directement
# - Enregistrer : Ctrl + O puis Entrée
# - Quitter : Ctrl + X
```

### 3. Consulter les logs d'erreurs en direct (Monitoring)
```bash
# Suivre les logs en temps réel (pratique lors de tests)
tail -f storage/logs/laravel.log

# Pour arrêter le suivi en temps réel : appuyez sur Ctrl + C

# Afficher les 100 dernières lignes de log
tail -n 100 storage/logs/laravel.log

# Chercher un mot-clé dans les logs (ex: 'error' ou 'exception')
grep -i "error" storage/logs/laravel.log
```

### 4. Commandes Laravel Artisan indispensables sur le serveur
```bash
# Tester la connexion à la base de données
php artisan db:monitor

# Ouvrir la console interactive Laravel Tinker pour exécuter du code PHP
php artisan tinker

# Vider tous les caches si un changement de configuration ne s'affiche pas
php artisan optimize:clear

# Reconstituer tous les caches de production
php artisan optimize

# Voir la liste de toutes les routes de l'application
php artisan route:list
```

### 5. Gérer les permissions
Si vous rencontrez une erreur `The stream or file ".../laravel.log" could not be opened: failed to open stream: Permission denied` :
```bash
chmod -R 775 storage bootstrap/cache
```

---

## ⏰ Configuration de la Tâche Cron

Pour que les devis expirés, les emails programmés et les tâches d'arrière-plan fonctionnent en continu :

1. Sur Hostinger hPanel : Allez dans **Avancé** → **Tâches Cron**.
2. Choisissez le type : **Personnalisé**.
3. Dans **Commande**, saisissez :
   ```bash
   * * * * * /usr/bin/php ~/domains/rayanovatd.com/rayanova/artisan schedule:run >> /dev/null 2>&1
   ```
   *(Remplacez le chemin par le chemin réel de votre dossier d'installation)*.
4. Intervalle : **Toutes les minutes** (`* * * * *`).
5. Cliquez sur **Enregistrer**.

---

## 🔒 Activation SSL (HTTPS)

1. Sur hPanel : Allez dans **Sécurité** → **Certificats SSL**.
2. Cliquez sur **Installer le certificat SSL** pour votre domaine.
3. Activez l'option **Forcer HTTPS** pour rediriger automatiquement tous les visiteurs de `http://` vers `https://`.

---

## 🚨 Diagnostic & Résolution des Problèmes Fréquents

| Problème rencontré | Cause | Solution |
| :--- | :--- | :--- |
| **Page 500 - Server Error** | Erreur PHP ou clé manquante | Consulter les logs : `tail -n 50 storage/logs/laravel.log`. Vérifier si `APP_KEY` est générée dans `.env`. |
| **Page blanche / 403 Forbidden** | Mauvais Document Root | Le serveur pointe sur la racine au lieu de `public/`. Suivre l'Étape 2 pour pointer sur `public/`. |
| **Vite manifest not found** | Assets non compilés | Lancer `npm run build` ou uploader le dossier local `public/build/` via SFTP. |
| **Images de produits ne s'affichent pas (404)** | Lien de stockage manquant | Exécuter `php artisan storage:link`. Vérifier `chmod -R 775 storage`. |
| **Erreur de mémoire durant `composer install`** | RAM limitée sur mutualisé | Utiliser le flag sans dev : `composer install --no-dev --prefer-dist --optimize-autoloader`. |
| **Version PHP en CLI différente du web** | Alias PHP par défaut | Utiliser le chemin explicite de la version PHP, par exemple `/usr/bin/php8.3 artisan ...` ou ajouter un alias dans `~/.bashrc`. |

---

## 📞 Support & Maintenance
Le code source est synchronisé sur GitHub : [https://github.com/zinehamdi/RayanovaTD.git](https://github.com/zinehamdi/RayanovaTD.git).  
Toute modification poussée sur la branche `main` peut être déployée instantanément avec `bash deploy.sh`.
