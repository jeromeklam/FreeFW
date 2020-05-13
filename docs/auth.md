# Middleware d'authentification

## JWT

* https://jwt.io/

Cette authentification est à utiliser pour identifier un utilisateur et/ou que les APIs utilisent cet utilisateur pour filtrer les données.

## HAWK

* https://github.com/hapijs/hawk

N'identifie pas réellement un utilisateur mais permet de sécuriser la requête. A utiliser pour un accès technique à une API. Mais aussi que si la clef utilisée peut être maintenue secrète.

## BASIC

* https://fr.wikipedia.org/wiki/Authentification_HTTP

Authentification basique d'un utilisateur, à n'utiliser que si pas d'autre choix mais forcément en https.

## DIGEST

* https://fr.wikipedia.org/wiki/Authentification_HTTP

Authentification d'un utilisateur, à priviligier à l'utilisation de la méthode BASIC, si possible.