<?php
/**
 * Helper pour les logs
 *
 * @author jeromeklam
 * @package Logger
 */
namespace FreeFW\Behaviour;

use Psr\Log\NullLogger;
use \FreeFW\ResourceDi as Singleton;

/**
 * @implements \Psr\Log\LoggerAware
 * @author jeromeklam
 */
trait LoggerAwareTrait
{

    /**
     * Is debug level
     */
    protected static $level_debug = null;

    /**
     * Affectation d'un logger
     *
     * @param \Psr\Log\LoggerInterface $logger
     *
     * @return null
     */
    protected static function setLogger(\Psr\Log\LoggerInterface $logger)
    {
        Singleton::getInstance()->setShared('logger', $logger);
    }

    /**
     * Retourne le logger
     *
     * @return \Psr\Log\LoggerInterface
     */
    protected static function getLogger()
    {
        $logger = Singleton::getInstance()->getShared('logger');
        if ($logger === null || $logger === false) {
            $logger = new NullLogger();
        }
        return $logger;
    }

    /**
     * Optimisation des appels debugs gourmands...
     *
     * @return boolean
     */
    public static function isDebugLevel()
    {
        if (self::$level_debug === null) {
            $logger = self::getLogger();
            self::$level_debug = ($logger->getLevel() >= \Psr\Log\LogLevel::DEBUG);
        }
        return self::$level_debug;
    }

    /**
     * Debug
     *
     * @param string $p_message
     * @param array  $p_context
     *
     * @return this
     */
    public static function debug($p_message, $p_context = array())
    {
        $logger = self::getLogger();
        $logger->debug($p_message, $p_context);
    }

    /**
     * Error
     *
     * @param string $p_message
     * @param array  $p_context
     *
     * @return this
     */
    public static function error($p_message, $p_context = array())
    {
        $logger = self::getLogger();
        $logger->error($p_message, $p_context);
    }

    /**
     * Info
     *
     * @param string $p_message
     * @param array  $p_context
     *
     * @return this
     */
    public static function info($p_message, $p_context = array())
    {
        $logger = self::getLogger();
        $logger->info($p_message, $p_context);
    }

    /**
     * Warning
     *
     * @param string $p_message
     * @param array  $p_context
     *
     * @return this
     */
    public static function warning($p_message, $p_context = array())
    {
        $logger = self::getLogger();
        $logger->warning($p_message, $p_context);
    }
}
