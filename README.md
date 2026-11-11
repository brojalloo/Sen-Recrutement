# Sen-Recrutement

Plateforme de recrutement en ligne pour le Sénégal, construite avec Laravel 12.

Trois rôles : les **candidats** postulent et suivent leurs candidatures, les
**recruteurs** publient des offres et traitent les candidatures, les
**administrateurs** modèrent les offres et gèrent les comptes.

## Démarrer en local

Prérequis : PHP 8.2+, Composer, Node 20+.

```bash
composer setup          # dépendances, .env, clé, migrations, build des assets
php artisan db:seed --class=DemoDataSeeder
composer dev            # serveur, worker de queue, logs et Vite en parallèle
```

Le site répond sur http://localhost:8000.

### Comptes de démonstration

| Rôle | Identifiant | Mot de passe |
|---|---|---|
| Candidat | `candidate1@email.com` | `password` |
| Recruteur | `recruiter1@techcorp.sn` | `password` |

Il n'y a **pas de compte administrateur de démonstration** : le formulaire
d'inscription refuse le rôle `admin`, et c'est voulu. Créez-en un en ligne de
commande :

```bash
php artisan admin:create admin@votre-domaine.sn --password='au moins 12 caractères'
```

## Commandes utiles

```bash
composer test                        # suite de tests
./vendor/bin/pint                    # formatage du code
./vendor/bin/phpstan analyse         # analyse statique (niveau 5)
npm run dev                          # assets en rechargement à chaud
npm run build                        # assets de production
```

La CI rejoue exactement ces quatre vérifications, plus `composer audit` et la
construction de l'image Docker. Les lancer avant de pousser évite l'aller-retour.

## Architecture

```
app/
  Http/Controllers/      par rôle : Admin/, Candidate/, Recruiter/, + public
  Http/Middleware/       RoleMiddleware (accès par rôle), SecurityHeaders (CSP)
  Http/Requests/         règles de validation partagées
  Policies/              qui a le droit de voir quoi
  Support/               JobPostingSchema (SEO), NotificationDispatcher
resources/
  css/app.css            bundle commun : Bootstrap, icônes, polices, thème
  css/pages/             styles propres à une page, chargés par elle seule
  js/app.js              tout le JavaScript de l'interface
```

### Quelques décisions à connaître avant de modifier

**Les CV vivent sur le disque privé.** Le disque `public` est servi sans
authentification via `public/storage` : y déposer un CV le rendrait
téléchargeable par quiconque connaît l'URL. Les CV ne transitent que par
`/cv/{application}` et `/download/cv/{user}`, sous contrôle des Policies. Les
avatars et logos d'entreprise, eux, sont publics et restent sur `public`.

**Une offre visible se décide à un seul endroit :** le scope `Job::visible()`
(approuvée et active). Pages publiques, tableaux de bord et sitemap s'y
réfèrent. Dupliquer ce filtre est ce qui avait laissé des offres en attente
s'afficher publiquement.

**Le CRUD des offres répond 404, pas 403,** sur l'offre d'un autre recruteur.
La requête est filtrée avant le `findOrFail`, pour ne pas révéler qu'une offre
existe. C'est délibéré et couvert par des tests : ne le convertissez pas en
Policy « pour l'uniformité ».

**Aucun script inline.** Tout le JavaScript passe par le bundle Vite, ce qui
permet à la CSP de garder `script-src 'self'`. Un `onclick=` ajouté dans une
vue serait bloqué par le navigateur. Utilisez les attributs `data-confirm` et
`data-toggle-password` déjà en place.

**Les styles d'une page ne vont pas dans le bundle commun.** Plusieurs pages
définissent les mêmes sélecteurs avec des valeurs différentes — `.avatar-circle`
existe en cinq tailles. Chaque page charge son fichier via
`@push('styles') @vite('resources/css/pages/…') @endpush`.

**Les échecs d'envoi d'email ne bloquent pas l'action métier** mais sont
consignés, et l'interface ne prétend pas qu'un email est parti quand il ne
l'est pas. Passez par `NotificationDispatcher`.

## Déploiement

Voir [DEPLOY_RENDER.md](DEPLOY_RENDER.md). L'image Docker construite par la CI
contient les assets déjà compilés : la production n'a pas besoin de Node.

À savoir : `render.yaml` ne déclare **aucun worker de queue**, et les
notifications ne sont pas mises en file. Les emails partent donc dans la
requête HTTP. Ajouter un worker demanderait un service supplémentaire.
