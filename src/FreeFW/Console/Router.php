<?php
/**
 * Router en mode console
 *
 * @author jeromeklam
 * @package Routing
 * @category Console
 */
namespace FreeFW\Console;

/**
 * Router console
 * @author jeromeklam
 */
class Router extends \FreeFW\Router\AbstractRouter implements \FreeFW\Interfaces\Router
{

    /**
     * Instance
     *
     * @var Router
     */
    protected static $instance = null;

    /**
     * Reprise des commandes depuis un fichier de config
     * array['commands'] = array(
     *     'test' => array('test', '\Yzynet\Controller\Client'
     *     ),
     *     ...
     * );
     *
     * @param array $p_config
     *
     * @return \FreeFW\Router\RouterInterface
     */
    public static function getInstance(array $p_config = null)
    {
        if (self::$instance === null) {
            self::$instance = new self();
            if (is_array($p_config) && array_key_exists('routes', $p_config)) {
                self::$instance->addRoutes($p_config['routes']);
            }
        }
        
        return self::$instance;
    }

    /**
     * Recherche d'une route correspondante
     *
     * @param string $p_command
     * @param array  $p_params
     * @param string $p_requestMethod
     *
     * @return mixed (boolean | \FreeFW\Router\Route)
     */
    protected function match($p_command, $p_params, $p_requestMethod = 'CMD')
    {
        foreach ($this->routes->getAll() as $route) {
            if (!in_array($p_requestMethod, (array)$route->getMethods())) {
                continue;
            }
            if ($route->getName() == $p_command) {
                $route->setParameters($p_params);
                $route->setType(\FreeFW\Router\Route::TYPE_CMD);
                
                return $route;
            }
        }
        
        return false;
    }

    /**
     * Préparation de la recherche de la route
     *
     * @todo : gérer les paramètres de la forme --name=value
     *
     * @return mixed (boolean | \FreeFW\Router\Route)
     */
    public function matchCurrentRequest()
    {
        $request = $this->getDIRequest();
        $command = $request->getCommand();
        if ($command !== false) {
            return $this->match($command, $request->getAttributes());
        }
        
        return false;
    }
}
