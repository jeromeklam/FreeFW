<?php
namespace FreeFW\Core;

/**
 * Base application
 *
 * @author jeromeklam
 */
class Application implements
    \Psr\Log\LoggerAwareInterface,
    \FreeFW\Interfaces\ConfigAwareTraitInterface
{

    /**
     * comportements
     */
    use \Psr\Log\LoggerAwareTrait;
    use \FreeFW\Behaviour\EventManagerAwareTrait;
    use \FreeFW\Behaviour\ConfigAwareTrait;
    use \FreeFW\Behaviour\RequestAwareTrait;
    use \FreeFW\Behaviour\StorageListenerTrait;

    /**
     * Router
     * @var \FreeFW\Http\Router
     */
    protected $router = null;

    /**
     * Route
     * @var \FreeFW\Router\Route
     */
    protected $route = null;

    /**
     * Rendered ?
     * @var boolean
     */
    protected $rendered = false;

    /**
     * Constructor
     *
     * @param \FreeFW\Application\Config $p_config
     */
    protected function __construct(
        \FreeFW\Application\Config $p_config,
        \Psr\Log\LoggerInterface $p_logger
    ) {
        $this->setAppConfig($p_config);
        $this->setLogger($p_logger);
        $this->router = new \FreeFW\Http\Router();
        $this->router->setLogger($this->logger);
        $bp = $p_config->get('basepath', false);
        if ($bp !== false) {
            $this->router->setBasePath($bp);
        }
        \FreeFW\DI\DI::setShared('router', $this->router);
        $this->initCache();
    }

    /**
     * Event de fin
     *
     * @return void
     */
    protected function afterRender()
    {
        if (!$this->rendered) {
            $this->logger->info('FreeFW.Application.afterRender.start');
            $manager = $this->getEventManager();
            $manager->notify(\FreeFW\Constants::EVENT_AFTER_RENDER);
            $this->logger->info('FreeFW.Application.afterRender.end');
            $this->rendered = true;
        }
        return $this;
    }

    /**
     * Return logger
     *
     * @return \Psr\Log\LoggerInterface
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     *
     * @param \FreeFW\Router\RouteCollection $p_collection
     */
    public function addRoutes(\FreeFW\Router\RouteCollection $p_collection)
    {
        $this->router->addRoutes($p_collection);
        return $this;
    }

    /**
     * Check for cache server
     *
     * @return boolean
     */
    protected function initCache()
    {
        // Le cache
        $myCacheCfg = self::getAppConfig()->get('cache');
        if (is_array($myCacheCfg)) {
            if (isset($myCacheCfg['type'])) {
                if ($myCacheCfg['type'] != \FreeFW\Cache\CacheFactory::FILE) {
                    try {
                        switch ($myCacheCfg['type']) {
                            case \FreeFW\Cache\CacheFactory::REDIS:
                                if (class_exists('\\Redis')) {
                                    $retry = true;
                                    $nb    = 10;
                                    $redis = new \Redis();
                                    $result = $redis->connect($myCacheCfg['arg0'], $myCacheCfg['arg1']);
                                    while ($nb > 0 && $retry) {
                                        $params = $redis->info('persistence');
                                        if (isset($params['loading'])) {
                                            if (intval($params['loading']) == 0) {
                                                $retry = false;
                                            }
                                        }
                                        if ($retry) {
                                            sleep(1);
                                            $nb--;
                                        } else {
                                            $pong = strtolower($redis->ping());
                                            if ($pong != '1' && strpos($pong, 'pong') === false) {
                                                $retry = true;
                                                sleep(1);
                                                $nb--;
                                            }
                                        }
                                    }
                                    if (!$retry) {
                                        $db = 6;
                                        if (isset($myCacheCfg['arg2'])) {
                                            $db = $myCacheCfg['arg2'];
                                        }
                                        $redis->select($db);
                                        $cache = \FreeFW\Cache\CacheFactory::make(
                                            \FreeFW\Cache\CacheFactory::REDIS,
                                            $redis
                                        );
                                    } else {
                                        $cache = \FreeFW\Cache\CacheFactory::make(
                                            \FreeFW\Cache\CacheFactory::FILE,
                                            '/tmp'
                                        );
                                    }
                                    \FreeFW\DI\DI::setShared('cache', $cache);
                                    $this->getLogger()->info("FreeFW.Application.initCacheServer.cache.redis");
                                    return true;
                                }
                                break;
                        }
                    } catch (\Exception $ex) {
                        $this->getLogger()->error($ex->getMessage());
                    }
                } else {
                    $this->getLogger()->info("FreeFW.Application.initCacheServer.cache.file");
                    $dir = null;
                    if (isset($myCacheCfg['arg0'])) {
                        $dir = $myCacheCfg['arg0'];
                    }
                    $cache = \FreeFW\Cache\CacheFactory::make(\FreeFW\Cache\CacheFactory::FILE, $dir);
                    \FreeFW\DI\DI::setShared('cache', $cache);
                }
            }
        }
        return true;
    }
}
