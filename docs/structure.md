Structure
---

Voilà la structure basique d'un projet.

```
    |---- app
    |      |---- console.php                            // Fichier pour lancement en ligne de commande
    |---- build                                         // Répertoire de build
    |---- config                                        // Répertoire des fichiers de configuration, fichiers à mettre dans .gitignore
    |      |---- config.php                             // Paramétrage de base, si aucun fichier <server>.config.php n'est trouvé
    |      |---- ini.php                                // Idem mais pour surcharger les directives php.ini
    |      |---- <server>.config.php
    |      `---- <server>.ini.php
    |---- dist                                          // C'est ici quon copie les fichiers pour générer le kit de déploiement
    |---- docs                                          // Le répertoire des documentations
    |---- install                                       // Scripts et fichiers d'installation
    |---- log                                           // Répertoire des logs, en .gitignore
    |---- src                                           // Les sources organisées en namespace PSR4
    |      |---- NS1
    |      `---- NS...
    |---- target                                        // Répertoire de destination des kits d'installation
    |---- tmp                                           // Fichiers de travail, à mettre en .gitignore
    |---- vendor                                        // composer.json
    |---- www                                           // Répertoire base serveur web
    |      |---- .htaccess                              // redirect, rewrite, ...
    |      |---- index.php                              // Point d'entré principal
    |      |---- socket.php                             // socket
    |      `---- websocket.php                          // websocket
    |---- .gitignore                                    // Pour exclure des fichiers du repo, (log, fichiers de travail, ...)
    |---- build.xml                                     // Orienté phing, lié à jenkins
    |---- CHANGELOG                                     // Suivi de version
    |---- composer.json                                 // composer
    `---- readme.md                                     // Readme principal de documentation
```

Les projets suivants sont à inclure :

* jeromeklam/freesso pour le Single Sign-On 
* jeromeklam/freefw pour le FrameWork
* jeromeklam/freews pour les services web

Ce qui amènera également :

* php en 7.2 minimum
* phing/phing pour les scripts de gestion, build, ...
* psr/log
* psr/cache
* psr/http-message
* psr/http-server-middleware
* guzzlehttp/guzzle
* guzzlehttp/psr7
* firebase/php-jwt
* cboden/ratchet pour la partie Wamp2
* php-amqplib/php-amqplib
* react/zmq
* phpmailer/phpmailer

