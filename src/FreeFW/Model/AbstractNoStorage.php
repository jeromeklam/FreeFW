<?php
namespace FreeFW\Model;

use \FreeFW\Interfaces\BaseModel as ModelInterface;
use \FreeFW\Lock\LockException;

/**
 * Classe de base de gestion des objets non stockés
 *
 * @author jeromeklam
 * @package Storage
 * @package Abstract
 */
abstract class AbstractNoStorage implements ModelInterface, \JsonSerializable
{

    /**
     * Comportements
     */
    use \FreeFW\Behaviour\DI;
    use \FreeFW\Behaviour\Validation;
    use \FreeFW\Behaviour\LoggerAwareTrait;
    use \FreeFW\Behaviour\Translation;

    /**
     * Modèles inclus
     * @var array
     */
    protected $included_models = [];

    /**
     * Données supplémentaires
     */
    protected $extended_datas = [];

    /**
     * Conversion en booléen
     *
     * @param mixed $p_val
     *
     * @return boolean
     */
    protected function asBoolean($p_val)
    {
        if ($p_val === true || $p_val == 1 || $p_val == '1' || strtoupper($p_val) == 'O'
            || strtoupper($p_val) == 'Y' || strtoupper($p_val) == 'X'
        ) {
            return true;
        }
        return false;
    }

    /**
     * Conversion en float
     *
     * @param unknown $p_val
     *
     * @return float
     */
    protected function asFloat($p_val)
    {
        if (is_float($p_val)) {
            $val = $p_val;
        } else {
            $str = str_replace(',', '.', str_replace(" ", "", $p_val));
            $str = str_replace('&nbsp;', '', str_replace(" ", "", $str));
            $str = str_replace(' ', '', str_replace(" ", "", $str));
            $val = floatval(str_replace(',', '.', $str));
        }
        return $val;
    }

    /**
     * Détection du type de date pour génération d'un format dd/mm/yyyy
     *
     * @param mixed $p_val
     *
     * @return string
     */
    protected function asDate($p_val)
    {
        return \FreeFW\Tools\Date::rpgToddmmyyyy($p_val);
    }

    /**
     * Converti un chemin du genre ns1.ns2::class.champ en \\ns1\\ns2\\Model\class
     *
     * @param unknown $p_str
     *
     * @return array
     */
    protected static function stringToNsClass($p_str)
    {
        $parts = explode('::', $p_str);
        $ns    = false;
        $class = false;
        $field = false;
        if (count($parts) > 1) {
            $ns    = $parts[0];
            $class = $parts[1];
        } else {
            $class = $p_str;
        }
        $parts = explode('.', $class);
        if ($parts > 1) {
            $class = $parts[0];
            $field = $parts[1];
        } else {
            $class = $p_str;
        }
        $parts = explode('.', $ns);
        if (count($parts) > 1) {
            $ns = implode('\\', $parts);
        }
        return array(
            'full'  => '\\' . $ns . '\\Model\\' . $class,
            'ns'    => $ns,
            'class' => $class,
            'field' => $field
        );
    }

