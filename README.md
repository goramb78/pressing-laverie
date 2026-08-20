# Pressing LIC — Application de gestion

Application de gestion de commandes pour un pressing/laverie, développée dans le cadre d'un projet académique (Licence 3, Génie Logiciel).

## Stack technique

- **Backend** : Laravel 13 (API REST), authentification par token via Sanctum
- **Frontend** : Angular 22 (SPA)
- **Base de données** : MySQL
- **Génération PDF** : DomPDF
- **Emails** : Laravel Mail
- **Graphiques** : Chart.js (via ng2-charts)

## Fonctionnalités

- Authentification (inscription client, connexion, protection des routes par rôle)
- Gestion des services (catalogue, ajout/modification/archivage)
- Dépôt et suivi de commandes (cycle : Reçu → En traitement → Prêt → Récupéré)
- Gestion des paiements
- Notifications email automatiques (confirmation, notification gestionnaire, reçu prêt)
- Génération de reçu PDF
- Statistiques (tickets du jour, recette, graphiques par mois et par service)

## Installation

### Prérequis

- PHP 8.3 ou supérieur
- Composer
- Node.js 22+ et npm
- MySQL

### Backend (pressing-api)

```bash
cd pressing-api
composer install
cp .env.example .env
php artisan key:generate
```

Configurer les identifiants de base de données dans `.env` (`DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`), puis :

```bash
php artisan migrate --seed
php artisan serve
```

L'API est accessible sur `http://127.0.0.1:8000`.

**Compte gestionnaire par défaut** (créé par le seeder) :
- Email : `gestionnaire@pressing.com`
- Mot de passe : `gestionnaire2026`

### Frontend (pressing-frontend)

```bash
cd pressing-frontend
npm install
ng serve
```

L'application est accessible sur `http://localhost:4200`.

## Auteur

Gora Thiam — Licence 3, Génie Logiciel