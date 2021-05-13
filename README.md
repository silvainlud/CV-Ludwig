# Curriculum Vitae de Ludwig SILVAIN

[![Build Status](https://drone.silvain.eu/api/badges/Silvain.eu/cv-ludwig/status.svg?ref=refs/heads/master)](https://drone.silvain.eu/Silvain.eu/cv-ludwig)

Voir [silvain.eu](https://silvain.eu)

Déplot complet sur [gitea.silvain.eu](https://git.silvain.eu/Silvain.eu/cv-ludwig)

# Utilisation

-  Monter les conteneurs docker :
```
docker-compose up -d
```

-  Ajouter un fichier `.env.local` pour ne pas éditer le fichier `.env` :
```dotenv
APP_ENV=dev
DATABASE_URL=mysql://root:123@database:3306/cv_ludwig?serverVersion=10.4.11-MariaDB
DATABASE_URL_MAIL=mysql://root:123@database:3306/mail
REDIS_URL=redis://host.docker.internal:6379
MAILER_DSN=smtp://mailcatcher:1025
```

-  Intaller les dépendances composer dans le conteneur `php` :
```
composer install
```