    /**
     * Complète l'enregistrement à partir d'un tableau
     *
     * @param array $p_record
     *
     * @return self
     */
    public function getFromRecord($p_record = array())
    {
        $source = '';
        if (method_exists($this, 'getSource')) {
            $source = $this->getSource();
        }
        if (method_exists($this, 'columnMap')) {
            if (is_array($p_record)) {
                foreach ($this::columnMap() as $field => $property) {
                    if (strpos($field, '::') === false && array_key_exists($field, $p_record)) {
                        $setter = 'set' . \FreeFW\Tools\PBXString::toCamelCase($property, true);
                        $this->{$setter}($p_record[$field]);
                    } else {
                        if (array_key_exists($property, $p_record)) {
                            $setter = 'set' . \FreeFW\Tools\PBXString::toCamelCase($property, true);
                            $this->{$setter}($p_record[$property]);
                        } else {
                            if ($source != '' && strpos($field, '::') === false && array_key_exists($source . '_' . $field, $p_record)) {
                                $setter = 'set' . \FreeFW\Tools\PBXString::toCamelCase($property, true);
                                $this->{$setter}($p_record[$source . '_' . $field]);
                            }
                        }
                    }
                }
            } else {
                if (is_object($p_record)) {
                    foreach ($this::columnMap() as $field => $property) {
                        if (strpos($field, '::') === false && array_key_exists($field, $p_record)) {
                            $setter = 'set' . \FreeFW\Tools\PBXString::toCamelCase($property, true);
                            $this->{$setter}($p_record->$field);
                        } else {
                            if (array_key_exists($property, $p_record)) {
                                $setter = 'set' . \FreeFW\Tools\PBXString::toCamelCase($property, true);
                                $this->{$setter}($p_record->$property);
                            } else {
                                if ($source != '' && strpos($field, '::') === false && array_key_exists($source . '_' . $field, $p_record)) {
                                    $setter = 'set' . \FreeFW\Tools\PBXString::toCamelCase($property, true);
                                    $this->{$setter}($p_record->{$source . '_' . $field});
                                }
                            }
                        }
                    }
                }
            }
        } else {
            if (is_array($p_record)) {
                foreach ($p_record as $field => $value) {
                    if (strpos($field, $source . '_') !== false) {
                        $field = str_replace($source . '_', '', $field);
                    }
                    $setter = 'set' . \FreeFW\Tools\PBXString::toCamelCase($field, true);
                    $this->{$setter}($value);
                }
            }
        }
        // On rempli les relations si des éléments ont été envoyés...
        if (method_exists($this, 'relationShips')) {
            foreach ($this->relationShips() as $field => $props) {
                $toTest = 'included_' . strtolower(str_replace('::', '_', $props['class']));
                if (array_key_exists($toTest, $p_record)) {
                    $parts     = explode('::', $props['class']);
                    $className = '\\' . \FreeFW\Tools\PBXString::toCamelCase($parts[0], true) . '\\Model\\' .
                    \FreeFW\Tools\PBXString::toCamelCase($parts[1], true);
                    if (class_exists($className)) {
                        $val  = $p_record[$toTest];
                        $name = $props['name'];
                        foreach ($val as $idxI => $valI) {
                            $incObj = $className::getInstance($valI);
                            if (array_key_exists('type', $props) && $props['type'] == 'array') {
                                $this->$name[] = $incObj;
                            } else {
                                $this->$name = $incObj;
                            }
                        }
                    }
                }
            }
        }
        return $this;
    }

    /**
     * Clone
     *
     * @param \static $p_object
     *
     * @return \FreeFW\Model\AbstractNoStorage
     */
    public function cloneFrom($p_object)
    {
        if (method_exists($this, 'columnMap')) {
            foreach ($this::columnMap() as $field => $property) {
                $setter = 'set' . \FreeFW\Tools\PBXString::toCamelCase($property, true);
                $getter = 'get' . \FreeFW\Tools\PBXString::toCamelCase($property, true);
                $this->{$setter}($p_object->{$getter}());
            }
        }
        return $this;
    }

    /**
     * Retourne une instance à partir d'un enregistrement
     *
     * @param array   $p_record
     * @param boolean $p_allEmpty
     *
     * @return self
     */
    public static function getInstance($p_record = array(), $p_allEmpty = true)
    {
        $new = new static();
        $new->getFromRecord($p_record);
        $cont = true;
        if (!$p_allEmpty) {
            $cont = false;
            $ids  = $new->columnId();
            if (is_array($ids) && count($ids) == 1) {
                $getter  = 'get' . \FreeFW\Tools\PBXString::toCamelCase($ids[0], true);
                $id      = $new->$getter();
                if ($id !== null && $id != '' && $id != '0' && $id !== false) {
                    $cont = true;
                }
            }
            if (!$cont) {
                return null;
            }
        }
        if ($cont && method_exists($new, 'getIncludedFromRecord')) {
            $new->getIncludedFromRecord($p_record);
        }
        return $new;
    }

    /**
     * Retourne l'objet sous forme de tableau
     *
     * @return array
     */
    public function __toArray()
    {
        $arr = array();
        foreach ($this::columnMap() as $column => $field) {
            $getter       = 'get' . \FreeFW\Tools\PBXString::toCamelCase($field, true);
            $arr[$column] = $this->{$getter}();
        }
        if (method_exists($this, 'relationShips')) {
            foreach ($this::relationShips() as $idx => $relation) {
                $name   = $relation['name'];
                $toTest = 'included_' . strtolower(str_replace('::', '_', $relation['class']));
                if (!array_key_exists($toTest, $arr)) {
                    $arr[$toTest] = [];
                }
                if ($this->{$name}) {
                    $arr[$toTest][] = $this->{$name}->__toArray();
                }
            }
        }
        return $arr;
    }

