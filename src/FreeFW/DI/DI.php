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
     * Register new DI
     *
     * @param string                     $p_ns
     * @param \FreeFW\Application\Config $p_config
     * @param \Psr\Log\LoggerInterface   $p_logger
     *
     * @return \FreeFW\DI\DependencyInjector
     */
    public static function registerDI(
        string $p_ns,
        \FreeFW\Application\Config $p_config,
        \Psr\Log\LoggerInterface $p_logger
    ) {
        $di = \FreeFW\DI\DependencyInjector::getFactory($p_ns, $p_config, $p_logger);
        self::add($p_ns, $di);
        return $di;
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
                $obj = $di->$fct($parts[2]);
                if (method_exists($obj, 'setMainBroker')) {
                    $broker = \FreeFw\DI\DI::getShared('broker');
                    if ($broker) {
                        $obj->setMainBroker(intval($broker));
                    } else {
                        $sso = \FreeFW\DI\DI::getShared('sso');
                        if ($sso) {
                            $obj->setMainBroker($sso->getBrokerId());
                        } else {
                            $obj->setMainBroker(0);
                        }
                    }
                }
                return $obj;
            }
        } else {
            switch ($p_object) {
                case 'sso':
                    return self::getShared('sso');
                case 'emailMailer':
                    $mailer = self::getShared('emailMailer');
                    if (!$mailer) {
                        $mailer = \FreeFW\Message\SenderFactory::getDefaultEmailSender();
                        self::setShared('emailMailer', $mailer);
                    }
                    return $mailer;
                default:
                    $parts = explode('\\', ltrim($p_object, '\\'));
                    if (count($parts) > 1 && class_exists($p_object)) {
                        $di  = self::$containers[$parts[0]];
                        $obj = $di->getClass($p_object);
                        if (method_exists($obj, 'setMainBroker')) {
                            $broker = \FreeFw\DI\DI::getShared('broker');
                            if ($broker) {
                                $obj->setMainBroker(intval($broker));
                            } else {
                                $sso = \FreeFW\DI\DI::getShared('sso');
                                if ($sso) {
                                    $obj->setMainBroker($sso->getBrokerId());
                                } else {
                                    $obj->setMainBroker(0);
                                }
                            }
                        }
                        return $obj;
                    }
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

    /**
     * Get updaters
     *
     * @return [\FreeFW\Storage\AbstractUpdater]
     */
    public static function getUpdaters()
    {
        $updaters = [];
        /**
         * @var \FreeFW\DI\DependencyInjector $di
         */
        foreach (self::$containers as $name => $di) {
            $updater = $di->getUpdater($name);
            if ($updater) {
                $updaters[] = $updater;
            }
        }
        return $updaters;
    }
}
