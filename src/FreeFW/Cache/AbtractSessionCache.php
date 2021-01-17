<?php
namespace FreeFW\Cache;

use \Psr\Cache\CacheItemPoolInterface;
use \Psr\Cache\CacheItemInterface;
use \FreeFW\Cache\CacheException;

/**
 * Cache en session
 * @author klam
 */
abstract class AbtractSessionCache implements \Psr\Cache\CacheItemPoolInterface
{

    /**
     * Clef de cache
     * @var string
     */
    const CACHE_KEY = 'session-cache';

    /**
     * Cache
     * @var array
     */
    protected static $session_cache = false;

    /**
     * Eléments à enregistrer sur demande
     * @var array
     */
    protected static $deferred = array();

    /**
     * Lecture du cache
     *
     * @return void
     */
    protected function readSessionCache()
    {
        $session = \FreeFW\DI\DI::getShared('session');
        if ($session->has(self::CACHE_KEY)) {
            self::$session_cache = unserialize($session->get(self::CACHE_KEY));
        }
        if (! is_array(self::$session_cache)) {
            self::$session_cache = array();
        }
        return $this;
    }

    /**
     * Retourne le ache de session
     *
     * @return array
     */
    protected function getSessionCache()
    {
        if (!is_array(self::$session_cache)) {
            $this->readSessionCache();
        }
        return self::$session_cache;
    }

    /**
     * Sauvegarde du cache
     *
     * @param boolean $p_full
     *
     * @return \Static
     */
    protected function setSessionCache($p_full = false)
    {
        $session = \FreeFW\DI\DI::getShared('session');
        if ($p_full) {
            self::$session_cache = array_merge(self::$session_cache, self::$deferred);
        }
        $session->set(self::CACHE_KEY, serialize(self::$session_cache));
        return $this;
    }

    /**
     * @see \Psr\Cache\CacheItemPoolInterface
     */
    public function getItem($key)
    {
        $session = $this->getSessionCache();
        if ($session) {
            if (isset($session[$key])) {
                return $session[$key];
            } else {
                throw new CacheException(sprintf('Key %s not found !', $key));
            }
        } else {
            throw new CacheException('Error retrieving sesidon cache');
        }
    }

    /**
     * @see \Psr\Cache\CacheItemPoolInterface
     */
    public function getItems(array $keys = array())
    {
        $session = $this->getSessionCache();
        if ($session) {
            $result = array();
            foreach ($keys as $oneKey) {
                if (isset($session[$oneKey])) {
                    $result[$oneKey] = $session[$oneKey];
                } else {
                    throw new CacheException(sprintf('Key %s not found !', $key));
                }
            }
            return $result;
        } else {
            throw new CacheException('Error retrieving sesidon cache');
        }
    }

    /**
     * @see \Psr\Cache\CacheItemPoolInterface
     */
    public function hasItem($key)
    {
        $session = $this->getSessionCache();
        if ($session) {
            if (isset($session[$key])) {
                return true;
            }
        }
        return false;
    }

    /**
     * @see \Psr\Cache\CacheItemPoolInterface
     */
    public function clear()
    {
        self::$session_cache = array();
    }

    /**
     * @see \Psr\Cache\CacheItemPoolInterface
     */
    public function deleteItem($key)
    {
        $session = $this->getSessionCache();
        if ($session) {
            if (isset($session[$key])) {
                unset($session[$key]);
                return true;
            }
        }
        return false;
    }

    /**
     * @see \Psr\Cache\CacheItemPoolInterface
     */
    public function deleteItems(array $keys)
    {
        $session = $this->getSessionCache();
        if ($session) {
            foreach ($keys as $oneKey) {
                if (isset($session[$oneKey])) {
                    unset($session[$oneKey]);
                } else {
                    return false;
                }
            }
            return true;
        }
        return false;
    }

    /**
     * @see \Psr\Cache\CacheItemPoolInterface
     */
    public function save(\Psr\Cache\CacheItemInterface $item)
    {
        $this->readSessionCache();
        self::$session_cache[$item->getKey()] = $item;
        $this->setSessionCache();
        return true;
    }

    /**
     * @see \Psr\Cache\CacheItemPoolInterface
     */
    public function saveDeferred(CacheItemInterface $item)
    {
        self::$deferred[$item->getKey()] = $item;
        return true;
    }

    /**
     * @see \Psr\Cache\CacheItemPoolInterface
     */
    public function commit()
    {
        $this->readSessionCache();
        $this->setSessionCache(true);
        return true;
    }
}