    /**
     * Retourne l'objet sous forme de tableau
     *
     * @return array
     */
    public function __toFields()
    {
        $arr = array();
        foreach ($this::columnId() as $field) {
            $getter     = 'get' . \FreeFW\Tools\PBXString::toCamelCase($field, true);
            $arr['key'] = $this->{$getter}();
        }
        if (method_exists($this, 'columnJsonSerialize')) {
            foreach ($this::columnJsonSerialize() as $column) {
                $getter       = 'get' . \FreeFW\Tools\PBXString::toCamelCase($column, true);
                $content      = $this->{$getter}();
                if (is_object($content) && method_exists($content, '__toArray')) {
                    $content = $content->__toArray();
                }
                if (is_string($content)) {
                    $res = json_encode($content);
                    if ($res == false) {
                        $content = base64_encode($content);
                    }
                }
                $arr[$column] = $content;
            }
        } else {
            foreach ($this::columnMap() as $column => $field) {
                $getter  = 'get' . \FreeFW\Tools\PBXString::toCamelCase($field, true);
                $content = $this->{$getter}();
                if (is_object($content) && method_exists($content, '__toArray')) {
                    $content = $content->__toArray();
                }
                if (is_string($content)) {
                    $res = json_encode($content);
                    if ($res == false) {
                        $content = base64_encode($content);
                    }
                }
                $arr[$field] = $content;
            }
        }
        return $arr;
    }

    /**
     * Sérialise l'object
     *
     * @return array
     */
    public function jsonSerialize()
    {
        $fields = array();
        $revers = array_flip($this::columnMap());
        if (method_exists($this, 'columnJsonSerialize')) {
            $list = $this::columnJsonSerialize();
        } else {
            $list = $this::columnMap();
        }
        foreach ($this::columnId() as $property => $field) {
            if (!array_key_exists($property, $revers)) {
                if (is_array($field) && count($field) > 0) {
                    $fields[$property] = null;
                    foreach ($field as $pp) {
                        $getter = 'get' . \FreeFW\Tools\PBXString::toCamelCase($pp, true);
                        if ($fields[$property] === null) {
                            $fields[$property] = $this->{$getter}();
                        } else {
                            $fields[$property] .= '__' . $this->{$getter}();
                        }
                    }
                }
            }
        }
        foreach ($list as $field => $property) {
            $getter  = 'get' . \FreeFW\Tools\PBXString::toCamelCase($property, true);
            $content = $this->{$getter}();
            if (is_string($content)) {
                $res = json_encode($content);
                if ($res == false) {
                    $content = utf8_decode($content);
                }
            }
            $fields[$property] = $content;
        }
        foreach ($this->extended_datas as $key => $value) {
            $fields[$key] = $value;
        }
        return $fields;
    }

    /**
     * Retourne l'élement sous forme de chaine
     *
     * @return string
     */
    public function serialize()
    {
        return json_encode($this->jsonSerialize());
    }

    /**
     * Affectation des valeurs contenues dans la requête
     *
     * @param \FreeFW\Interfaces\Request
     *
     * @return Object
     */
    public function bindFromRequest($p_request)
    {
        //Recherche du type de l'objet
        $chemin     = explode("\\", get_class($this));
        $typeObject = $chemin[count($chemin)-1];
        $params     = $p_request->getAttributes();
        if (method_exists($this, 'getColumnDescByField')) {
            $cols = $this->getColumnDescByField();
            foreach ($cols as $name => $field) {
                $setter = $field['setter'];
                if (array_key_exists($field['field'], $params)) {
                    if ($field['type'] == \FreeFW\Constants::TYPE_BOOLEAN) {
                        $this->$setter(true);
                    } else {
                        $this->$setter($params[$name]);
                    }
                } else {
                    if ($field['type'] == \FreeFW\Constants::TYPE_BOOLEAN) {
                        $this->$setter(false);
                    }
                }
            }
        } else {
            $cols = array_flip($this::columnMap());
            foreach ($cols as $name => $field) {
                if (array_key_exists($name, $params)) {
                    $setter = 'set' . \FreeFW\Tools\PBXString::toCamelCase($name, true);
                    $this->$setter($params[$name]);
                }
            }
        }
        return $this;
    }

