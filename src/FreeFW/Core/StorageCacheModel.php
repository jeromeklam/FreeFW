<?php
namespace FreeFW\Core;

/**
 *
 * @author jeromeklam
 *
 */
abstract class StorageCacheModel extends \FreeFW\Core\StorageModel
{

    /**
     * Cache datas...
     * @var array
     */
    protected static $cache = [];

    /**
     *
     * {@inheritDoc}
     * @see \FreeFW\Interfaces\StorageStrategyInterface::findFirst()
     */
    public static function findFirst(array $p_filters = [], $p_force = false)
    {
        $cls = get_called_class();
        $cls = rtrim(ltrim($cls, '\\'), '\\');
        $key = md5($cls . '::' . json_encode($p_filters, true));
        if (!array_key_exists($key, self::$cache) || $p_force) {
            self::$cache[$key] = parent::findFirst($p_filters);
        }
        return self::$cache[$key];
    }
}
