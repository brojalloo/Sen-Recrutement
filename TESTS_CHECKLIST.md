# Checklist de test - Laravel Recrutement

## Tests à effectuer

### Authentification
- [ ] Inscription candidat avec validation
- [ ] Inscription recruteur avec validation
- [ ] Connexion avec email/password
- [ ] Déconnexion
- [ ] Mot de passe oublié → email reçu
- [ ] Réinitialisation avec token valide

### Navigation publique
- [ ] Page d'accueil accessible
- [ ] Liste des offres avec filtres
- [ ] Détail d'une offre
- [ ] Page contact + envoi email

### Candidat
- [ ] Accès dashboard candidat
- [ ] Voir offres recommandées
- [ ] Postuler à une offre (upload CV)
- [ ] Voir historique candidatures
- [ ] Éditer profil
- [ ] Upload avatar

### Recruteur
- [ ] Accès dashboard recruteur
- [ ] Voir statistiques
- [ ] Créer nouvelle offre
- [ ] Éditer offre existante
- [ ] Upload logo entreprise
- [ ] Supprimer offre
- [ ] Voir candidatures reçues
- [ ] Éditer profil

### Admin
- [ ] Accès dashboard admin
- [ ] Liste utilisateurs paginée
- [ ] Liste offres paginée
- [ ] Liste logs paginée
- [ ] Export logs CSV

### Messagerie
- [ ] Composer message
- [ ] Envoyer message
- [ ] Recevoir message (inbox)
- [ ] Voir messages envoyés (outbox)
- [ ] Marquer message comme lu

### Téléchargements
- [ ] Télécharger CV (applicant peut télécharger son propre CV)
- [ ] Télécharger CV (recruteur peut télécharger CV de candidature à son offre)
- [ ] Bloquer téléchargement CV non autorisé

### Sécurité
- [ ] Middleware role bloque accès non autorisé
- [ ] Dashboard admin accessible uniquement par admin
- [ ] Dashboard candidate accessible uniquement par candidate
- [ ] Dashboard recruiter accessible uniquement par recruiter
- [ ] Routes protégées requièrent authentification

### Design
- [ ] Navbar responsive
- [ ] Dropdown menu fonctionne
- [ ] Theme CSS chargé
- [ ] Icons affichées
- [ ] Footer présent
- [ ] Pages erreur (404, 500) stylées

## Tests de régression

### Base de données
- [ ] Pas d'erreur sur users existants
- [ ] Timestamps optionnels fonctionnent
- [ ] Remember token optionnel fonctionne

### Emails
- [ ] Config SMTP valide
- [ ] Email contact envoyé
- [ ] Email reset password envoyé
- [ ] Email notification candidature envoyé

### Storage
- [ ] Storage link actif
- [ ] Uploads CV dans storage/app/public/cvs
- [ ] Uploads logos dans storage/app/public/logos
- [ ] Uploads avatars dans storage/app/public/avatars

## Résultats attendus
- ✅ 43 routes fonctionnelles
- ✅ Toutes les vues se chargent sans erreur
- ✅ Pas d'erreur 500 sur actions CRUD
- ✅ Messages flash affichés correctement
- ✅ Redirections appropriées après actions
