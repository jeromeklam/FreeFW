CRUD
---

Le plus simple est d'utiliser le FW pour nous retourner une instance propre.

```
     $model = \FreeFW\DI\DI::get('NS1::Model::Tab1');
```

En plus de retourner le modèle vide et initialisé, la méthode va alimenter le modèle avec la stratégie de stockage, le logger, ...

L'interface "Fluent" est implémentée et on peut donc enchaîner les appels.

```
     $model
          ->setTab1(0)
          ->setBrkId(0)
     ;
```

# Appeler une méthode du CRUD.

Les méthodes n'ont pas forcément le nom attendu :

* create
* find
* save
* remove

L'appel est très simple :

```
     $model->create();
```

Par défaut les erreurs sont trappées et injectées dans le modèle, le tout est également effectué en transaction.

```
     if ($model->isValid()) {
         if ($model->create(true) {
              $this->addErrors($model->getErrors());
         }
     } else {
         $this->addErrors($model->getErrors());
     }
```

Les modèles implémentent également l'interface "validator" ce qui permet de déjà gérer de manière automatique les champs obligatoires, ... Mais on peut également surcharger la méthode localement pour compléter les vérifications.

```
    /**
     * Validate model
     *
     * @return void
     */
    protected function validate() : bool
```

Il faut simplement penser à appeler en premier la méthode parente.


