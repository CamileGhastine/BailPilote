# Installation du projet Symfony (docker) BailPilote

## Prérequis
- Docker
- Docker Compose

## Conteneurs utilisés
bailPilote_php
bailPilote_nginx
bailPilote_mysql
bailPilote_phpmyadmin
bailPilote_mailhog

## Étapes d’installation

1. Cloner le dépôt

git clone https://github.com/CamileGhastine/BailPilote.git
cd BailPilote

2. Corriger les permissions (indispensable)
Le montage de volume Docker écrase les permissions du dossier hôte. À exécuter une seule fois après le clonage :
sudo chown -R $(id -u):$(id -g) ./app

3. Démarrer les conteneurs

Vérifier que les ports ne sont pas déjà utilisés

docker-compose up -d --build

4. Installer les dépendances Symfony

docker exec -it bailPilote_php composer install

5. Configurer l’environnement
Créer le fichier .env.local :

cp .env .env.local
Vérifier la configuration de la base de données :

DATABASE_URL="mysql://user:pwd@mysql:3306/bailPilote?serverVersion=8.0.32&charset=utf8mb4"
MAILER_DSN=smtp://mailhog:1025
MESSENGER_TRANSPORT_DSN=sync://

## Commandes utiles
Accéder au conteneur PHP :
docker exec -it bailPilote_php sh
Voir les logs :
docker-compose logs -f
Arrêter les conteneurs :
docker-compose down


## Suivi de projet
Lien Jira : https://bail-pilote.atlassian.net/jira/software/projects/BP/boards/1
