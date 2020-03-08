<?php
namespace FreeFW\Core;

use \FreeFW\Constants as FFCST;

/**
 * Standard model
 *
 * @author jeromeklam
 */
abstract class Model implements
    \FreeFW\Interfaces\ApiResponseInterface,
    \FreeFW\Interfaces\ValidatorInterface,
    \Serializable
{

    /**
     * Behaviour
     */
    use \FreeFW\Behaviour\ValidatorTrait;

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
                throw new \FreeFW\Core\FreeFWMemberAccessException(
                    'Property ' . $property . ' doesn\'t exists !'
                );
            }
            switch ($matches[1]) {
                case 'set':
                    return $this->set($property, $p_args[0]);
                case 'get':
                    return $this->get($property);
                default:
                    throw new \FreeFW\Core\FreeFWMemberAccessException(
                        'Method ' . $p_methodName . ' doesn\'t exists !'
                    );
            }
        } else {
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
        return $this;
    }

    /**
     * Init object with datas
     *
     * @param array $p_datas
     *
     * @return \FreeFW\Core\Model
     */
    public function initWithJson(array $p_datas = [], array $p_relations = [])
    {
        $props = $this->getProperties();
        $this->init();
        foreach ($p_datas as $name => $value) {
            foreach ($props as $prp => $property) {
                $test = $prp;
                if (array_key_exists(FFCST::PROPERTY_PUBLIC, $property)) {
                    $test = $property[FFCST::PROPERTY_PUBLIC];
                }
                if ($test == $name) {
                    $setter = 'set' . \FreeFW\Tools\PBXString::toCamelCase($name, true);
                    $this->$setter($value);
                    break;
                }
            }
        }
        foreach ($p_relations as $name => $relation) {
            foreach ($props as $prp => $property) {
                $test = $prp;
                if (array_key_exists(FFCST::PROPERTY_PUBLIC, $property)) {
                    $test = $property[FFCST::PROPERTY_PUBLIC];
                }
                if (array_key_exists(FFCST::PROPERTY_FK, $property)) {
                    $fks = $property[FFCST::PROPERTY_FK];
                    if (array_key_exists($relation['name'], $fks)) {
                        $fk     = $fks[$relation['name']];
                        // Complete empty object
                        $rel    = \FreeFW\DI\DI::get($fk['model']); 
                        $setter = 'set' . \FreeFW\Tools\PBXString::toCamelCase($relation['name'], true);
                        foreach ($relation['values'] as $val) {
                            $rel->setApiId($val);
                        }
                        $this->$setter($rel);
                        // property
                        $setter = 'set' . \FreeFW\Tools\PBXString::toCamelCase($test, true);
                        foreach ($relation['values'] as $val) {
                            $this->$setter($val);
                        }
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
        foreach ($this->getProperties() as $name => $property) {
            if (array_key_exists(FFCST::PROPERTY_OPTIONS, $property)) {
                if (in_array($p_option, $property[FFCST::PROPERTY_OPTIONS])) {
                   return $name;
                }
            }
        }
        return '';
    }

    /**
     *
     * @see \FreeFW\Interfaces\ApiResponseInterface
     */
    public function getApiId() : string
    {
        foreach ($this->getProperties() as $name => $property) {
            if (array_key_exists(FFCST::PROPERTY_OPTIONS, $property)) {
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
        foreach ($this->getProperties() as $name => $property) {
            if (array_key_exists(FFCST::PROPERTY_OPTIONS, $property)) {
                if (in_array(FFCST::OPTION_PK, $property[FFCST::PROPERTY_OPTIONS])) {
                    $setter = 'set' . \FreeFW\Tools\PBXString::toCamelCase($name, true);
                    return $this->$setter($p_id);
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
        foreach ($this->getProperties() as $name => $property) {
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
        foreach ($this->getProperties() as $name => $property) {
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
        foreach ($this->getProperties() as $name => $property) {
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
        foreach ($this->getProperties() as $name => $property) {
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
        foreach ($this->getProperties() as $name => $property) {
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
        foreach ($this->getProperties() as $name => $property) {
            $oneAttribute = new \FreeFW\JsonApi\V1\Model\AttributeObject($name);
            $getter       = 'get' . \FreeFW\Tools\PBXString::toCamelCase($name, true);
            $oneAttribute->setValue($this->$getter());
            if (array_key_exists(FFCST::PROPERTY_PUBLIC, $property)) {
                $oneAttribute->setJsonName($property[FFCST::PROPERTY_PUBLIC]);
            }
            if (array_key_exists(FFCST::PROPERTY_OPTIONS, $property)) {
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
        /**
         * One to One, an attribute is the Foreign Key
         */
        foreach ($this->getProperties() as $name => $property) {
            if (array_key_exists(FFCST::PROPERTY_FK, $property)) {
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

    /**
     *
     * {@inheritDoc}
     * @see \Serializable::serialize()
     */
    public function serialize()
    {
        $serializable = get_object_vars($this);
        return serialize($serializable);
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
     * Initialization
     *
     * return self
     */
    public function init()
    {
        $props = $this->getProperties();
        foreach ($props as $name => $oneProperty) {
            $options = [];
            $pk      = false;
            if (array_key_exists(FFCST::PROPERTY_OPTIONS, $oneProperty)) {
                $options = $oneProperty[FFCST::PROPERTY_OPTIONS];
                if (in_array(FFCST::OPTION_PK, $options)) {
                    $pk = true;
                }
            }
            $value = null;
            if (array_key_exists(FFCST::PROPERTY_DEFAULT, $oneProperty)) {
                $value = $oneProperty[FFCST::PROPERTY_DEFAULT];
                $setter = 'set' . \FreeFW\Tools\PBXString::toCamelCase($name, true);
                switch ($oneProperty[FFCST::PROPERTY_TYPE]) {
                    case FFCST::TYPE_BOOLEAN:
                        // boolean can't be null !
                        if ($value == FFCST::DEFAULT_TRUE) {
                            $this->$setter(1);
                        } else {
                            $this->$setter(0);
                        }
                        break;
                    case FFCST::TYPE_DATETIME:
                        if ($value == FFCST::DEFAULT_NOW) {
                            $this->$setter(\FreeFW\Tools\Date::getCurrentTimestamp());
                        }
                        break;
                    default:
                        $this->$setter($value);
                        break;
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
        $props = $this->getProperties();
        foreach ($props as $name => $oneProperty) {
            $options = [];
            if (array_key_exists(FFCST::PROPERTY_OPTIONS, $oneProperty)) {
                $options = $oneProperty[FFCST::PROPERTY_OPTIONS];
            }
            if (in_array(FFCST::OPTION_REQUIRED, $options) && !in_array(FFCST::OPTION_PK, $options)) {
                $getter = 'get' . \FreeFW\Tools\PBXString::toCamelCase($name, true);
                $value  = $this->$getter();
                $public = $name;
                if (array_key_exists(FFCST::PROPERTY_PUBLIC, $oneProperty)) {
                    $public = $oneProperty[FFCST::PROPERTY_PUBLIC];
                }
                if ($value === null || (is_string($value) && $value == '')) {
                    $this->addError(
                        FFCST::ERROR_REQUIRED,
                        sprintf('%s field is required !', $public),
                        \FreeFW\Core\Error::TYPE_PRECONDITION,
                        $public
                    );
                } else {
                    if (in_array(FFCST::OPTION_FK, $options)) {
                        if ($value <= 0) {
                            $this->addError(
                                FFCST::ERROR_REQUIRED,
                                sprintf('%s relation is required !', $public),
                                \FreeFW\Core\Error::TYPE_PRECONDITION,
                                $public
                            );
                        }
                    }
                }
            }
        }
        return $this;
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
}
