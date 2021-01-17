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
            $this->logger->debug('application.afterRender.start');
            $manager = $this->getEventManager();
            $manager->notify(\FreeFW\Constants::EVENT_AFTER_RENDER);
            $this->logger->debug('application.afterRender.end');
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
     *
     * @param object $p_object
     * @param object $p_queue
     * @param object $p_queueCfg
     * @param string $p_event_name
     */
    public function listen($p_object, $p_queue, $p_queueCfg, $p_event_name = null) {
        if ($p_event_name == '') {
            $p_event_name = 'unknown';
        }
        switch ($p_event_name) {
            case \FreeFW\Constants::EVENT_STORAGE_BEGIN:
                $this->in_transaction += 1;
                break;
            case \FreeFW\Constants::EVENT_STORAGE_ROLLBACK:
                $this->updates = [];
            case \FreeFW\Constants::EVENT_STORAGE_COMMIT:
                $this->in_transaction -= 1;
                break;
            case \FreeFW\Constants::EVENT_STORAGE_DELETE:
            case \FreeFW\Constants::EVENT_STORAGE_UPDATE:
            case \FreeFW\Constants::EVENT_STORAGE_CREATE:
                if ($p_object instanceof \FreeFW\Core\Model && $p_object->forwardStorageEvent()) {
                    $this->updates[] = [
                        'event' => $p_event_name,
                        'type'  => $p_object->getApiType(),
                        'id'    => $p_object->getApiId()
                    ];
                }
                break;
        }
        // Only Core Models
        if ($this->in_transaction <= 0) {
            $this->in_transaction = 0;
            // Only if requested
            try {
                foreach ($this->updates as $oneUpdate) {
                    // First send Event to webSocket...
                    $context = new \ZMQContext();
                    $socket = $context->getSocket(\ZMQ::SOCKET_PUSH, 'my event');
                    $socket->connect("tcp://127.0.0.1:5555");
                    $socket->send(serialize($oneUpdate));
                    // And then to RabbitMQ
                    $properties = [
                        'content_type' => 'application/json',
                        'delivery_mode' => \PhpAmqpLib\Message\AMQPMessage::DELIVERY_MODE_PERSISTENT
                    ];
                    $channel = $p_queue->channel();
                    // Exchange as fanout, only to connected consumers
                    $channel->exchange_declare($p_queueCfg['name'], 'fanout', false, false, false);
                    $msg = new \PhpAmqpLib\Message\AMQPMessage(
                        serialize($oneUpdate),
                        $properties
                    );
                    $channel->basic_publish($msg, $p_queueCfg['name']);
                    $channel->close();
                }
            } catch (\Exception $ex) {
                // @todo...
            }
        }
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
                }
            }
        }
        $this->getLogger()->info("FreeFW.Application.initCacheServer.cache.file");
        $dir = null;
        if (isset($myCacheCfg['arg0'])) {
            $dir = $myCacheCfg['arg0'];
        }
        $cache = \FreeFW\Cache\CacheFactory::make(\FreeFW\Cache\CacheFactory::FILE, $dir);
        \FreeFW\DI\DI::setShared('cache', $cache);
        return true;
    }
}
