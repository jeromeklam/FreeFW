Les modèles, la description
---

# La structure

```
    |---- src
    |      |---- Ns1
    |      |      |---- Model
    |      |      |      |---- Base
    |      |      |      |      |---- Model1
    |      |      |      |      `---- Model...
    |      |      |      |---- StorageModel
    |      |      |      |      |---- Model1
    |      |      |      |      `---- Model...
    |      |      |      |---- Model1
    |      |      |      |---- Model...
```

# Principe

* Le modèle représente une table de base de données ou un objet logique,
* Le modèle de base à utiliser se trouve dans le répertoire Model directement,
* Ce modèle hérite d'un modèle dit de "Base" qui lui contient les setter et getter, répertoire Base,
* Ce modèle de base hérite d'un modèle de stockage, répertoire StorageModel, qui décrit les champs et le mode de stockage.
* Instancié via le DI du Framework, il sera initialisé avec un Logger, la configuration et un eventManager.

# Le modèle StorageModel

Ce modèle, classe abstraite, hérite de la classe de base \FreeFW\Core\StorageModel et doit implémenter certains fonctions.
On pourra ainsi effectuer les opérations du CRUD (Create Read Update Delete) de manière automatique.

Voici ci-dessous les méthodes à implémenter.

## La source

Cette méthode si il s'agit d'une table de base de données doit retourner le nom de cette table, elle est obligatoire.

```
    /**
     * Get object source
     *
     * @return string
     */
    public static function getSource() : string
```

## Les propriétés

```
    /**
     * get properties
     *
     * @return array[]
     */
    public static function getProperties() : array
```

Ce tableau doit contenir la liste des champs de la table, la clef étant le nom du membre et la valeur un tableau de paramètres. Pour chaque membre un getter et un setter seront générés. Par défaut le nom du membre est égal au nom du champ en base de données mais celà n'est pas une obligation.

Pour des raisons de lisibilité on peut utiliser des membres statiques, un pour chaque champ. Les paramètres et options sont disponibles sous forme de constantes dans la classe \FreeFW\Constants. Voici quelques exemples :

```
    return [
        'tab_id'   => self::$PRP_TAB_ID,
        'brk_id'   => self::$PRP_BRK_ID,
        'tab_from' => self::$PRP_TAB_FROM,
    ];
```

### La PK

La clef primaire d'une table, elle est obligatoire.

```
    protected static $PRP_TAB_ID = [
        FFCST::PROPERTY_PRIVATE => 'tab_id',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_BIGINT,
        FFCST::PROPERTY_OPTIONS => [FFCST::OPTION_REQUIRED, FFCST::OPTION_PK],
        FFCST::PROPERTY_COMMENT => 'Identifiant de la table',
        FFCST::PROPERTY_SAMPLE  => 123,
    ];
```

### Le broker

Le broker correspond à une séparation par société. Chaque utilisateur se connecte forcément sur un broker ce qui permet de gérer une première restriction. Les méthofes d'initialisation, d'enregistrement et de lecture gèrent ce champ de manière automatique en tant que restriction de donnée basique.

```
    protected static $PRP_BRK_ID = [
        FFCST::PROPERTY_PRIVATE => 'brk_id',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_BIGINT,
        FFCST::PROPERTY_OPTIONS => [FFCST::OPTION_REQUIRED, FFCST::OPTION_BROKER],
        FFCST::PROPERTY_COMMENT => 'Identifiant du broker, pour restriction',
        FFCST::PROPERTY_SAMPLE  => 123,
    ];
```

### Un champ classique

Les autres champs, en fonction de leur type, STRING, NUMBER, BOOLEAN, TEXT, BLOB, ...

```
    protected static $PRP_TAB_FROM = [
        FFCST::PROPERTY_PRIVATE => 'tab_from',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_DATETIMETZ,
        FFCST::PROPERTY_OPTIONS => [],
        FFCST::PROPERTY_COMMENT => 'Date de début de validité',
        FFCST::PROPERTY_SAMPLE  => '2020-02-01 12:44:44',
    ];
```

### Une clef étangère

```
    protected static $PRP_TAB2_ID = [
        FFCST::PROPERTY_PRIVATE => 'tab2_id',
        FFCST::PROPERTY_TYPE    => FFCST::TYPE_BIGINT,
        FFCST::PROPERTY_OPTIONS => [FFCST::OPTION_FK],
        FFCST::PROPERTY_COMMENT => 'Identifiant de la jointure vers table 2',
        FFCST::PROPERTY_SAMPLE  => 123,
        FFCST::PROPERTY_FK      => ['tab2' =>
            [
                FFCST::FOREIGN_MODEL => 'NS1::Model::Tab2',
                FFCST::FOREIGN_FIELD => 'tab2_id',
                FFCST::FOREIGN_TYPE  => \FreeFW\Model\Query::JOIN_LEFT
            ]
        ]
    ];
```

On indique dans ce cas la table liée. On précise le modèle, le champ de destination et le type de jointure.

## Les relations

Cette méthode est optionnelle.

Les relations peuvent être utile pour les vérifications lors des suppressions, ... ou pour retourner les éléments associés lors d'une requête.

```
    /**
     * Get One To many relationShips
     *
     * @return array
     */
    public function getRelationships()
    {
        return [
            'sons' => [
                FFCST::REL_MODEL   => 'NS1::Model::Tab3',
                FFCST::REL_FIELD   => 'tab_id',
                FFCST::REL_TYPE    => \FreeFW\Model\Query::JOIN_LEFT,
                FFCST::REL_COMMENT => 'Les fils de la table 1',
                FFCST::REL_REMOVE  => FFCST::REL_REMOVE_CHECK,
                FFCST::REL_EXISTS  => '6680001',
            ],
        ];
    ]
```

