# Sen-Recrutement

Site de recrutement en ligne développé avec Laravel.

Voir le dossier `README` et la documentation du projet pour lancer localement.
 
Déploiement de test avec Docker
-------------------------------

Prérequis : Docker et Docker Compose installés.

1. Copier `.env.example` en `.env` et ajuster si besoin :

```bash
cp .env.example .env
```

2. Lancer les services (build + démarrage) :

```bash
docker-compose up -d --build
```

3. Accéder à l'app de test : http://localhost:8000

4. Pour voir les logs et arrêter :

```bash
docker-compose logs -f
docker-compose down
```

Remarques:
- Ce déploiement utilise `php artisan serve` dans le conteneur pour un test rapide (pas pour production).
- Si tu veux un déploiement en ligne (Render / VPS), je peux t'aider ensuite.
