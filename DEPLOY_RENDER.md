# Déployer sur Render — guide rapide

Ce document explique comment déployer rapidement `Sen-Recrutement` sur Render (Web Service). Render s'intègre directement à GitHub et déploie automatiquement à chaque push.

1) Créer un compte Render et connecter GitHub
- Ouvre https://render.com et crée un compte.
- Dans Render, connecte ton compte GitHub et autorise l'accès au repo `Sen-Recrutement`.

2) Créer un nouveau Web Service
- New -> Web Service -> Connect repository -> choisis `Sen-Recrutement` et branch `main`.

3) Paramètres recommandés
- Environment: `Docker` ou `Static` (choisis "Community" Web Service par défaut).
- Build command (si tu n'utilises pas Docker) :
  ```bash
  composer install --no-interaction --prefer-dist
  npm ci && npm run build || true
  ```
- Start command :
  ```bash
  php artisan serve --host 0.0.0.0 --port $PORT
  ```
  (Remarque : pour production, il est préférable d'utiliser Docker / PHP-FPM + Nginx ou un service managé.)

4) Variables d'environnement à ajouter (Settings > Environment)
- `APP_ENV=production`
- `APP_DEBUG=false`
- `APP_KEY` (générer via `php artisan key:generate` ou via la console Render)
- `APP_URL=https://<ton-domaine>.onrender.com`
- DB vars: `DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`
- Mail vars: `MAIL_MAILER`, `MAIL_HOST`, `MAIL_PORT`, `MAIL_USERNAME`, `MAIL_PASSWORD`, `MAIL_FROM_ADDRESS`

5) Base de données
- Render propose des services gérés (Postgres). Si tu veux MySQL, tu peux provisionner une base MySQL externe (ex. PlanetScale, ClearDB, DO Managed DB) et renseigner les vars DB.
- Si tu utilises Postgres, mets `DB_CONNECTION=pgsql` et adapte si besoin.

6) Commande post-déploiement (migrations)
- Après le premier deploy, exécute dans la console Render ou ajoute un `postdeploy` (Render Dashboard) :
  ```bash
  php artisan migrate --force
  php artisan storage:link
  php artisan config:cache
  ```

7) Tests et accès
- Une fois le déploiement terminé, visite l'URL fournie par Render.

8) Optionnel : fichier `render.yaml`
- Tu peux déclarer l'infrastructure en `render.yaml`. Voici un modèle minimal à adapter avant usage :

```yaml
# render.yaml - modèle à adapter
services:
  - type: web
    name: Sen-Recrutement
    env: node
    branch: main
    plan: free
    buildCommand: "composer install --no-interaction --prefer-dist && npm ci && npm run build || true"
    startCommand: "php artisan serve --host 0.0.0.0 --port $PORT"
    envVars:
      - key: APP_ENV
        value: production
      - key: APP_DEBUG
        value: 'false'

# Note: adapte ce fichier selon la doc Render et tes besoins avant de l'utiliser.
```

Si tu veux, je peux :
- générer le `render.yaml` final (avec tes valeurs) et le committer, ou
- te guider étape par étape dans l'UI Render pour créer le service.
