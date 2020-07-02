Les controlleurs, les méthodes du CRUD & co.
---

Par défaut les controlleurs servent de lien entre les requêtes/réponses et le métier. Afin de simplifier les choses le FW met à disposition les méthodes basiques avec également une gestion basique. Le but n'est pas de faire des ces méthodes la couteau suisse qui fait tout, donc voici ce que permettent ces méthodes et leurs limitations.

Les méthodes décrites ci-dessous sont réservées à un fonctionnement RestFul. Pour d'autres controlleurs il faudra en créer de zéro avec leurs logiques, ...

# Fonctionnement général

Ces méthodes ont besoin d'une instance ApiParams pour fonctionner, cette instance doit être disponible dans la requête sous forme de paramètre. Le middleware Api est censé savoir traduire les requêtes et réponses. Plus de détail [ici](./apiparams.md)

```
    /**
     * @var \FreeFW\Http\ApiParams $apiParams
     */
    $apiParams = $p_request->getAttribute('api_params', false);
```

En retour il faut retourner une réponse avec le résultat, voici les options disponibles :

## Réponses

Les données à retourner sont en général une instance qui hérite au minimum d'une des interfaces suivantes :

* \FreeFW\Core\Model
* \FreeFW\Core\StorageModel
* \FreeFW\Core\StorageCacheModel

C'est de nouveau au middleware Api de savoir traduire les codes à retourner et non au controlleur de gérer cette logique.

### createSuccessAddResponse

Retourne une réponse OK pour une création (2*). Cette méthode est prévue pour recevoir en paramètre des données, en général l'élément créé.

### createSuccessUpdateResponse

Retourne une réponse OK pour une modification (2*). Cette méthode est prévue pour recevoir en paramètre des données, en général l'élément modifié

### createSuccessRemoveResponse

Retourne une réponse OK pour une suppression (2*). Cette méthode n'attend pas de paramètre.

### createSuccessEmptyResponse

Retourne une réponse OK (2*) sans contenu. A n'utiliser que si les précédentes réponses ne conviennent pas.

### createSuccessOkResponse

Retourne une réponse OK (2*). Cette méthode est prévue pour recevoir en paramètre des données. A n'utiliser que si les précédentes réponses ne conviennent pas.

### createErrorResponse

Retourne une réponse >= 4*. Le premier paramètre est le code principal de l'erreur, le second les données.

Les méthodes suivantes permettent de gérer les erreurs sur l'instance :

* getErrors : retourne les erreurs
* addErrors : ajoute un tableau d'erreurs
* addError : ajoute une erreurs
* hasErrors : retourne vrai si il y a des erreurs

## Méthodes standards

Ces méthodes sont à utiliser pour des appels simples

### getAll : la recherche

* tout se passe bien : OK + datas demandées
* rien n'a été trouvé : OK + datas = []

### getOne : retourne un élément spécifique selon son ID

* tout se passe bien : OK + data demandée
* rien n'a été trouvée : Erreur 666007 = 'not found'
* si id <0 : Erreur 666006 = 'Id id mandatory'
* si id n'est pas renseigné ou 0 : OK + model initialisé

### createOne : création et retourne l'élement créé

* tout se passe bien : ADD + datas insérées
* erreur pendant l'insert : Erreur 666011 = errors ou 'not insert' si y a pas d'errors
* il n'y a pas de données envoyées : Erreur 666008 = 'no data'

### updateOne : modification et retourne l'élément modifié

* tout se passe bien : OK + datas modifiées
* erreur pendant l'update : Erreur 666009 = errors ou 'not update' si y a pas d'errors
* l'id qu'on veut modifier n'existe pas : Erreur 666012 = 'Id is unavailable'
* il n'y a pas de données envoyées : Erreur 666008 = 'no data'
* l'id qu'on veut modifier n'est pas >0 ou pas renseigné : Erreur 666006 = 'Id is mandatory'

### removeOne : suppression

* tout se passe bien : OK
* erreur pendant le delete : Erreur 666010 = errors ou 'not delete' si y a pas d'errors
* l'id qu'on veut supprimer n'existe pas : Erreur 666012 = 'Id is unavailable'
* l'id qu'on veut supprimer n'est pas >0 ou pas renseigné : Erreur 666006 = 'Id is mandatory'
