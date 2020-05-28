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
    public static function getFactory(string $p_dsn, $p_key1 = null, $p_key2 = null, $p_logger = null, $p_event_manager = null)
    {
        $parts   = explode(':', $p_dsn);
        $storage = null;
        if (!$p_logger instanceof \Psr\Log\LoggerInterface) {
            throw new \InvalidArgumentException();
        }
        if (!$p_event_manager instanceof \FreeFW\Listener\EventManager) {
            throw new \InvalidArgumentException();
        }
        switch (strtolower($parts[0])) {
            case 'mysql':
                $provider = new \FreeFW\Storage\PDO\Mysql($p_dsn, $p_key1, $p_key2);
                $provider->setEventManager($p_event_manager);
                $storage  = new \FreeFW\Storage\PDOStorage($provider);
                $storage
                    ->setEventManager($p_event_manager)
                    ->setLogger($p_logger)
                ;
                break;
            case 'oci':
                $provider = new \FreeFW\Storage\PDO\Oracle($p_dsn, $p_key1, $p_key2);
                $provider->setEventManager($p_event_manager);
                $storage  = new \FreeFW\Storage\PDOStorage($provider);
                $storage
                    ->setEventManager($p_event_manager)
                    ->setLogger($p_logger)
                ;
                break;
            default:
                throw new \FreeFW\Core\FreeFWStorageException(sprintf('Unknown %s provider !', $p_dsn));
                break;
        }
        return $storage;
    }
}
