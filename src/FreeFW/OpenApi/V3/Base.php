<?php
namespace FreeFW\OpenApi\V3;

/**
 * OpenApi v3 Base object
 *
 * @author jeromeklam
 */
class Base implements \FreeFW\Interfaces\ConfigAwareTraitInterface, \Psr\Log\LoggerAwareInterface, \JsonSerializable
{

    /**
     * comportements
     */
    use \Psr\Log\LoggerAwareTrait;
    use \FreeFW\Behaviour\ConfigAwareTrait;

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
        if (preg_match('~^(add|has|set|get)([A-Z])(.*)$~', $p_methodName, $matches)) {
            $property = \FreeFW\Tools\PBXString::fromCamelCase($matches[2] . $matches[3]);
            if (!property_exists($this, $property)) {
                if ($matches[1] == 'add' || $matches[1] == 'has') {
                    if (substr($property, -1) == 'y') {
                        $property = substr($property, 0, -1) . 'ies';
                    } else {
                        $property = $property . 's';
                    }
                    if (!property_exists($this, $property)) {
                        throw new \FreeFW\Core\FreeFWMemberAccessException(
                            'Property ' . $property . ' doesn\'t exists !'
                        );
                    }
                } else {
                    throw new \FreeFW\Core\FreeFWMemberAccessException(
                        'Property ' . $property . ' doesn\'t exists !'
                    );
                }
            }
            switch ($matches[1]) {
                case 'has':
                    if (array_key_exists($p_args[0], $this->$property)) {
                        return true;
                    }
                    return false;
                case 'add':
                    if (count($p_args) > 1) {
                        return $this->add($property, $p_args[1], $p_args[0]);
                    }
                    return $this->add($property, $p_args[0]);
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
     * Add a property
     *
     * @param string $p_property
     * @param mixed  $p_value
     * @param mixed  $p_index
     *
     * @return static
     */
    public function add($p_property, $p_value, $p_index = '')
    {
        if ($this->$p_property === null || !is_array($this->$p_property)) {
            $this->$p_property = [];
        }
        if ($p_index != '') {
            $this->$p_property[$p_index] = $p_value;
        } else {
            $this->$p_property = $p_value;
        }
        return $this;
    }

    /**
     * To array
     *
     * @return []
     */
    public function __toArray()
    {
        $serializable = get_object_vars($this);
        $arr = [];
        foreach ($serializable as $name => $oneProp) {
            if ($name !== 'logger' && $name !== config && $oneProp !== null) {
                if (strpos($name, '/') === false) {
                    $name = \FreeFW\Tools\PBXString::toCamelCase($name);
                    if ($name == 'ref') {
                        $name = '$ref';
                    }
                }
                if (is_object($oneProp)) {
                    if (method_exists($oneProp, '__toArray')) {
                        $arr[$name] = $oneProp->__toArray();
                    }
                } else {
                    $arr[$name] = $oneProp;
                }
            }
        }
        return $arr;
    }

    /**
     * @return string
     */
    public function jsonSerialize()
    {
        return $this->__toArray();
    }
}
