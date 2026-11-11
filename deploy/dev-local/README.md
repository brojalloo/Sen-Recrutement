# Docker local sur Windows

Dans PowerShell, ouvre le dossier du dépôt sur la branche `dev` et démarre Docker Desktop en mode conteneurs Linux.

```powershell
.\deploy\dev-local\Sync-Dev.ps1
```

Ouvre [127.0.0.1:8093](http://127.0.0.1:8093). La commande télécharge l’image du dernier push validé sur `dev` et vérifie sa révision avant de la démarrer. Relance-la pour récupérer une nouvelle version. Les conteneurs déjà installés redémarrent quand Docker Desktop redémarre, sauf arrêt manuel.

Pour télécharger l’image, installe GitHub CLI avec `winget install --id GitHub.cli`, rouvre PowerShell, puis lance `gh auth login`. L’archive GitHub Actions est disponible pendant 90 jours. La construction locale ci-dessous fonctionne aussi sans GitHub CLI.

## Développer avec Docker

Après avoir modifié le code, reconstruis l’image depuis le dépôt.

```powershell
.\deploy\dev-local\Sync-Dev.ps1 -Build
```

Pour créer ton compte administrateur local, exécute cette commande et suis les invites.

```powershell
docker --context desktop-linux compose --env-file deploy/dev-local/.env -f deploy/dev-local/compose.yaml exec app php artisan admin:create admin@local.test
```

Les volumes de cette instance sont dédiés au développement. La base et les fichiers restent en place lors d’une reconstruction. Conserve aussi `deploy/dev-local/.env`, qui contient la clé locale générée au premier démarrage. Les données d’une ancienne installation Compose vivent dans leurs volumes d’origine. Utilise une sauvegarde et une restauration pour les transférer.

Pour voir les journaux ou arrêter l’instance en conservant les volumes :

```powershell
docker --context desktop-linux compose --env-file deploy/dev-local/.env -f deploy/dev-local/compose.yaml logs --tail 100
docker --context desktop-linux compose --env-file deploy/dev-local/.env -f deploy/dev-local/compose.yaml stop
```

Les pull requests vers `dev` construisent et démarrent cette configuration dans GitHub Actions. Après validation d’un push sur `dev`, le workflow publie l’image de développement comme artefact GitHub Actions. La mise en service sur un serveur reste une étape distincte.
