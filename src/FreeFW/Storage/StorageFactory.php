<?php
namespace FreeFW\Storage;

/**
 *
 * @author jeromeklam
 *
 */
class StorageFactory
{

    /**
     * Get new StorageInterface
     *
     * @param string $p_dsn
     * @param string $p_key1
     * @param string $p_key2
     *
     * @return \FreeFW\Interfaces\StorageInterface
     */
    public static function getFactory(string $p_dsn, $p_key1 = null, $p_key2 = null)
    {
        $parts   = explode(':', $p_dsn);
        $storage = null;
        switch (strtolower($parts[0])) {
            case 'mysql':
                $provider = new \FreeFW\Storage\PDO\Mysql($p_dsn, $p_key1, $p_key2);
                $storage  = new \FreeFW\Storage\PDOStorage($provider);
                break;
            case 'oci':
                $provider = new \FreeFW\Storage\PDO\Oracle($p_dsn, $p_key1, $p_key2);
                $storage  = new \FreeFW\Storage\PDOStorage($provider);
                break;
            default:
                throw new \FreeFW\Core\FreeFWStorageException(sprintf('Unknown %s provider !', $p_dsn));
                break;
        }
        return $storage;
    }
}
