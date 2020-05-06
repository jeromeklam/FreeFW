Cheminement
---

C'est un framework destiné à ne servir qu'en mode service-web. Pour celà toute requête va passer par le point d'entrée qui est le fichier www/index.php. Voici les principales étapes de la requête jusqu'à la réponse :

# index.php

Le point d'entrée initialise tout et à l'intérieur d'une gestion d'exception effectue les tâches suivantes :

* Préparation du chargement des classes (via le loader de composer)
* Recherche des fichiers de configuration 
* Chargement de la configuration
* Initialisation du logger
* Initialisation de la file d'attente (web socket)
* Connexion base de données
* Initialisation de l'application
* Mise en place de l'event manager
* Chargement des modules (FW, SSO, WS, ...) routes, ...
* Demande de gestion

# Application

La demande est traitée par l'application :

* récupération d'une requête PSR
* Utilisation d'un router pour détecter la route
* Initialisation des middlewares en fonction de la route et des paramètres de la requête
* Pipeline des middlewares "IN" pour transformer / adapter la requête
* Exécution de la méthode paramétrée dans la route qui retourne une réponse PSR
* Pipeline des middlewares "OUT" pour transformer / adapter la réponse.
* On retourne la réponse

# Middlewares

* FreeFW::IgnoreMethod   : permet d'ignorer certaines méthodes comme OPTIONS, HEAD, ... qui n'ont pas besoins de traitements particuliers
* FreeFW::AuthNegociator : permet de gérer différentes méthodes de sécurité (JWT, Hawk, basic, ...)
* FreeFW::ApiNegociator  : permet de gérer différents formats json, vnd.api+json, ...
* FreeFW::Router         : pour exécuter la route
* FreeSSO::Broker        : pour vérifier l'entête ApiId qui est censé être le broker : obligatoire
