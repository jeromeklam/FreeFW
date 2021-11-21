<?php
namespace FreeFW\Core;

use \FreeFW\Constants as FFCST;
use FreeFW\JsonApi\V1\Model\IncludedObject;

/**
 * Standard model
 *
 * @author jeromeklam
 */
abstract class Model implements
    \FreeFW\Interfaces\ApiResponseInterface,
    \FreeFW\Interfaces\ValidatorInterface,
    \FreeFW\Interfaces\ConfigAwareTraitInterface,
    \Psr\Log\LoggerAwareInterface,
    \Serializable
{

    /**
     * Model behaviour
     * @var string
     */
    const MODEL_BEHAVIOUR_RAW      = 'RAW';
    const MODEL_BEHAVIOUR_STANDARD = 'STANDARD';
    const MODEL_BEHAVIOUR_UPDATED  = 'UPDATED';
    const MODEL_BEHAVIOUR_API      = 'API';

    /**
     * Behaviour
     */
    use \FreeFW\Behaviour\ValidatorTrait;
    use \FreeFW\Behaviour\ConfigAwareTrait;
    use \FreeFW\Behaviour\AutomateAwareTrait;
    use \Psr\Log\LoggerAwareTrait;

    /**
     * Model behaviour
     * @var string
     */
    protected $model_behaviour = self::MODEL_BEHAVIOUR_STANDARD;

    /**
     * Updated fields
     * @var array
     */
    protected $updated = [];

    /**
     * TS
     * @var number
     */
    protected $__ts = null;

    /**
     * Memory cache
     * @var array
     */
    protected static $__cache = [];

    /**
     * Constructor
     */
    public function __construct(
        \FreeFW\Application\Config $p_config = null,
        \Psr\Log\AbstractLogger $p_logger = null
    ) {
        if ($p_config) {
            $this->setAppConfig($p_config);
        } else {
            $this->setAppConfig(\FreeFW\DI\DI::getShared('config'));
        }
        if ($p_logger) {
            $this->setLogger($p_logger);
        } else {
            $this->setLogger(\FreeFW\DI\DI::getShared('logger'));
        }
        $this->initModel();
        $this->init();
    }

    /**
     * Set ts
     *
     * @return self
     */
    public function setTs()
    {
        $this->__ts = microtime(true);
        return $this;
    }

    /**
     * Get ts
     *
     * @return number
     */
    public function getTs()
    {
        return $this->__ts;
    }

    /**
     * Initialisation
     */
    public function init()
    {
        return $this;
    }

    /**
     * Merge with an exising object
     *
     * @param \FreeFW\Core\Model $p_model
     * @param boolean          $p_only_updated
     * @param boolean          $p_update_id
     *
     * @return \FreeFW\Core\Model
     */
    public function merge(\FreeFW\Core\Model $p_model, $p_only_updated = true, $p_update_id = false)
    {
        if (get_class($this) == get_class($p_model)) {
            if (method_exists($this, 'getRelationships')) {
                foreach ($this->getRelationships() as $relName => $relation) {
                    if (!$p_only_updated || $p_model->hasBeenUpdated($relName)) {
                        $setter = 'set' . \FreeFW\Tools\PBXString::toCamelCase($relName, true);
                        $getter = 'get' . \FreeFW\Tools\PBXString::toCamelCase($relName, true);
                        $this->{$setter}($p_model->{$getter}());
                    }
                }
            }
            foreach ($this->getModelDescriptionProperties() as $name => $property) {
                $options = [];
                if (isset($property[FFCST::PROPERTY_OPTIONS])) {
                    $options = $property[FFCST::PROPERTY_OPTIONS];
                }
                if (in_array(FFCST::OPTION_FK, $options)) {
                    foreach ($property[FFCST::PROPERTY_FK] as $fkName => $relation) {
                        if (!$p_only_updated || $p_model->hasBeenUpdated($fkName)) {
                            $setter = 'set' . \FreeFW\Tools\PBXString::toCamelCase($fkName, true);
                            $getter = 'get' . \FreeFW\Tools\PBXString::toCamelCase($fkName, true);
                            $this->{$setter}($p_model->{$getter}());
                        }
                    }
                }
                if (!$p_only_updated || $p_model->hasBeenUpdated($name)) {
                    $getter = 'get' . \FreeFW\Tools\PBXString::toCamelCase($name, true);
                    $setter = 'set' . \FreeFW\Tools\PBXString::toCamelCase($name, true);
                    $this->$setter($p_model->$getter());
                }
            }
        }
        return $this;
    }

    /**
     * Set model behaviour
     *
     * @param string $p_behaviour
     *
     * @return \FreeFW\Core\Model
     */
    public function setModelBehaviour($p_behaviour)
    {
        $this->model_behaviour = $p_behaviour;
        return $this;
    }

    /**
     * Get model behaviour
     *
     * @return string
     */
    public function getModelBehaviour()
    {
        return $this->model_behaviour;
    }

    /**
     * Raw behaviour ?
     *
     * @return boolean
     */
    public function isRawBehaviour()
    {
        return $this->model_behaviour === self::MODEL_BEHAVIOUR_RAW;
    }

    /**
     * Api behaviour ?
     *
     * @return boolean
     */
    public function isApiBehaviour()
    {
        return $this->model_behaviour === self::MODEL_BEHAVIOUR_API;
    }

    /**
     * Magic call
     *
     * @param string $p_methodName
     * @param array  $p_args
     *
     * @throws \FreeFW\Core\FreeFWMemberAccessException
     * @throws \FreeFW\Core\FreeFWMethodAccessException
     *
     * @return mixed
     */
    public function __call($p_methodName, $p_args)
    {
        if (preg_match('~^(set|get)([A-Z])(.*)$~', $p_methodName, $matches)) {
            $property = \FreeFW\Tools\PBXString::fromCamelCase($matches[2] . $matches[3]);
            if (!property_exists($this, $property)) {
                //echo \FreeFW\Tools\Exception::format();
                throw new \FreeFW\Core\FreeFWMemberAccessException(
                    'Property ' . $property . ' doesn\'t exists for ' . $p_methodName . ' !'
                );
            }
            switch ($matches[1]) {
                case 'set':
                    return $this->set($property, $p_args[0]);
                case 'get':
                    return $this->get($property);
                default:
                    //echo \FreeFW\Tools\Exception::format();
                    throw new \FreeFW\Core\FreeFWMemberAccessException(
                        'Method ' . $p_methodName . ' doesn\'t exists !'
                    );
            }
        } else {
            //echo \FreeFW\Tools\Exception::format();
            throw new \FreeFW\Core\FreeFWMethodAccessException(
                'Method ' . $p_methodName . ' doesn\'t exists !'
            );
        }
    }

    /**
     * Get a property
     *
     * @param string $p_property
     *
     * @return mixed
     */
    public function get($p_property)
    {
        return $this->$p_property;
    }

    /**
     * Set a field as updated
     *
     * @param string $p_field
     *
     * @return boolean
     */
    public function addUpdatedField($p_field)
    {
        $this->updated[$p_field] = true;
        return true;
    }

    /**
     * Return all updated fields
     *
     * @return array
     */
    public function getUpdatedFields()
    {
        if ($this->model_behaviour == self::MODEL_BEHAVIOUR_UPDATED || $this->model_behaviour == self::MODEL_BEHAVIOUR_API) {
            return array_keys($this->updated);
        } else {
            return array_keys($this->getProperties());
        }
    }

    /**
     * True if field has benn updated
     *
     * @param string $p_field
     *
     * @return boolean
     */
    public function hasBeenUpdated($p_field)
    {
        if ($this->model_behaviour == self::MODEL_BEHAVIOUR_UPDATED || $this->model_behaviour == self::MODEL_BEHAVIOUR_API) {
            if (isset($this->updated[$p_field])) {
                return true;
            }
            return false;
        }
        return true;
    }

    /**
     * Set a property
     *
     * @param string $p_property
     * @param mixed  $p_value
     *
     * @return static
     */
    public function set($p_property, $p_value)
    {
        $this->$p_property = $p_value;
        $this->addUpdatedField($p_property);
        return $this;
    }

    /**
     * Get base64 src format
     *
     * @param mixed $p_data
     *
     * @return boolean|string
     */
    protected function decode_chunk($p_data)
    {
        if (strpos($p_data, ';base64,') !== false) {
            $data = explode(';base64,', $p_data);
            if (!is_array($data) || !isset($data[1])) {
                return false;
            }
            $data = base64_decode($data[1]);
            if (!$data) {
                return false;
            }
            return $data;
        }
        return base64_decode($p_data);
    }

    /**
     * Init object with datas
     *
     * @param array $p_datas
     *
     * @return \FreeFW\Core\Model
     */
    public function initWithJson(array $p_datas = [], array $p_relations = [], array $p_included = [])
    {
        $description = $this->getModelDescription();
        $props = $description['properties'];
        $this->initModel();
        foreach ($p_datas as $name => $value) {
            foreach ($props as $prp => $property) {
                $test = $prp;
                if (isset($property[FFCST::PROPERTY_PUBLIC])) {
                    $test = $property[FFCST::PROPERTY_PUBLIC];
                }
                if ($test == $name) {
                    //if (!in_array(FFCST::OPTION_LOCAL, $property[FFCST::PROPERTY_OPTIONS])) {
                        $this->addUpdatedField($prp);
                        $type   = $property[FFCST::PROPERTY_TYPE];
                        $setter = $property[FFCST::PROPERTY_SETTER];
                        switch ($type) {
                            case FFCST::TYPE_BLOB:
                                $this->$setter($this->decode_chunk($value));
                                break;
                            case FFCST::TYPE_BIGINT:
                            case FFCST::TYPE_INTEGER:
                                if ($value != '') {
                                    $this->$setter(intval($value));
                                }
                            default:
                                $this->$setter($value);
                                break;
                        }
                    //}
                    break;
                }
            }
        }
        foreach ($p_relations as $name => $relation) {
            if ($relation['type'] == \FreeFW\JsonApi\V1\Model\RelationshipObject::ONE_TO_ONE) {
                $foundRel = false;
                foreach ($props as $prp => $property) {
                    $test = $prp;
                    if (isset($property[FFCST::PROPERTY_PUBLIC])) {
                        $test = $property[FFCST::PROPERTY_PUBLIC];
                    }
                    if (isset($property[FFCST::PROPERTY_FK])) {
                        $fks = $property[FFCST::PROPERTY_FK];
                        if (isset($fks[$relation['name']])) {
                            $fk = $fks[$relation['name']];
                            // Complete empty object
                            $id = null;
                            foreach ($relation['values'] as $val) {
                                switch ($property[FFCST::PROPERTY_TYPE]) {
                                    case FFCST::TYPE_BIGINT:
                                    case FFCST::TYPE_INTEGER:
                                        if ($val != '') {
                                            $id = intval($val);
                                        }
                                        break;
                                    default:
                                        $id = $val;
                                        break;
                                }
                                break;
                            }
                            $setter = 'set' . \FreeFW\Tools\PBXString::toCamelCase($relation['name'], true);
                            $class  = '\\' . str_replace('::', '\\', $fk['model']);
                            $found  = false;
                            foreach ($p_included as $oneIncluded) {
                                if ($oneIncluded instanceof $class && $oneIncluded->getApiId() == $id) {
                                    $this->addUpdatedField($prp); // id first
                                    $this->addUpdatedField($relation['name']); // relation name too
                                    $found = true;
                                    $this->$setter($oneIncluded);
                                    break;
                                }
                            }
                            /*
                            if (!$found) {
                                $rel = \FreeFW\DI\DI::get($fk['model']);
                                $rel->setApiId($id);
                                $this->$setter($rel); $this->addUpdatedField($prp);
                                $this->addUpdatedField($prp); // id first
                                $this->addUpdatedField($relation['name']); // relation name too
                            }*/
                            // property
                            $this->addUpdatedField($test);
                            $setter = 'set' . \FreeFW\Tools\PBXString::toCamelCase($test, true);
                            $this->$setter($id);
                            $foundRel = true;
                            break;
                        }
                    }
                }
                if (!$foundRel) {
                    $setter = 'set' . \FreeFW\Tools\PBXString::toCamelCase($name, true);
                    if (method_exists($this, $setter)) {
                        $id = 0;
                        foreach ($relation['values'] as $val) {
                            $id = $val;
                            break;
                        }
                        $class  = '\\' . str_replace('::', '\\', $relation['model']);
                        foreach ($p_included as $oneIncluded) {
                            if ($oneIncluded instanceof $class && $oneIncluded->getApiId() == $id) {
                                $this->$setter($oneIncluded);
                                break;
                            }
                        }
                    }
                }
            } else {
                if (method_exists($this, 'getRelationships')) {
                    $mRels = $this->getRelationships();
                    if (isset($mRels[$name])) {
                        $rels   = [];
                        $setter = 'set' . \FreeFW\Tools\PBXString::toCamelCase($name, true);
                        foreach ($relation['values'] as $val) {
                            $found = false;
                            $class = '\\' . str_replace('::', '\\', $mRels[$name]['model']);
                            $found = false;
                            foreach ($p_included as $oneIncluded) {
                                if ($oneIncluded instanceof $class && $oneIncluded->getApiId() == $val) {
                                    $this->addUpdatedField($name); // name
                                    $found  = true;
                                    $rels[] = $oneIncluded;
                                    break;
                                }
                            }
                            if (!$found) {
                                $this->addUpdatedField($name); // name
                                $rel = \FreeFW\DI\DI::get($mRels[$name]['model']);
                                $rel->setApiId($val);
                                $rels[] = $rel;
                            }
                        }
                        $this->$setter($rels);
                    } else {
                        $setter = 'set' . \FreeFW\Tools\PBXString::toCamelCase($name, true);
                        if (method_exists($this, $setter)) {
                            $this->$setter($rels);
                        }
                    }
                } else {
                    $setter = 'set' . \FreeFW\Tools\PBXString::toCamelCase($name, true);
                    if (method_exists($this, $setter)) {
                        $this->$setter($rels);
                    }
                }
            }
        }
        return $this;
    }

    /**
     *
     * {@inheritDoc}
     * @see \FreeFW\Interfaces\ApiResponseInterface::getFieldNameByOption()
     */
    public function getFieldNameByOption($p_option) : string
    {
        $description = $this->getModelDescription();
        foreach ($description['properties'] as $name => $property) {
            if (isset($property[FFCST::PROPERTY_OPTIONS])) {
                if (in_array($p_option, $property[FFCST::PROPERTY_OPTIONS])) {
                   return $name;
                }
            }
        }
        return '';
    }

    /**
     *
     * {@inheritDoc}
     * @see \FreeFW\Interfaces\ApiResponseInterface::getFieldName()
     */
    public function getFieldName(string $p_field, $p_option=FFCST::PROPERTY_PUBLIC) : string
    {
        $description = $this->getModelDescription();
        $props       = $description['properties'];
        //
        switch ($p_option) {
            case FFCST::PROPERTY_PUBLIC:
                if (isset($props[$p_field])) {
                    if (isset($props[$p_field][FFCST::PROPERTY_PUBLIC])) {
                        return $props[$p_field][FFCST::PROPERTY_PUBLIC];
                    }
                }
                break;
            case FFCST::PROPERTY_PRIVATE:
                foreach ($props as $property) {
                    if (isset($property[FFCST::PROPERTY_PUBLIC])) {
                        if ($property[FFCST::PROPERTY_PUBLIC]==$p_field) {
                            return $property[FFCST::PROPERTY_PRIVATE];
                        }
                    }
                }
                break;
        }
        //
        return $p_field;
    }

    /**
     *
     * @see \FreeFW\Interfaces\ApiResponseInterface
     */
    public function getApiId() : string
    {
        $description = $this->getModelDescription();
        foreach ($description['properties'] as $name => $property) {
            if (isset($property[FFCST::PROPERTY_OPTIONS])) {
                if (in_array(FFCST::OPTION_PK, $property[FFCST::PROPERTY_OPTIONS])) {
                    $getter = 'get' . \FreeFW\Tools\PBXString::toCamelCase($name, true);
                    return (string)$this->$getter();
                }
            }
        }
        return '';
    }

    /**
     *
     * @see \FreeFW\Interfaces\ApiResponseInterface
     */
    public function setApiId($p_id)
    {
        $description = $this->getModelDescription();
        foreach ($description['properties'] as $name => $property) {
            if (isset($property[FFCST::PROPERTY_OPTIONS])) {
                if (in_array(FFCST::OPTION_PK, $property[FFCST::PROPERTY_OPTIONS])) {
                    $setter = 'set' . \FreeFW\Tools\PBXString::toCamelCase($name, true);
                    $id = null;
                    switch ($property[FFCST::PROPERTY_TYPE]) {
                        case FFCST::TYPE_BIGINT:
                        case FFCST::TYPE_INTEGER:
                            if ($p_id != '') {
                                $id = intval($p_id);
                            }
                            break;
                        default:
                            $id = $p_id;
                            break;
                    }
                    return $this->$setter($id);
                }
            }
        }
        return $this;
    }

    /**
     *
     * @see \FreeFW\Interfaces\ApiResponseInterface
     */
    public function getApiNestedParentId() : string
    {
        foreach ($this->getModelDescriptionProperties() as $name => $property) {
            if (array_key_exists(FFCST::PROPERTY_OPTIONS, $property)) {
                if (in_array(FFCST::OPTION_NESTED_PARENT_ID, $property[FFCST::PROPERTY_OPTIONS])) {
                    $getter = 'get' . \FreeFW\Tools\PBXString::toCamelCase($name, true);
                    return (string)$this->$getter();
                }
            }
        }
        return '';
    }

    /**
     *
     * @see \FreeFW\Interfaces\ApiResponseInterface
     */
    public function getApiNestedPosition() : string
    {
        foreach ($this->getModelDescriptionProperties() as $name => $property) {
            if (array_key_exists(FFCST::PROPERTY_OPTIONS, $property)) {
                if (in_array(FFCST::OPTION_NESTED_POSITION, $property[FFCST::PROPERTY_OPTIONS])) {
                    $getter = 'get' . \FreeFW\Tools\PBXString::toCamelCase($name, true);
                    return (string)$this->$getter();
                }
            }
        }
        return '';
    }

    /**
     *
     * @see \FreeFW\Interfaces\ApiResponseInterface
     */
    public function getApiNestedLeft() : string
    {
        foreach ($this->getModelDescriptionProperties() as $name => $property) {
            if (array_key_exists(FFCST::PROPERTY_OPTIONS, $property)) {
                if (in_array(FFCST::OPTION_NESTED_LEFT, $property[FFCST::PROPERTY_OPTIONS])) {
                    $getter = 'get' . \FreeFW\Tools\PBXString::toCamelCase($name, true);
                    return (string)$this->$getter();
                }
            }
        }
        return '';
    }

    /**
     *
     * @see \FreeFW\Interfaces\ApiResponseInterface
     */
    public function getApiNestedRight() : string
    {
        foreach ($this->getModelDescriptionProperties() as $name => $property) {
            if (array_key_exists(FFCST::PROPERTY_OPTIONS, $property)) {
                if (in_array(FFCST::OPTION_NESTED_RIGHT, $property[FFCST::PROPERTY_OPTIONS])) {
                    $getter = 'get' . \FreeFW\Tools\PBXString::toCamelCase($name, true);
                    return (string)$this->$getter();
                }
            }
        }
        return '';
    }

    /**
     *
     * @see \FreeFW\Interfaces\ApiResponseInterface
     */
    public function getApiNestedLevel() : string
    {
        foreach ($this->getModelDescriptionProperties() as $name => $property) {
            if (array_key_exists(FFCST::PROPERTY_OPTIONS, $property)) {
                if (in_array(FFCST::OPTION_NESTED_LEVEL, $property[FFCST::PROPERTY_OPTIONS])) {
                    $getter = 'get' . \FreeFW\Tools\PBXString::toCamelCase($name, true);
                    return (string)$this->$getter();
                }
            }
        }
        return '';
    }

    /**
     *
     * @see \FreeFW\Interfaces\ApiResponseInterface
     */
    public function getApiType() : string
    {
        $class = get_called_class();
        $class = rtrim(ltrim($class, '\\'), '\\');
        $class = str_replace('\\Model\\', '_', $class);
        return $class;
    }

    /**
     * Get attributes
     *
     * @return array
     */
    public function getApiAttributes() : array
    {
        $attributes = [];
        $description = $this->getModelDescription();
        foreach ($description['properties'] as $name => $property) {
            $oneAttribute = new \FreeFW\JsonApi\V1\Model\AttributeObject($name);
            $getter       = $property[FFCST::PROPERTY_GETTER];
            $oneAttribute->setValue($this->$getter());
            if (isset($property[FFCST::PROPERTY_PUBLIC])) {
                $oneAttribute->setJsonName($property[FFCST::PROPERTY_PUBLIC]);
            }
            if (isset($property[FFCST::PROPERTY_OPTIONS])) {
                if (in_array(FFCST::OPTION_PK, $property[FFCST::PROPERTY_OPTIONS]) ||
                    in_array(FFCST::OPTION_FK, $property[FFCST::PROPERTY_OPTIONS]) ||
                    in_array(FFCST::OPTION_BROKER, $property[FFCST::PROPERTY_OPTIONS]) ||
                    in_array(FFCST::OPTION_JSONIGNORE, $property[FFCST::PROPERTY_OPTIONS])) {
                    $oneAttribute->setJsonIgnore(true);
                }
            }
            $oneAttribute->setType($property[FFCST::PROPERTY_TYPE]);
            $attributes[] = $oneAttribute;
        }
        return $attributes;
    }

    /**
     * Get relations
     *
     * @return array
     */
    public function getApiRelationShips() : array
    {
        $relations = [];
        $description = $this->getModelDescription();
        /**
         * One to One, an attribute is the Foreign Key
         */
        foreach ($description['properties'] as $name => $property) {
            if (isset($property[FFCST::PROPERTY_FK])) {
                foreach ($property[FFCST::PROPERTY_FK] as $nameP => $valueP) {
                    $oneRelation = new \FreeFW\JsonApi\V1\Model\RelationshipObject($nameP);
                    $oneRelation->setType(\FreeFW\JsonApi\V1\Model\RelationshipObject::ONE_TO_ONE);
                    $oneRelation->setPropertyName($name);
                    $oneRelation->setModel($valueP['model']);
                    $relations[] = $oneRelation;
                }
            }
        }
        /**
         * One to Many, we use the id field
         */
        if (method_exists($this, 'getRelationships')) {
            foreach ($this->getRelationships() as $name => $oneRelationDes) {
                $oneRelation = new \FreeFW\JsonApi\V1\Model\RelationshipObject($name);
                $oneRelation->setType(\FreeFW\JsonApi\V1\Model\RelationshipObject::ONE_TO_MANY);
                $oneRelation->setPropertyName($name);
                $oneRelation->setModel($oneRelationDes['model']);
                $relations[] = $oneRelation;
            }
        }
        return $relations;
    }

    /**
     * @see \FreeFW\Interfaces\ApiResponseInterface
     *
     * @return bool
     */
    public function isSingleElement() : bool
    {
        return true;
    }

    /**
     * @see \FreeFW\Interfaces\ApiResponseInterface
     *
     * @return bool
     */
    public function isArrayElement() : bool
    {
        return false;
    }

    /**
     *
     * @return \FreeFW\Core\Model
     */
    public static function getNew($p_fields = null)
    {
        $cls = get_called_class();
        $cls = rtrim(ltrim($cls, '\\'), '\\');
        $obj = \FreeFW\DI\DI::get(str_replace('\\', '::', $cls));
        // @todo
        return $obj;
    }

    /**
     * Serialize
     *
     * @return string
     */
    public function __toString()
    {
        return @serialize($this);
    }

    public function unset()
    {
        $serializable = get_object_vars($this);
        unset($serializable['strategy']);
        unset($serializable['logger']);
        unset($serializable['config']);
        unset($serializable['app_config']);
        unset($serializable['updated']);
        return $serializable;
    }

    /**
     *
     * {@inheritDoc}
     * @see \Serializable::serialize()
     */
    public function serialize()
    {
        $serializable = $this->unset();
        return serialize($serializable);
    }

    /**
     *
     * {@inheritDoc}
     * @see \Serializable::serialize()
     */
    public function toHistory()
    {
        $serializable = get_object_vars($this);
        foreach ($serializable as $key => $value) {
            if (is_array($value) || is_object($value)) {
                unset($serializable[$key]);
            }
        }
        return base64_encode(serialize($serializable));
    }

    /**
     *
     * {@inheritDoc}
     * @see \Serializable::unserialize()
     */
    public function unserialize($data)
    {
        $unserialized = unserialize($data);
        if (is_array($unserialized) === true) {
            foreach ($unserialized as $property => $value) {
                $this->{$property} = $value;
            }
        }
    }

    /**
     * Return object properties
     *
     * @return array
     */
    public static function getProperties()
    {
        return [];
    }

    /**
     * Get call name
     *
     * @return string
     */
    public static function getClassName()
    {
        $clsName = str_replace('\\', '_', get_called_class());
        return str_replace('_Model_', '_', $clsName);
    }

    /**
     * Get Model description
     *
     * @return []
     */
    public static function getModelDescription()
    {
        $clsName = str_replace('\\', '_', get_called_class());
        $logName = str_replace('_Model_', '_', $clsName);
        $key     = 'FreeFW.' . $clsName . '.properties';
        /**
         * First, maybe in memory ??
         */
        if (self::$__cache && isset(self::$__cache[$key])) {
            return self::$__cache[$key];
        }
        /**
         * @var \Psr\Cache\CacheItemPoolInterface $cache
         */
        $cache = \FreeFW\DI\DI::getShared('cache');
        if ($cache && $cache->hasItem($key)) {
            $item = $cache->getItem($key);
            if ($item && $item->isHit()) {
                return $item->get();
            }
        }
        // Simple properties
        $description = [];
        $description['properties'] = static::getProperties();
        // Override
        $cfg      = \FreeFW\DI\DI::getShared('config');
        $cfgProps = $cfg->get('properties', []);
        if (isset($cfgProps[$logName])) {
            $description['properties'] = array_merge_recursive($description['properties'], $cfgProps[$logName]);
        }
        //
        foreach ($description['properties'] as $name => $oneProperty) {
            $dbField = $name;
            if (!isset($oneProperty[FFCST::PROPERTY_OPTIONS])) {
                $oneProperty[FFCST::PROPERTY_OPTIONS] = [];
            }
            if (isset($oneProperty[FFCST::PROPERTY_PRIVATE])) {
                $dbField = $oneProperty[FFCST::PROPERTY_PRIVATE];
            }
            $description['properties'][$name][FFCST::PROPERTY_GETTER] = 'get' . \FreeFW\Tools\PBXString::toCamelCase($dbField, true);
            $description['properties'][$name][FFCST::PROPERTY_SETTER] = 'set' . \FreeFW\Tools\PBXString::toCamelCase($dbField, true);
        }
        //
        if ($cache) {
            $item = new \FreeFW\Cache\Item($key);
            $item->set($description);
            $cache->save($item);
        }
        self::$__cache[$key] = $description;
        return $description;
    }

    /**
     * Get propreties from cache
     *
     * @return array
     */
    public static function getModelDescriptionProperties()
    {
        $description = self::getModelDescription();
        return $description['properties'];
    }

    /**
     * Get indexes
     *
     * @return array
     */
    public function getModelDescriptionIndexes()
    {
        $indexes = [];
        $clsName = str_replace('\\', '_', get_called_class());
        $logName = str_replace('_Model_', '_', $clsName);
        //
        if (method_exists($this, 'getUniqIndexes')) {
            $indexes = $this->getUniqIndexes();
        }
        // Override
        $cfg      = \FreeFW\DI\DI::getShared('config');
        $cfgProps = $cfg->get('indexes', []);
        if (isset($cfgProps[$logName])) {
            $indexes = array_merge_recursive($indexes, $cfgProps[$logName]);
        }
        return $indexes;
    }

    /**
     * Initialization
     *
     * return self
     */
    public function initModel()
    {
        $props = $this->getModelDescriptionProperties();
        $class = str_replace('\\Model\\', '_', get_class($this));

        $models   = $this->getAppConfig()->get('models');
        $class    = str_replace('\\Model\\', '_', get_class($this));
        $defaults = [];
        if (is_array($models) && isset($models[$class])) {
            if (isset($models[$class]['default'])) {
                $defaults = $models[$class]['default'];
            }
        }
        $cfg = $this->getAppConfig();
        foreach ($props as $name => $oneProperty) {
            $options = [];
            $pk      = false;
            if (isset($oneProperty[FFCST::PROPERTY_OPTIONS])) {
                $options = $oneProperty[FFCST::PROPERTY_OPTIONS];
                if (in_array(FFCST::OPTION_PK, $options)) {
                    $pk = true;
                }
            }
            $setter = $oneProperty[FFCST::PROPERTY_SETTER];
            $value  = null;
            if (isset($oneProperty[FFCST::PROPERTY_DEFAULT])) {
                $value = $oneProperty[FFCST::PROPERTY_DEFAULT];
                switch ($oneProperty[FFCST::PROPERTY_TYPE]) {
                    case FFCST::TYPE_BOOLEAN:
                        // boolean can't be null !
                        if ($value === FFCST::DEFAULT_TRUE) {
                            $this->$setter(true);
                        } else {
                            $this->$setter(false);
                        }
                        break;
                    case FFCST::TYPE_DATETIMETZ:
                    case FFCST::TYPE_DATETIME:
                        if ($value === FFCST::DEFAULT_NOW) {
                            $this->$setter(\FreeFW\Tools\Date::getCurrentTimestamp());
                        }
                        break;
                    case FFCST::TYPE_INTEGER:
                    case FFCST::TYPE_BIGINT:
                        if ($value === FFCST::DEFAULT_CURRENT_USER) {
                            $sso  = \FreeFW\DI\DI::getShared('sso');
                            if ($sso) {
                                $user = $sso->getUser();
                                if ($user) {
                                    $this->$setter($user->getUserId());
                                    foreach ($oneProperty[FFCST::PROPERTY_FK] as $relName => $rel) {
                                        $setter2 = 'set' . \FreeFW\Tools\PBXString::toCamelCase($relName, true);
                                        $this->$setter2($user);
                                        break;
                                    }
                                }
                            }
                        } else {
                            if ($value === FFCST::DEFAULT_CURRENT_GROUP) {
                                $sso  = \FreeFW\DI\DI::getShared('sso');
                                if ($sso) {
                                    $group = null;
                                    $user = $sso->getUser();
                                    if ($user) {
                                        $group = $sso->getUserGroup();
                                    }
                                    if ($group === null) {
                                        $group = $sso->getBrokerGroup();
                                    }
                                    if ($group) {
                                        $this->$setter($group->getGrpId());
                                        foreach ($oneProperty[FFCST::PROPERTY_FK] as $relName => $rel) {
                                            $setter3 = 'set' . \FreeFW\Tools\PBXString::toCamelCase($relName, true);
                                            $this->$setter3($group);
                                            break;
                                        }
                                    }
                                }
                            } else {
                                if ($value === FFCST::DEFAULT_LANG) {
                                    $langId = $cfg->get('default:lang_id', 0);
                                    if ($langId > 0) {
                                        $langModel = \FreeFW\Model\Lang::findFirst(['lang_id' => $langId]);
                                        if ($langModel) {
                                            $this->$setter($langModel->getLangId());
                                            foreach ($oneProperty[FFCST::PROPERTY_FK] as $relName => $rel) {
                                                $setter4 = 'set' . \FreeFW\Tools\PBXString::toCamelCase($relName, true);
                                                $this->$setter4($langModel);
                                                break;
                                            }
                                        }
                                    }
                                } else {
                                    if ($value === FFCST::DEFAULT_COUNTRY) {
                                        $cntyId = $cfg->get('default:cnty_id', 0);
                                        if ($cntyId > 0) {
                                            $cntyModel = \FreeFW\Model\Country::findFirst(['cnty_id' => $cntyId]);
                                            if ($cntyModel) {
                                                $this->$setter($cntyModel->getCntyId());
                                                foreach ($oneProperty[FFCST::PROPERTY_FK] as $relName => $rel) {
                                                    $setter5 = 'set' . \FreeFW\Tools\PBXString::toCamelCase($relName, true);
                                                    $this->$setter5($cntyModel);
                                                    break;
                                                }
                                            }
                                        }
                                    } else {
                                        if ($value === FFCST::DEFAULT_CURRENT_YEAR) {
                                            $this->$setter(date('Y'));
                                        } else {
                                            $this->$setter($value);
                                        }
                                    }
                                }
                            }
                        }
                        break;
                    default:
                        $this->$setter($value);
                        break;
                }
            } else {
                if ($pk) {
                    $this->$setter(0);
                } else {
                    $def = null;
                    if (isset($defaults[$name])) {
                        $def = $defaults[$name];
                    }
                    if ($def !== null) {
                        $this->$setter($def);
                    }
                }
            }
        }
        return $this;
    }

    /**
     * Validate model
     *
     * @return void
     */
    protected function validate()
    {
        $class    = str_replace('\\Model\\', '_', get_class($this));
        $props = $this->getModelDescriptionProperties();
        foreach ($props as $name => $oneProperty) {
            $options = [];
            $getter  = $oneProperty[FFCST::PROPERTY_GETTER];
            $setter  = $oneProperty[FFCST::PROPERTY_SETTER];
            $value   = $this->$getter();
            $public  = $name;
            if (isset($oneProperty[FFCST::PROPERTY_OPTIONS])) {
                $options = $oneProperty[FFCST::PROPERTY_OPTIONS];
            }
            if (isset($oneProperty[FFCST::PROPERTY_ENUM])) {
                if (is_array($oneProperty[FFCST::PROPERTY_ENUM])) {
                    if (!in_array($value, $oneProperty[FFCST::PROPERTY_ENUM])) {
                        $this->addError(
                            FFCST::ERROR_VALUES,
                            sprintf('%s field is not in allowed values !', $public),
                            \FreeFW\Core\Error::TYPE_PRECONDITION,
                            $public
                        );
                    }
                }
            }
            if (isset($oneProperty[FFCST::PROPERTY_MAX])) {
                if (strlen($value) > $oneProperty[FFCST::PROPERTY_MAX]) {
                    $this->addError(
                        FFCST::ERROR_MAXLENGTH,
                        sprintf('%s field is too long !', $public),
                        \FreeFW\Core\Error::TYPE_PRECONDITION,
                        $public
                    );
                }
            }
            if (in_array(FFCST::OPTION_REQUIRED, $options) &&
                !in_array(FFCST::OPTION_PK, $options) &&
                !in_array(FFCST::OPTION_BROKER, $options) &&
                !in_array(FFCST::OPTION_USER, $options) &&
                !in_array(FFCST::OPTION_GROUP, $options)) {
                if (isset($oneProperty[FFCST::PROPERTY_PUBLIC])) {
                    $public = $oneProperty[FFCST::PROPERTY_PUBLIC];
                }
                if (in_array(FFCST::OPTION_FK, $options)) {
                    if ($value <= 0 || $value === null || (is_string($value) && $value == '')) {
                        foreach ($oneProperty[FFCST::PROPERTY_FK] as $name => $rel) {
                            $public = $name;
                        }
                        $this->addError(
                            FFCST::ERROR_REQUIRED,
                            sprintf('%s relation is required !', $name),
                            \FreeFW\Core\Error::TYPE_PRECONDITION,
                            $public
                        );
                    }
                } else {
                    if ($value === null || (is_string($value) && $value == '')) {
                        $this->addError(
                            FFCST::ERROR_REQUIRED,
                            sprintf('%s field is required !', $public),
                            \FreeFW\Core\Error::TYPE_PRECONDITION,
                            $public
                        );
                    }
                }
            }
        }
        return $this;
    }

    /**
     * Clone current object
     *
     * @return \FreeFW\Core\Model
     */
    public function clone()
    {
        $class = get_called_class();
        $new   = new $class();
        foreach ($this->getModelDescriptionProperties() as $name => $property) {
            $getter = 'get' . \FreeFW\Tools\PBXString::toCamelCase($name, true);
            $setter = 'set' . \FreeFW\Tools\PBXString::toCamelCase($name, true);
            if (method_exists($new, $setter)) {
                $new->$setter($this->$getter());
            }
        }
        return $new;
    }

    /**
     * Add to queue ?
     *
     * @return boolean
     */
    public function forwardStorageEvent()
    {
        return false;
    }

    /**
     * get all simple fields as StdClass
     *
     * @return array
     */
    public function getFieldsAsArray(array $p_orig = [], $p_keep_binary = true)
    {
        $data = $p_orig;
        foreach ($this->getModelDescriptionProperties() as $name => $oneProperty) {
            $getter  = 'get' . \FreeFW\Tools\PBXString::toCamelCase($name, true);
            $content = $this->{$getter}();
            if (isset($oneProperty[FFCST::PROPERTY_TYPE])) {
                switch ($oneProperty[FFCST::PROPERTY_TYPE]) {
                    case FFCST::TYPE_HTML:
                        $content = \FreeFW\Tools\PBXString::htmlToText($content);
                        $data[$name . '_sm'] = \FreeFW\Tools\PBXString::truncString($content, 256);
                        $data[$name . '_md'] = \FreeFW\Tools\PBXString::truncString($content, 512);
                        $data[$name . '_lg'] = \FreeFW\Tools\PBXString::truncString($content, 768);
                        $data[$name . '_xl'] = \FreeFW\Tools\PBXString::truncString($content, 1204);
                        break;
                    case FFCST::TYPE_DATE:
                    case FFCST::TYPE_DATETIME:
                    case FFCST::TYPE_DATETIMETZ:
                        $content_hm  = '';
                        $content_hms = '';
                        if ($content != '') {
                            $dth = \FreeFW\Tools\Date::mysqlToDatetime($content, false, false);
                            $content     = $dth->format('d/m/Y');
                            $content_hm  = $dth->format('d/m/Y h:m');
                            $content_hms = $dth->format('d/m/Y h:m:s');
                        }
                        $data[$name . '_hm']  = $content_hm;
                        $data[$name . '_hms'] = $content_hms;
                        break;
                    case FFCST::TYPE_RESULTSET:
                    case FFCST::TYPE_BLOB:
                        if (!$p_keep_binary) {
                            continue 2;
                        }
                }
            }
            $data[$name] = $content;
        }
        return $data;
    }

    /**
     * get datas for merging, print, ...
     *
     * @return \FreeFW\Model\MergeModel
     */
    public function getMergeData($p_includes = [], $p_prefix = '', $p_parent = '', $p_check_merge = false)
    {
        $config = $this->getAppConfig();
        if ($p_includes === false) {
            $p_includes = [];
        } else {
            if ($p_includes === true || $p_includes === []) {
                if (method_exists($this, 'getDefaultMergeIncludes')) {
                    $p_includes = $this->getDefaultMergeIncludes();
                } else {
                    $p_includes = [];
                }
            }
        }
        $merge  = true;
        if ($p_check_merge) {
            if ($p_prefix !== '') {
                $merge  = $config->get('models:' . $this->getApiType() . ':merge:include', true);
            } else {
                $merge  = $config->get('models:' . $this->getApiType() . ':merge:main', true);
            }
        }
        $datas = new \FreeFW\Model\MergeModel();
        $block = $this->getApiType();
        $parts = explode('_', $block);
        array_shift($parts);
        $block = \FreeFW\Tools\PBXString::fromCamelCase(implode('_', $parts));
        if ($p_prefix != '') {
            $block = $p_prefix;
        }
        $datas->addBlock($block);
        $data = $this->getFieldsAsArray([]);
        foreach ($this->getProperties() as $name => $oneProperty) {
            $title = $oneProperty[FFCST::PROPERTY_PRIVATE];
            if (isset($oneProperty[FFCST::PROPERTY_PUBLIC])) {
                $title = $oneProperty[FFCST::PROPERTY_PUBLIC];
            }
            if (isset($oneProperty[FFCST::PROPERTY_TITLE])) {
                $title = $oneProperty[FFCST::PROPERTY_TITLE];
            }
            if (isset($oneProperty[FFCST::PROPERTY_MERGE])) {
                $title = $oneProperty[FFCST::PROPERTY_MERGE];
            }
            if ($merge === true || in_array($name, $merge)) {
                $datas->addField($name, $title, $oneProperty[FFCST::PROPERTY_TYPE]);
            }
            if (isset($oneProperty[FFCST::PROPERTY_OPTIONS])) {
                if (in_array(FFCST::OPTION_FK, $oneProperty[FFCST::PROPERTY_OPTIONS])) {
                    $relName = '';
                    foreach ($oneProperty[FFCST::PROPERTY_FK] as $relName => $relDatas) {
                        if ($p_includes === true || in_array($relName, $p_includes)) {
                            $getter = 'get' . \FreeFW\Tools\PBXString::toCamelCase($relName, true);
                            $relModel = $this->{$getter}();
                            if ($relModel instanceOf \FreeFW\Core\Model) {
                                if (is_array($p_includes)) {
                                    $newIncludes = false;
                                    foreach ($p_includes as $newOne) {
                                        if (strpos($newOne, $relName . '.') === 0) {
                                            if (!is_array($newIncludes)) {
                                                $newIncludes = [];
                                            }
                                            $newIncludes[] = str_replace($relName . '.', '', $newOne);
                                        }
                                    }
                                } else {
                                    $newIncludes = $p_includes;
                                }
                                $relDatas = $relModel->getMergeData($newIncludes, $block . '_' . $relName, $p_parent, $p_check_merge);
                                foreach ($relDatas->getBlocks() as $oneBlock) {
                                    $datas->addBlock($oneBlock);
                                    $datas->addData($relDatas->getDatas($oneBlock), $oneBlock);
                                    $datas->addFields($relDatas->getFields(), $relDatas->getTitles(), $relDatas->getTypes(), $oneBlock);
                                }
                            }
                        }
                    }
                }
            }
        }
        if (method_exists($this, 'getRelationships')) {
            foreach ($this->getRelationships() as $relName => $relOptions) {
                if ($p_includes === true || in_array($relName, $p_includes)) {
                    $getter = 'get' . \FreeFW\Tools\PBXString::toCamelCase($relName, true);
                    if (method_exists($this, $getter)) {
                        $relDatas = $this->{$getter}();
                        $newDatas = [];
                        if ($relDatas instanceof \FreeFW\Model\ResultSet) {
                            foreach ($relDatas as $relData) {
                                $newDatas = $relData->getMergeData($p_includes, $block . '_' . $relName, $p_parent, $p_check_merge);
                                foreach ($newDatas->getBlocks() as $oneBlock) {
                                    $datas->addBlock($oneBlock, true);
                                    $datas->addData($newDatas->getDatas($oneBlock), $oneBlock, false);
                                }
                            }
                        }
                    }
                }
            }
        }
        $specific = [];
        if (method_exists($this, 'getSpecificEditionFields')) {
            $specific = $this->getSpecificEditionFields();
            foreach ($specific as $specField) {
                if ($merge === true || in_array($specField['name'], $merge)) {
                    $datas->addField($specField['name'], $specField['title'], $specField['type']);
                    $data[$specField['name']] = $specField['content'];
                } 
            }
        }
        $datas->addData($data, $block);
        if (method_exists($this, 'beforeMerge')) {
            $datas = $this->beforeMerge($datas, $block);
        }
        return $datas;
    }

    /**
     * Export one as in sheet
     * 
     * @param \FreeOffice\Model\Spreesheet $p_sheet
     * @param array                        $p_includes
     * 
     * @return boolean
     */
    public function exportAsSheet($p_sheet, $p_includes = [])
    {
        $p_sheet->addLine($this->getMergeData($p_includes, '' , '', true));
        return true;
    }
}