On indique la table, la jointure et on peut également préciser :

* remove : REL_REMOVE_CHECK pour interdire une suppression d'un parent, REL_REMOVE_CASCADE pour supprimer les éléments avec le parent.
* exists : le code d'erreur à retourner en cas d'élément parent trouvé.

## L'autocomplete

Une méthode pour retourner les champs à utiliser lors d'un autocomplete. La requête sera réalisée sous forme de OR. Les champs sont à retourner via un tableau.

```
    /**
     * Get autocomplete field
     *
     * @return string
     */
    public static function getAutocompleteField()
    {
        return ['tab_fld1', 'tab_fld2'];
    }
```

## Les index uniques

Cette méthode est optionnelle.

Nous allons ici retourner l'ensemble des indexes uniques afin de pouvoir contrôler les opérations de création et modification. Toujours retournés sous forme de tableau.

```
    /**
     * Get uniq indexes
     * 
     * @return array[]
     */
    public static function getUniqIndexes()
    {
        return [
            'name' => [
                FFCST::INDEX_FIELDS => 'tab_name',
                FFCST::INDEX_EXISTS => '6690001',
            ]
        ];
    }
```

Le paramètre "exists" permet de retourner le code erreur en cas de problème avec l'index. Le champs fields contient les champs séparés par , ou via un tableau de chaines.

# Le modèle de base

Très simple, ce ne sont que les getters et setters. Cette classe peut facilement être générée.

# Le modèle

On décrit ici la méthode d'initialisation ainsi que les objets périphériques, ..., principalement un membre par clef étrangère. Si nous avons décrit une relation tab2 vers une table externe, il faut créer un membre local nommé tab2 avec le setter et le getter.

```
<?php
namespace NS1\Model;

use \FreeFW\Constants as FFCST;

/**
 * Site
 *
 * @author jeromeklam
 */
class Tab1 extends \NS1\Model\Base\Tab1  implements
    \FreeFW\Interfaces\ApiResponseInterface
{

     /**
      * Table 2
      * @var \NS1\Model\Tab2
      */
     protected $tab2 = null;

    /**
     * Init
     * {@inheritDoc}
     * @see \FreeFW\Core\Model::init()
     */
    public function init()
    {
        $this->tab1_id  = 0;
        $this->brk_id   = 0;
        return $this;
    }

     /**
      * Setter
      *
      * @param \NS1\Model\Tab2 $p_value
      *
      * @return \NS1\Model\Tab1
      */
     public function setTab2($p_value)
     {
          $this->tab2 = $p_value;
          return $this;
     }

     /**
      * Getter
      *
      * @return \NS1\Model\Tab2
      */
     public function getTab2()
     {
          return $this->tab2;
     }
}

```

# Les constantes

## De base pour une propriété de champ

* PROPERTY_PRIVATE    : Le nom du champ en base de données
* PROPERTY_TYPE       : Le type du champ, cf constantes ci-dessous
* PROPERTY_ENUM       : Dans le cas d'un type SELECT, la liste des valeurs dans un tableau
* PROPERTY_OPTIONS    : Les options, cf ci-dessous
* PROPERTY_DEFAULT    : Valeur par défaut du champ
* PROPERTY_COMMENT    : Commentaire
* PROPERTY_SAMPLE     : Exemple de valeur
* PROPERTY_MIN        : Longueur minimale
* PROPERTY_MAX        : Longueur maximale
* PROPERTY_FK         : Données de la clef étrangère
* PROPERTY_DEPRECATED : à Vrai si champ déprécié
* PROPERTY_SCOPE      : Tableau des extensions autorisées, vide pour toutes, (cf scopes des routes, utilisateurs)

## Les types

* TYPE_STRING     : Chaine
* TYPE_MD5        : Chaine md5
* TYPE_PASSWORD   : Mot de passe
* TYPE_TEXT       : Texte
* TYPE_TEXT_HTML  : Chaine au format html (déprécié)
* TYPE_BLOB       : Binaire
* TYPE_JSON       : Chaine au format json
* TYPE_DATE       : Date
* TYPE_DATETIME   : DateHeure
* TYPE_DATETIMETZ : Dateheure avec timezone
* TYPE_BIGINT     : Entier long
* TYPE_BOOLEAN    : Booléen
* TYPE_INTEGER    : Entier
* TYPE_DECIMAL    : Décimal
* TYPE_MONETARY   : Monétaire
* TYPE_TABLE      : Table (déprécié)
* TYPE_SELECT     : Liste de valeur courte
* TYPE_LIST       : Liste de valeur longue
* TYPE_RESULTSET  : Résultat de modèles (déprécié)
* TYPE_FILE       : Fichier (déprécié)
* TYPE_HTML       : Chaine au format html

## Les options pour la propriété PROPERTY_OPTIONS

Pour jouer sur les règles de gestion de base d'un champ d'un modèle

* OPTION_REQUIRED         : Obligatoire
* OPTION_PK               : Clef primaire
* OPTION_FK               : Clef secondaire
* OPTION_JSONIGNORE       : Pas d'export json, ...
* OPTION_LOCAL            : Champ local ou technique non enregistré
* OPTION_UNIQ             : Valeur unique (déprécié, cf getUniqIndexes)
* OPTION_BROKER           : broker
* OPTION_NESTED_PARENT_ID : champ parent id d'un arbre
* OPTION_NESTED_POSITION  : Champ position d'un arbre
* OPTION_NESTED_LEFT      : Champ left d'un arbre nestedtree
* OPTION_NESTED_RIGHT     : Champ right d'un arbre nestedtree
* OPTION_NESTED_LEVEL     : Champ niveau d'un arbre nestedtree
