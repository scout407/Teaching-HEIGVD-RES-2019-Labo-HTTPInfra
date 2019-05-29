# Labo HTTP Infra 

## step 1 : serveur HTTP static (apache)

Dans cette partie nous devions mettre en place un serveur HTTP apache qui est static en utilisant une template.

Nous devions créer le Dockerfile créant l'image  pour le serveur.

Contenu du dockerfile : 

```FROM php:5.6-apache

COPY content/ /var/www/html/```

Nous montont donc une image via apache 5.6 en mettant le contenue de content dans /var/www/html ainsi lorsque nous créerons le container il possèdera le template que nous avons mis dans content.

Nous avons utilisé une template boostrap et modifier certain champs.

Pour construire l'image il faut utiliser la commande :
```docker build -t res/apache2_php <chemin_absolu_vers_dockerfile>```
ou bien
```docker build -t res/apache2_php .```
si on se trouve dans le dossier contenant le docker.

Pour lancer le container il faut utiliser la commande :
```docker run -d -p 9090:80 res/apache_php```

Une fois ceci réalisé il suffit de s'y connecter via l'adresse ip de docker aisni que le port désigné.

## step 2 : serveur HTTP dynamique avec express.js

Dans cette partie nous devions implémenter un serveur HTTP dynamique proposant du contenue en utilisant express.js et Node.js.
Il faut aussi créer un dockerfile mais cette fois adapter au serveur HTTP dynamique pour l'image docker.

Contenu du dockerfile :
```FROM node:8.11

COPY src /opt/app

CMD ["node", "/opt/app/index.js"]```

Ce dockerfile permet de monter une image d'un serveur HTTP en utilisant node.js comme environnement.
Comme pour l'étape 1 ici on copy les fichier du bon fonctionnement depuis src dans notre application.

Le fichier index.js est créer permettant de générer les villes et la retourner au client au format json.

Les dépendances aux librairies pour le code sont toutes gérées avec les fichiers package-lock.json et package.json. Les librairies utilisées sont ajoutées avec la commande : " nom install --save chance express ". Dès lors que le code est fonctionnel, il faut construire l'image et démarrer le container comme suit :
```docker build -t res/express <chemin_absolu_vers_dockerfile>``` ou bien 
```docker build -t res/express .``` si depuis le dossier du dockerfile.

puis lancer le container avec la commande : 
```docker run -p 9090:3000 res/express```




