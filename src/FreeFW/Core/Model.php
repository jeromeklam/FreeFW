<?php
namespace FreeFW\Core;

/**
 * Standard model
 *
 * @author jeromeklam
 */
abstract class Model
{

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
     * Init object
     */
    abstract public function init();
}
