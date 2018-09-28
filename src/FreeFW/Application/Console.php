<?php
/**
 * Classe de gestion d'une application de type console
 *
 * @author jeromeklam
 * @package Application
 */
namespace FreeFW\Application;

/**
 * Console application
 * @author jeromeklam
 */
class Console extends \FreeFW\Application\Base
{

    /**
     * Instance
     *
     * @var \FreeFW\Application\Console
     */
    protected static $instance;

    /**
     * Retourne une instance
     *
     * @param unknown $p_config
     *
     * @return \FreeFW\Application\Console
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
    public function __construct($p_config = null)
    {
        $this->checkSystem();
        parent::__construct('Console');
        // Set config
        self::setDIConfig($p_config);
        // Request
        $request = new \FreeFW\Console\Request();
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
        // Nouvelle réponse
        $this->_response = new \FreeFW\Console\Response();
    }

    /**
     * Retourne le router
     *
     * @return \FreeFW\Router\Router
     */
    public function getRouter()
    {
        $router = self::getDIShared('router');
        if ($router === null || $router === false) {
            $router = \FreeFW\Console\Router::getInstance();
            foreach (self::getDIModules() as $name => $module) {
                $ns         = str_replace('.', '/', $module['ns']);
                $routesFile = rtrim($module['path'], '/') . '/' . $module['name'] . '/src/' . $ns .
                              '/Resources/Routes/console.php';
                if (is_file($routesFile)) {
                    include $routesFile;
                    $router->addModule(array('routes' => $routes));
                } else {
                    $routesFile = rtrim($module['path'], '/') . '/' . $module['name'] . '/src/' . $ns .
                                  '/Resources/Routes/routes.php';
                    if (is_file($routesFile)) {
                        include $routesFile;
                        $router->addModule(array('routes' => $routes));
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
     * @return void
     */
    public function handle()
    {
        $router  = $this->getRouter();
        $manager = $this->getEventManager();
        $route   = $router->matchCurrentRequest();
        $request = self::getDIRequest();
        if ($route === false || $route === null) {
            self::debug('console.before.finish');
            $this->beforeFinish();
            self::debug('console.event.route.not.found');
            self::debug(sprintf('console.requête : %s', $request));
            $manager->notify(\FreeFW\Constants::EVENT_COMMAND_NOT_FOUND);
        } else {
            self::debug(sprintf('console.route : %s', $route));
            $response = $route->dispatch($this);
            self::debug('console.before.finish');
            $this->beforeFinish();
            self::debug('console.before.render');
            $response->render();
            self::debug('console.after.render');
        }
    }
}
