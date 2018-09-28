<?php
namespace FreeFW\Model;

use \FreeFW\Tools\PBXString;
use \FreeFW\Interfaces\BaseModel as ModelInterface;

/**
 * Classe de base de gestion en mémoire
 *
 * @author jeromeklam
 * @package Storage
 */
abstract class AbstractMemoryStorage extends \FreeFW\Model\AbstractStorage implements
    ModelInterface,
    \JsonSerializable
{

    /**
     * Données
     * @var array
     */
    protected static $datas = null;

    /**
     * Constructeur
     *
     * @param $resource
     * @param array    $config
     */
    public function __construct($p_datas = null)
    {
        self::$datas = $p_datas;
    }

    /**
     * Recherche
     *
     * @param array  $p_filters   // Tableau de propertyName => value
     * @param array  $p_sortCols  // Tableau de propertyName => ASC/DESC
     * @param array  $p_groupCols // Tableau de propertyName
     * @param number $p_from      // Indice de départ, commence à 0
     * @param number $p_len       // Longueur de recherche, 0 pour illimité
     * @param string $p_fulltext  // Chaine à rechercher dans les champs type fulltext
     * @param string $p_mode      // Mode de recherche : EQUAL / LIKE
     * @param string $p_andor     // Clauses : AND / OR
     *
     * @return \Iterable
     */
    public static function find(
        $p_filters = array(),
        $p_sortCols = array(),
        $p_groupCols = array(),
        $p_from = 0,
        $p_len = 0,
        $p_fulltext = null,
        $p_included = "",
        $p_fields = "",
        $p_mode = "EQUAL",
        $p_andor = "AND"
    ) {
        $resultSet = new \FreeFW\Model\ResultSet();
        foreach (self::$datas as $idx => $value) {
            $resultSet->add($value);
        }
        //TODO
        return $resultSet;
    }

    /**
     * Retourne un enregistrement en fonction de son identifiant(s)
     *
     * @param array $p_values
     *
     * @return object
     */
    public static function findById($p_values = array())
    {
        $resultSet = array();
        foreach (self::$file as $key => $value) {
            foreach ($value as $id => $val) {
                //echo $id,'=>',$val;
                if ($key == 'id') {
                    echo 'toto';
                }
            }
        }
        return $resultSet;
    }

    /**
     * Enregistrement de la modification des données
     *
     * @return boolean
     */
    public function save($new_data)
    {
        $newJsonString = json_encode($new_data);
        file_put_contents(self::$file, $newJsonString);
        return true;
    }

    /**
     * Regarde si un enregistrement existe
     * pour le champ $param
     *
     * @$objectID   : id de l'element
     * @$objectName : nom du champ testé
     *
     * @return boolean
     */
    public static function exist($objectName, $objectID)
    {
        $bool1 = false;
        $bool2 = false;
        foreach (self::file as $key => $value) {
            foreach ($value as $id => $val) {
                if ($key == 'lock_object_id' && $val == $objectID) {
                    $bool1 = true;
                }
                if ($key == 'lock_object_name' && $val == $objectName) {
                    $bool2 = true;
                }
            }
            if ($bool1 && $bool2) {
                return true;
            }
        }
        return false;
    }

     /**
     * Supprime un élément en fonction de son id
     *
     * gestion de suppression par id simple
     *
     * @param number $id // id de l'enregistrement à supprimer
     *
     * @return boolean
     */
    public static function deleteById($id)
    {
        //On récupère la classe appelante
        $class = self::getModelClass();
        $class = $class . "_id";
        $file  = self::file;
        foreach (self::file as $key => $value) {
            foreach ($value as $id => $val) {
                if ($key == $class && $val == $id) {
                    unset($file[$key]);
                    return true;
                }
            }
        }
        return false;
    }
}