    /**
     * Fonction standard pour get and set
     *
     * @param string $p_name
     * @param array  $p_args
     *
     * @throws \Exception
     */
    public function __call($p_name, $p_args)
    {
        $propName = false;
        $mode     = false;
        if (substr($p_name, 0, 7) == 'display') {
            $propName = \FreeFW\Tools\PBXString::fromCamelCase(substr($p_name, 7));
            $mode     = 'DISPLAY';
            if (!method_exists($this, $p_name)) {
                $p_name = 'get' . substr($p_name, 7);
                $mode   = false;
            }
        }
        if ($mode === false) {
            if (substr($p_name, 0, 3) == 'get') {
                $propName = \FreeFW\Tools\PBXString::fromCamelCase(substr($p_name, 3));
                $mode     = 'GET';
            } else {
                if (substr($p_name, 0, 3) == 'set' && count($p_args) == 1) {
                    $propName = \FreeFW\Tools\PBXString::fromCamelCase(substr($p_name, 3));
                    $mode     = 'SET';
                } else {
                    throw new \Exception(sprintf('Bad method %s!', $p_name), 500);
                }
            }
        }
        if ($propName !== false && property_exists($this, $propName)) {
            switch ($mode) {
                case 'GET':
                    return $this->{$propName};
                case 'SET':
                    $this->{$propName} = $p_args[0];
                    return $this;
                default:
                    throw new \Exception(sprintf('Unknown mode: %s -- %s!', $mode, $p_name), 500);
                    break;
            }
        } else {
            throw new \Exception(sprintf('Property does not exists: %s -- %s', get_class($this), $p_name), 500);
        }
    }

    /**
     * Magic display...
     *
     * @param string $p_name
     *
     * @return mixed
     */
    public function __display($p_name)
    {
        $propName = 'display' . \FreeFW\Tools\PBXString::toCamelCase($p_name, true);
        return $this->{$propName}();
    }

    /**
     * Fonction pour recuperer les arguments d'une commande console
     *
     * @return array
     */
    public function getParamsCMD()
    {
        $cpt = 1;
        $cols = array_flip($this::columnMap());
        foreach ($cols as $name => $field) {
            if (isset($_SERVER[ 'argv' ][ $cpt ])) {
                //Si l'argument commence bien par ":"
                if (stripos($_SERVER[ 'argv' ][ $cpt ], ':') == 0) {
                    //On retire les ":"
                    $argument = str_replace(":", "", $_SERVER[ 'argv' ][ $cpt ]);
                    $arrayParam[$name] = $argument;
                } else {
                    return $arrayParam;
                }
            } else {
                $arrayParam[$name] = "NULL";
            }
            //On incremente le compteur pour parcourir tout les champs
            $cpt++;
        }
        return $arrayParam;
    }

    /**
     * Fonction qui permet de passer des arguments
     *
     * via interface console
     *
     * @return array
     */
    public function interfaceCMD()
    {
        $cols = array_flip($this::columnMap());
        foreach ($cols as $name => $field) {
            if ($name != "id") {
                echo "\n".$name.":\n";
                //Lecture de l'entree utilisateur
                $input  = fgets(STDIN);
                $setter = 'set' . \FreeFW\Tools\PBXString::toCamelCase($name, true);
                $this->$setter($input);
            }
        }
    }

    /**
     * Retourne le nom de champ
     *
     * @param string $p_field
     *
     * @return string
     */
    public function getRealFieldName($p_field)
    {
        $arr = array_flip($this->columnMap());
        if (array_key_exists($p_field, $arr)) {
            return $arr[$p_field];
        }
        return false;
    }

    /**
     * Purge des données supplémentaires
     *
     * @return \FreeFW\Model\AbstractStorage
     */
    public function flushExtendedDatas()
    {
        $this->extended_datas = [];
        return $this;
    }

    /**
     * Ajout d'une données supplémentaire
     *
     * @param string $p_key
     * @param mixed  $p_value
     *
     * @return \FreeFW\Model\AbstractStorage
     */
    public function addExtendedData($p_key, $p_value)
    {
        $this->extended_datas[$p_key] = $p_value;
        return $this;
    }

    /**
     * Retourne les données supplémentaires
     *
     * @return array
     */
    public function getExtendedDatas()
    {
        return $this->extended_datas;
    }
}
