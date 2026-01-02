# Migration Complète - Recrutement Laravel

## Vue d'ensemble
Migration complète du site de recrutement vers Laravel 12.x avec toutes les fonctionnalités du site original.

## Fonctionnalités implémentées

### 1. Authentification
- ✅ Inscription (candidats/recruteurs)
- ✅ Connexion/Déconnexion
- ✅ Réinitialisation de mot de passe (mot-de-passe-oublie/reinitialiser)
- ✅ Middleware de rôles (admin/candidate/recruiter)

### 2. Page d'accueil
- ✅ Hero section avec recherche
- ✅ Offres récentes
- ✅ Offres populaires
- ✅ Design moderne et attractif

### 3. Offres d'emploi (public)
- ✅ Liste avec filtres (localisation, type, salaire)
- ✅ Pagination
- ✅ Détail d'une offre
- ✅ Badges visuels (type, salaire)

### 4. Candidat
- ✅ Dashboard avec statistiques
- ✅ Candidature en ligne avec upload CV
- ✅ Historique des candidatures
- ✅ Profil éditable (nom, téléphone, adresse, bio)
- ✅ Upload avatar

### 5. Recruteur
- ✅ Dashboard avec statistiques
- ✅ CRUD complet des offres
- ✅ Upload logo entreprise
- ✅ Vue des candidatures reçues
- ✅ Profil éditable (nom, entreprise, description)

### 6. Messagerie
- ✅ Boîte de réception
- ✅ Boîte d'envoi
- ✅ Composer un message
- ✅ Marquer comme lu
- ✅ Disponible pour tous les utilisateurs authentifiés

### 7. Administration
- ✅ Dashboard avec statistiques globales
- ✅ Gestion des utilisateurs (liste paginée)
- ✅ Gestion des offres (liste paginée)
- ✅ Logs d'administration (liste + export CSV)

### 8. Contact
- ✅ Formulaire de contact
- ✅ Envoi d'email automatique
- ✅ Validation des champs

### 9. Fichiers
- ✅ Téléchargement CV (protégé par rôle)
- ✅ Upload CV (candidature)
- ✅ Upload logo (offre d'emploi)
- ✅ Upload avatar (profil)
- ✅ Storage public lié

### 10. Design & UX
- ✅ Layout Bootstrap 5
- ✅ Theme CSS personnalisé (palette moderne)
- ✅ Navbar responsive avec dropdown
- ✅ Footer avec liens sociaux
- ✅ Icons Bootstrap
- ✅ Fonts Google (Poppins)
- ✅ Pages d'erreur (404, 500)

## Structure technique

### Models
- User (avec rôles et champs étendus)
- Job (recruitment_jobs)
- Application
- Message
- AdminLog

### Controllers
- AuthController (login/register)
- ForgotPasswordController & ResetPasswordController
- HomeController
- JobController (public)
- ContactController
- ApplicationController
- MessagingController
- ProfileController (avatar)
- FileController (download CV)
- Admin/AdminController
- Admin/UserController
- Admin/JobAdminController
- Admin/LogController (avec export CSV)
- Candidate/CandidateController
- Candidate/ProfileController
- Recruiter/RecruiterController
- Recruiter/JobController
- Recruiter/ProfileController

### Migrations
- Altérations users (profile fields, timestamps, remember_token) - gardées pour compatibilité
- recruitment_jobs
- applications
- messages
- admin_logs
- password_reset_tokens

### Routes (43 total)
- Publiques : home, jobs, contact, auth, password reset
- Candidate : dashboard, apply, profile
- Recruiter : dashboard, jobs CRUD, profile
- Admin : dashboard, users, jobs, logs (+ export)
- Authentifiées : messaging, avatar, CV download

### Views
- layouts/app.blade.php
- home/index.blade.php
- job/index.blade.php, show.blade.php
- auth/login.blade.php, register.blade.php
- auth/passwords/email.blade.php, reset.blade.php
- contact/index.blade.php
- candidate/dashboard.blade.php, apply.blade.php, profile.blade.php
- recruiter/dashboard.blade.php, profile.blade.php
- recruiter/jobs/index.blade.php, create.blade.php, edit.blade.php
- admin/dashboard.blade.php, users.blade.php, jobs.blade.php, logs.blade.php
- messaging/inbox.blade.php, outbox.blade.php, compose.blade.php
- emails/contact.blade.php, application.blade.php
- errors/404.blade.php, 500.blade.php

### Configuration
- .env : MySQL (ib), Gmail SMTP (quoted password), filesystem public, queue sync, session/cache file
- Middleware : RoleMiddleware alias 'role'
- User model : timestamps disabled, remember_token handled

## Compatibilité base de données
- Migration gardée avec la base legacy `ib`
- Tables existantes respectées via guards
- Colonnes manquantes gérées via model settings

## Prochaines étapes optionnelles
- Tests automatisés (PHPUnit)
- Seeders pour données de démo
- API REST pour mobile
- Notifications en temps réel
- Statistiques avancées (charts)

## Commandes utiles

```bash
# Démarrer le serveur
php artisan serve

# Lister les routes
php artisan route:list

# Lancer les migrations
php artisan migrate --force

# Lier le storage
php artisan storage:link

# Vider le cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

## Notes importantes
- Remember-me désactivé pour compatibilité avec la base legacy
- Timestamps désactivés sur User pour éviter les erreurs
- Emails configurés via Gmail (app password requis)
- Storage public utilisé pour uploads (CVs, logos, avatars)
