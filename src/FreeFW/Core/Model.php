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
    const MODEL_BEHAVIOUR_API      = 'API';

    /**
     * Behaviour
     */
    use \FreeFW\Behaviour\ValidatorTrait;
    use \FreeFW\Behaviour\ConfigAwareTrait;
    use \Psr\Log\LoggerAwareTrait;

    /**
     * Model behaviour
     * @var string
     */
    protected $model_behaviour = self::MODEL_BEHAVIOUR_STANDARD;

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
    }

    /**
     * Initialisation
     */
    public function init()
    {
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
        $props = $this->getProperties();
        $this->initModel();
        foreach ($p_datas as $name => $value) {
            foreach ($props as $prp => $property) {
                $test = $prp;
                if (array_key_exists(FFCST::PROPERTY_PUBLIC, $property)) {
                    $test = $property[FFCST::PROPERTY_PUBLIC];
                }
                if ($test == $name) {
                    $type   = $property[FFCST::PROPERTY_TYPE];
                    $setter = 'set' . \FreeFW\Tools\PBXString::toCamelCase($prp, true);
                    switch ($type) {
                        case FFCST::TYPE_BLOB:
                            $this->$setter($this->decode_chunk($value));
                            break;
                        default:
                            $this->$setter($value);
                            break;
                    }
                    break;
                }
            }
        }
        foreach ($p_relations as $name => $relation) {
            if ($relation['type'] == \FreeFW\JsonApi\V1\Model\RelationshipObject::ONE_TO_ONE) {
                $foundRel = false;
                foreach ($props as $prp => $property) {
                    $test = $prp;
                    if (array_key_exists(FFCST::PROPERTY_PUBLIC, $property)) {
                        $test = $property[FFCST::PROPERTY_PUBLIC];
                    }
                    if (array_key_exists(FFCST::PROPERTY_FK, $property)) {
                        $fks = $property[FFCST::PROPERTY_FK];
                        if (array_key_exists($relation['name'], $fks)) {
                            $fk = $fks[$relation['name']];
                            // Complete empty object
                            $id = 0;
                            foreach ($relation['values'] as $val) {
                                $id = $val;
                                break;
                            }
                            $setter = 'set' . \FreeFW\Tools\PBXString::toCamelCase($relation['name'], true);
                            $class  = '\\' . str_replace('::', '\\', $fk['model']);
                            $found  = false;
                            foreach ($p_included as $oneIncluded) {
                                if ($oneIncluded instanceof $class && $oneIncluded->getApiId() == $id) {
                                    $found = true;
                                    $this->$setter($oneIncluded);
                                    break;
                                }
                            }
                            if (!$found) {
                                $rel = \FreeFW\DI\DI::get($fk['model']);
                                $rel->setApiId($id);
                                $this->$setter($rel);
                            }
                            // property
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
                    if (array_key_exists($name, $mRels)) {
                        $rels   = [];
                        $setter = 'set' . \FreeFW\Tools\PBXString::toCamelCase($name, true);
                        foreach ($relation['values'] as $val) {
                            $found = false;
                            $class = '\\' . str_replace('::', '\\', $mRels[$name]['model']);
                            $found = false;
                            foreach ($p_included as $oneIncluded) {
                                if ($oneIncluded instanceof $class && $oneIncluded->getApiId() == $val) {
                                    $found  = true;
                                    $rels[] = $oneIncluded;
                                    break;
                                }
                            }
                            if (!$found) {
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
     * {@inheritDoc}
     * @see \FreeFW\Interfaces\ApiResponseInterface::getFieldName()
     */
    public function getFieldName(string $p_field, $p_option=FFCST::PROPERTY_PUBLIC) : string
    {
        $props = $this->getProperties();
        //
        switch ($p_option) {
            case FFCST::PROPERTY_PUBLIC:
                if (array_key_exists($p_field, $props)) {
                    if (array_key_exists(FFCST::PROPERTY_PUBLIC, $props[$p_field])) {
                        return $props[$p_field][FFCST::PROPERTY_PUBLIC];
                    }
                }
                break;

            case FFCST::PROPERTY_PRIVATE:
                foreach ($this->getProperties() as $name => $property) {
                    if (array_key_exists(FFCST::PROPERTY_PUBLIC, $property)) {
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
        unset($serializable['strategy']);
        unset($serializable['logger']);
        unset($serializable['config']);
        unset($serializable['app_config']);
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
    public function initModel()
    {
        $props = $this->getProperties();
        $class = str_replace('\\Model\\', '_', get_class($this));
        $cfg   = $this->getAppConfig();
        foreach ($props as $name => $oneProperty) {
            $options = [];
            $pk      = false;
            if (array_key_exists(FFCST::PROPERTY_OPTIONS, $oneProperty)) {
                $options = $oneProperty[FFCST::PROPERTY_OPTIONS];
                if (in_array(FFCST::OPTION_PK, $options)) {
                    $pk = true;
                }
            }
            $setter = 'set' . \FreeFW\Tools\PBXString::toCamelCase($name, true);
            $value  = null;
            if (array_key_exists(FFCST::PROPERTY_DEFAULT, $oneProperty)) {
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
                                    $group = $sso->getBrokerGroup();
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
                    $def = $cfg->get('default:' . $class . ':' . $name, null);
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
        $props = $this->getProperties();
        foreach ($props as $name => $oneProperty) {
            $options = [];
            $getter  = 'get' . \FreeFW\Tools\PBXString::toCamelCase($name, true);
            $value   = $this->$getter();
            $public  = $name;
            if (array_key_exists(FFCST::PROPERTY_OPTIONS, $oneProperty)) {
                $options = $oneProperty[FFCST::PROPERTY_OPTIONS];
            }
            if (array_key_exists(FFCST::PROPERTY_ENUM, $oneProperty)) {
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
            if (array_key_exists(FFCST::PROPERTY_MAX, $oneProperty)) {
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
                if (array_key_exists(FFCST::PROPERTY_PUBLIC, $oneProperty)) {
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
        foreach ($this->getProperties() as $name => $property) {
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
}
