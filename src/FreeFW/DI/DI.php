<?php
namespace FreeFW\DI;

/**
 * Dependecy Injector container
 *
 * @author jeromeklam
 */
class DI
{

    /**
     * All DI
     * @var array
     */
    protected static $containers = [];

    /**
     * Shared object
     * @var array
     */
    protected static $shared = [];

    /**
     * Add new DI
     *
     * @param string                                         $p_ns
     * @param \FreeFW\Interfaces\DependencyInjectorInterface $p_di
     *
     * @return void
     */
    public static function add(string $p_ns, \FreeFW\Interfaces\DependencyInjectorInterface $p_di)
    {
        self::$containers[$p_ns] = $p_di;
    }

    /**
     * Get object
     *
     * @param string $p_object
     *
     * @return \FreeFW\Interfaces\DependencyInjectorInterface
     */
    public static function get(string $p_object)
    {
        $parts = explode('::', $p_object);
        if (is_array($parts) && count($parts) == 3) {
            if (array_key_exists($parts[0], self::$containers)) {
                $di  = self::$containers[$parts[0]];
                $fct = 'get' . ucfirst($parts[1]);
                return $di->$fct($parts[2]);
            }
        }
        throw new \FreeFW\Core\FreeFWException(sprintf('DI : Nothing to handle %s', $p_object));
    }

    /**
     * Set a new shared object
     *
     * @param string $p_name
     * @param mixed  $p_shared
     *
     * @return \FreeFW\DI\DI
     */
    public static function setShared(string $p_name, $p_shared)
    {
        self::$shared[$p_name] = $p_shared;
    }

    /**
     * Get shared object
     *
     * @param string $p_name
     *
     * @return boolean
     */
    public static function getShared(string $p_name)
    {
        if (array_key_exists($p_name, self::$shared)) {
            return self::$shared[$p_name];
        }
        return false;
    }
}
