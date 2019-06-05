# Labo HTTP Infra 

## step 1 : serveur HTTP static (apache)

Dans cette partie nous devions mettre en place un serveur HTTP apache qui est static en utilisant une template.

Nous devions créer le Dockerfile créant l'image  pour le serveur.

Contenu du dockerfile : 

```
FROM php:5.6-apache

COPY content/ /var/www/html/
```

Nous montont donc une image via apache 5.6 en mettant le contenue de content dans /var/www/html ainsi lorsque nous créerons le container il possèdera le template que nous avons mis dans content.

Nous avons utilisé une template boostrap et modifier certain champs.

Pour construire l'image il faut utiliser la commande :
`docker build -t res/apache2_php <chemin_absolu_vers_dockerfile>`
ou bien
`docker build -t res/apache2_php .`
si on se trouve dans le dossier contenant le docker.

Pour lancer le container il faut utiliser la commande :
`docker run -d -p 9090:80 res/apache_php`

Une fois ceci réalisé il suffit de s'y connecter via l'adresse ip de docker aisni que le port désigné.

## step 2 : serveur HTTP dynamique avec express.js

Dans cette partie nous devions implémenter un serveur HTTP dynamique proposant du contenue en utilisant express.js et Node.js.
Il faut aussi créer un dockerfile mais cette fois adapter au serveur HTTP dynamique pour l'image docker.

Contenu du dockerfile :
```
FROM node:8.11

COPY src /opt/app

CMD ["node", "/opt/app/index.js"]
```

Ce dockerfile permet de monter une image d'un serveur HTTP en utilisant node.js comme environnement.
Comme pour l'étape 1 ici on copy les fichier du bon fonctionnement depuis src dans notre application.

Le fichier index.js est créer permettant de générer les villes et la retourner au client au format json.

Les dépendances aux librairies pour le code sont toutes gérées avec les fichiers package-lock.json et package.json. Les librairies utilisées sont ajoutées avec la commande : " nom install --save chance express ". Dès lors que le code est fonctionnel, il faut construire l'image et démarrer le container comme suit :
`docker build -t res/express <chemin_absolu_vers_dockerfile>` ou bien 
`docker build -t res/express .` si depuis le dossier du dockerfile.

puis lancer le container avec la commande : 
```docker run -d -p 9090:3000 res/express```
## step 3 : reverse proxy apache static
Pour mettre en place un serveur reverse proxy apache, il nous faut activer le module du proxy.

Pour cette étape, le Dockerfile à créer et compléter aura les instructions suivantes :
```
FROM php:5.6-apache

COPY conf/ /etc/apache

RUN a2enmod proxy proxy_http
RUN a2ensite 000-* 001-*
```

On copie des fichiers de configuration générés statiquement pour notre serveur apache. On veut également lancer le programme qui activera les modules proxy et proxy_http pour pouvoir mettre en place notre reverse proxy.

Pour que le serveur soit correctement mis en place, il faut aussi créer une configuration du virtual host, sachant qu'il y a déjà une configuration par défaut. Ce qui fera 2 fichiers de configuration, celui par défaut (000-default.conf) et celui crée (001-reverse-proxy.conf)

Le contenue de 000-default.conf :
```
<VirtualHost *:80>
</VirtualHost>
```

Celui de 001-reverse-proxy.conf :
```
<VirtualHost *:80>

	ServerName labo.res.ch
	
	
	ProxyPass					"/api/students" "http://172.17.0.3.3000/""
	ProxyPassReverse			"/api/students/" "http://172.17.0.3:3000/"
	
	ProxyPass					"/" "http://172.17.0.2:80/"
	ProxyPassReverse			"/" "http://172.17.0.2:80/"

</VirtualHost>
```

Le port d'entrée 80 est pris comme port d'entrée à l'entrée du container, qu'il reste à associer au 8080 qui permet de communiquer avec l'extérieur. Le nom de domaine utilisé pour appeler le serveur proxy est "labo.res.ch". 2 paires de directives gèrent chacune la redirection de requêtes au serveur dynamique express (1ere paire), ainsi que la redirection au serveur statique (2e paire). Les IP pour chaque serveur sont statiquement fixées.

## step 4 : requête ajax
Nous avons donc nos 2 serveur et notre proxy qui tourne pour avoir un seul point d'entrée. Maintenant pour modifier les informations des pages il nous faut installer vim sur notre machine pour cela il faut rajouter au docker :
```
RUN apt-get update && \ apt-get install -y vim
```

Ensuite il faut établire un lien entre nos générations de villes et notre page. Pour cela il nous faut créer un fichier cities.js qui fera les requètes des villes et les envera à la page de manière dynamique.
Pour que la page puisse utiliser le script il faut rajouter à la fin de notre index.hmtl :
```
< !-- Custom script for load cities -->
<script src="js/cities.js"></script>
```

Une fois ceci réaliser il faut mettre notre image à jour.

