<?php
/**
 * Classe de gestion d'une application de type restful
 *
 * @author jeromeklam
 * @package Application
 */
namespace FreeFW\Application;

use FreeFW\ResourceDi;
use FreeFW\Migration\Schema\Schema;
use DirectoryIterator;

/**
 * Micro application
 * @author jeromeklam
 */
class Micro extends \FreeFW\Application\Base
{

    /**
     * Instance
     *
     * @var \FreeFW\Application\Micro
     */
    protected static $instance;

    /**
     * Retourne une instance
     *
     * @param \FreeFW\Application\Config $p_config
     *
     * @return \FreeFW\Application\Micro
     */
    public static function getInstance($p_config)
    {
        if (self::$instance === null) {
            self::$instance = new self($p_config);
        }
        return self::$instance;
    }

    /**
     * Singleton, donc pas de constructeur public
     *
     * @param \FreeFW\Application\Config $p_config
     */
    protected function __construct($p_config = null)
    {
        $this->checkSystem();
        parent::__construct('Json');
        // Set config
        self::setDIConfig($p_config);
        // Request
        $request = \FreeFW\Http\ServerRequest::fromGlobals();
        self::setDIRequest($request);
        // Le logger
        $myLogCfg = self::getDIConfig()->get('logger');
        if (is_array($myLogCfg)) {
            if (array_key_exists('file', $myLogCfg)) {
                if (array_key_exists('level', $myLogCfg)) {
                    $logFile  = str_replace(':caller:', $request->getCaller(), $myLogCfg['file']);
                    $myLogger = new \FreeFW\Log\FileLogger($logFile, $myLogCfg['level']);
                    self::setLogger($myLogger);
                } else {
                    throw new \InvalidArgumentException('Le level du logger est manquant !');
                }
            } else {
                throw new \UnexpectedValueException('Le type de logger est inconnu !');
            }
        } else {
            self::setLogger(new \Psr\Log\NullLogger());
        }
        // Nouvelle réponse, par défaut en json
        $this->setResponse(new \FreeFW\Http\FreeFWResponse());
        // Le cache
        $myCacheCfg = self::getDIConfig()->get('cache');
        if (is_array($myCacheCfg)) {
            if (array_key_exists('type', $myCacheCfg)) {
                $cache = \FreeFW\Cache\CacheFactory::make(
                    $myCacheCfg['type'],
                    $myCacheCfg['arg0'],
                    $myCacheCfg['arg1']
                );
                $this->setDIShared('cache', $cache);
            }
        }
    }

    /**
     * Retourne le router
     *
     * @return \FreeFW\Interfaces\Router
     */
    public function getRouter()
    {
        $router = self::getDIShared('router');
        if ($router === false) {
            $router = \FreeFW\Http\Router::getInstance(self::getDIConfig()->getAsArray());
            $bP     = self::getDIConfig()->get('apiBasePath');
            if ($bP == '') {
                $bP = '/api/';
            } else {
                $bP = rtrim($bP, '/') . '/api/';
            }
            $router->setBasePath($bP);
            foreach (self::getDIModules() as $name => $module) {
                $ns         = str_replace('.', '/', $module['ns']);
                $routesFile = rtrim($module['path'], '/') . '/' . $module['name'] . '/src/' . $ns .
                              '/Resources/Routes/micro.php';
                if (is_file($routesFile)) {
                    include $routesFile;
                    $router->addModule(array('routes' => $routes));
                } else {
                    $dir = rtrim($module['path'], '/') . '/' . $module['name'] . '/src/' .
                           $ns . '/Resources/Routes/';
                    if (is_dir($dir)) {
                        $d = new DirectoryIterator($dir) or die("Failed opening directory $dir for reading");
                        foreach ($d as $i => $fileinfo) {
                            // skip hidden files
                            if ($fileinfo->isDot()) {
                                continue;
                            }
                            if ($fileinfo->isDir()) {
                                $routesFile = rtrim($module['path'], '/') . '/' . $module['name'] .
                                              '/src/' . $ns . '/Resources/Routes/' .
                                              $fileinfo->getFilename() . '/micro.php';
                                if (is_file($routesFile)) {
                                    include $routesFile;
                                    $router->addModule(array('routes' => $routes));
                                }
                            }
                        }
                    }
                }
            }
            self::setDIShared('router', $router);
        }
        return $router;
    }

    /**
     * Que doit-on faire ??
     *
     * @deprecated
     *
     * @return void
     */
    public function handle()
    {
        self::debug('restful.handle.start');
        $router  = $this->getRouter();
        $manager = $this->getEventManager();
        $request = $this->getDIRequest();
        $config  = $this->getDIConfig();
        self::debug('restful.handle.check');
        // Get route
        $route = $router->matchCurrentRequest();
        if ($route !== false) {
            $route
                ->setMiddlewares($config->get('app:middlewares:micro'))
            ;
            $response = $route->process($request);
            $this->beforeFinish();
            self::debug('restful.before.render');
            $response->render();
        } else {
            self::debug(sprintf('restful.route.not.found : %s', $request->getUri()));
            $this->beforeFinish();
            $manager->notify(\FreeFW\Constants::EVENT_ROUTE_NOT_FOUND);
        }
    }

    /**
     * Run
     *
     * @retuen void
     */
    public function run()
    {
        self::debug('restful.run.start');
        $manager = $this->getEventManager();
        $request = $this->getDIRequest();
        $config  = $this->getDIConfig();
        $router  = $this->getRouter();
        self::setDIShared('router', $router);
        // Middlaware stack
        $pipeline = new \FreeFW\Middleware\Pipeline();
        foreach ($this->getDIMiddlewares() as $idx => $class) {
            $object = new $class();
            $pipeline->addMiddleware($object);
        }
        $response = $pipeline->handle($request);
        $this->beforeFinish();
        self::debug('restful.run.before.render');
        $response->render();
        self::debug('restful.run.after.render');
        $this->afterRender();
    }
}
