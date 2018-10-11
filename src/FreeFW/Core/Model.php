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
                if (array_key_exists('jsonname', $property)) {
                    $test = $property['jsonname'];
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
            if (!in_array(FFCST::OPTION_PK, $property['options']) &&
                !in_array(FFCST::OPTION_JSONIGNORE, $property['options'])) {
                $getter = 'get' . \FreeFW\Tools\PBXString::toCamelCase($name, true);
                if (array_key_exists('jsonname', $property)) {
                    $attributes[$property['jsonname']] = $this->$getter();
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
    public static function getNew(array $p_fields = [])
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
     * Init object
     */
    abstract public function init();

    /**
     * Return object properties
     *
     * @return array
     */
    public static function getProperties()
    {
        $props = get_class_vars(get_called_class());
        var_dump($props);
        die('getProperties');
    }
}
