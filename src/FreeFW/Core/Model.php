<?php
namespace FreeFW\Core;

use \FreeFW\Constants as FFCST;

/**
 * Standard model
 *
 * @author jeromeklam
 */
abstract class Model implements
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
    public function initWithJson(array $p_datas = [])
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
        return $this;
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
            if (array_key_exists(FFCST::PROPERTY_OPTIONS, $property)) {
                if (!in_array(FFCST::OPTION_PK, $property[FFCST::PROPERTY_OPTIONS]) &&
                    !in_array(FFCST::OPTION_JSONIGNORE, $property[FFCST::PROPERTY_OPTIONS])) {
                    $getter = 'get' . \FreeFW\Tools\PBXString::toCamelCase($name, true);
                    if (array_key_exists(FFCST::PROPERTY_PUBLIC, $property)) {
                        $attributes[$property[FFCST::PROPERTY_PUBLIC]] = $this->$getter();
                    } else {
                        $attributes[$name] = $this->$getter();
                    }
                }
            } else {
                $getter = 'get' . \FreeFW\Tools\PBXString::toCamelCase($name, true);
                if (array_key_exists(FFCST::PROPERTY_PUBLIC, $property)) {
                    $attributes[$property[FFCST::PROPERTY_PUBLIC]] = $this->$getter();
                } else {
                    $attributes[$name] = $this->$getter();
                }
            }
        }
        return $attributes;
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
            if (in_array(FFCST::OPTION_REQUIRED, $options)) {
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
                }
            }
        }
        return $this;
    }
}
