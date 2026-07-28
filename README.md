# Mini-projet Symfony — Examen Final ESGI 4

Application web de type « mur de posts » : les utilisateurs s'inscrivent, se connectent, puis peuvent consulter et publier des messages.

## Instructions pour lancer le projet

### Prérequis

- PHP 8.1 ou supérieur
- [Composer](https://getcomposer.org/)
- PostgreSQL 14+ (16 recommandé)
- Extensions PHP : `ctype`, `iconv`, `pdo_pgsql`

### 1. Cloner le dépôt

```bash
git clone <URL_DU_DEPOT>
cd symphony
```

### 2. Installer les dépendances

```bash
composer install
```

### 3. Configurer la base de données

Le fichier `.env` contient une URL PostgreSQL par défaut :

```env
DATABASE_URL="postgresql://app:!ChangeMe!@127.0.0.1:5432/app?serverVersion=16&charset=utf8"
```

Adaptez-la à votre environnement local. Exemple pour créer l'utilisateur et la base sous PostgreSQL :

```bash
psql postgres -c "CREATE USER app WITH PASSWORD '!ChangeMe!';"
psql postgres -c "CREATE DATABASE app OWNER app;"
```

Vous pouvez aussi surcharger la valeur dans un fichier `.env.local` (non versionné) :

```env
DATABASE_URL="postgresql://VOTRE_USER:VOTRE_MDP@127.0.0.1:5432/VOTRE_BDD?serverVersion=16&charset=utf8"
```

### 4. Créer le schéma de base de données

```bash
php bin/console doctrine:database:create --if-not-exists
php bin/console doctrine:migrations:migrate --no-interaction
```

> Si la migration a déjà été exécutée (base existante) et que les tables manquent, lancez :
> `php bin/console doctrine:schema:update --force`

### 5. Lancer le serveur

Avec la CLI Symfony :

```bash
symfony server:start
```

Ou avec le serveur PHP intégré :

```bash
php -S 127.0.0.1:8000 -t public
```

### 6. Utiliser l'application

1. Ouvrir [http://127.0.0.1:8000](http://127.0.0.1:8000) — vous serez redirigé vers la page de connexion.
2. Cliquer sur **Créer un compte** (`/register`) pour vous inscrire (email + mot de passe, min. 6 caractères).
3. Se connecter (`/login`) avec les identifiants créés.
4. Consulter la liste des posts (`/`) et en créer un via **Créer un post** (`/post/new`).

---

## Documentation

### Objectifs

Permettre à des utilisateurs authentifiés de publier et consulter des posts sur un mur partagé. Le projet répond aux contraintes minimales de l'examen Symfony (HTML/Twig, formulaires, PostgreSQL/Doctrine, authentification, services, assets).

### Dictionnaire de données

| Entité | Champ       | Type        | Contraintes              | Description                    |
|--------|-------------|-------------|--------------------------|--------------------------------|
| User   | id          | integer     | PK, auto-incrément       | Identifiant utilisateur        |
| User   | email       | string(180) | unique, obligatoire      | Identifiant de connexion       |
| User   | password    | string      | obligatoire (hashé)      | Mot de passe                   |
| User   | roles       | json        | obligatoire              | Rôles Symfony (ROLE_USER…)     |
| Post   | id          | integer     | PK, auto-incrément       | Identifiant du post            |
| Post   | title       | string(255) | obligatoire              | Titre du post                  |
| Post   | content     | text        | obligatoire              | Contenu du post                |
| Post   | createdAt   | datetime    | obligatoire              | Date de création               |

### Routes principales

| URL          | Méthode   | Nom           | Accès              | Description              |
|--------------|-----------|---------------|--------------------|--------------------------|
| `/`          | GET       | `home`        | Authentifié        | Liste des posts          |
| `/post/new`  | GET, POST | `post_new`    | Authentifié        | Formulaire de création   |
| `/register`  | GET, POST | `app_register`| Public             | Inscription              |
| `/login`     | GET, POST | `app_login`   | Public             | Connexion                |
| `/logout`    | GET       | `app_logout`  | Authentifié        | Déconnexion              |

### Architecture

- **Contrôleurs** : `HomeController`, `PostController`, `SecurityController`, `RegistrationController`
- **Service métier** : `App\Service\PostService` (création et récupération des posts)
- **Entités Doctrine** : `User`, `Post`
- **Templates Twig** : `templates/`
- **Assets** : `public/assets/css/app.css`, `public/assets/js/app.js`, `public/assets/images/logo.svg`
- **Sécurité** : authentification par formulaire (`form_login`) avec token CSRF

### Schéma relationnel (MCD)

```mermaid
erDiagram
    USERS {
        int id PK
        string email UK
        string password
        json roles
    }
    POST {
        int id PK
        string title
        text content
        datetime created_at
    }
```

Les posts ne sont pas liés à un utilisateur dans cette version minimale du projet.

---

## Commentaires (optionnel)

- **Difficultés rencontrées** : configuration PostgreSQL, token CSRF manquant sur le formulaire de connexion, migration Doctrine initiale vide.
- **Ce qui a bien fonctionné** : génération des entités, formulaires Symfony, service `PostService`, templates Twig.
- **Pistes d'amélioration** : associer chaque post à son auteur, pagination, tests fonctionnels.
