<?php
/**
 * Classe abstraite de gestion des routers
 *
 * @author jeromeklam
 * @package Routing
 * @category Abstract
 */
namespace FreeFW\Router;

/**
 * Classe abstraite de gestion des routers
 * @author jeromeklam
 */
class AbstractRouter
{

    /**
     * Comportements
     */
    use \FreeFW\Behaviour\DI;
    use \FreeFW\Behaviour\LoggerAwareTrait;

    /**
     * Préfixe de la route
     *
     * @var string
     */
    protected $basePath = '';

    /**
     * Liste complète des routes
     *
     * @var array
     */
    protected $routes = array();

    /**
     * Routes nommées
     *
     * @var array
     */
    protected $namedRoutes = array();

    /**
     * Route courante
     *
     * @var \FreeFW\Router\Route
     */
    protected $current_route = null;

    /**
     * Constructeur
     *
     * @param \FreeFW\Router\RouteCollection $collection
     */
    protected function __construct()
    {
        $this->routes = new \FreeFW\Router\RouteCollection();
    }

    /**
     * Affectation de la route courante
     *
     * @param \FreeFW\Router\Route $p_route
     *
     * @return \FreeFW\Router\AbstractRouter
     */
    public function setCurrentRoute($p_route)
    {
        $this->current_route = $p_route;
        return $this;
    }

    /**
     * Retourne la route courante
     *
     * @return \FreeFW\Router\Route
     */
    public function getCurrentRoute()
    {
        return $this->current_route;
    }

    /**
     * Ajout de routes
     *
     * @param array $p_routes
     *
     * @return \FreeFW\Http\Router
     */
    protected function addRoutes($p_routes)
    {
        foreach ($p_routes as $name => $route) {
            if (is_array($route) and count($route) >= 3) {
                if (array_key_exists('url', $route)) {
                    // tableau associatif
                    // @todo : verify minimum properties exists
                    $newRoute = new \FreeFW\Router\Route(
                        $route['url'],
                        $name,
                        str_replace('.', '::', $route['controller']),
                        explode(',', $route['methods'])
                    );
                    if (array_key_exists('version', $route)) {
                        $newRoute->setVersion($route['version']);
                    }
                    if (array_key_exists('role', $route)) {
                        $newRoute->setRole($route['role']);
                    }
                    if (array_key_exists('secured', $route)) {
                        $newRoute->setSecure($route['secured']);
                    }
                    if (array_key_exists('auth', $route)) {
                        $newRoute->setAuth($route['auth']);
                    }
                    if (array_key_exists('type', $route)) {
                        $newRoute->setType($route['type']);
                    }
                    if (array_key_exists('filters', $route)) {
                        $newRoute->setFilters($route['filters'], true);
                    }
                    if (array_key_exists('force', $route)) {
                        $newRoute->setForcedMethod($route['force']);
                    }
                    if (array_key_exists('menus', $route)) {
                        $newRoute->setMenus($route['menus']);
                    }
                    if (array_key_exists('middleware', $route)) {
                        $newRoute->setMiddlewares($route['middleware']);
                    }
                    if (array_key_exists('package', $route)) {
                        $newRoute->setPackage($route['package']);
                    }
                    if (array_key_exists('help', $route)) {
                        $newRoute->setDescription($route['help']);
                    } else {
                        if (array_key_exists('description', $route)) {
                            $newRoute->setDescription($route['description']);
                        }
                    }
                } else {
                    //Ancien systeme de lecture des routes plus fonctionnel
                    //seul clé/valeur supporté
                    var_dump($route);
                    echo('Ancien système de route non supporté. (système clé/valeur maintenant)');
                    die;
                }
                $this->namedRoutes[$name] = $route;
                $this->routes->attachRoute($newRoute);
            } else {
                throw new \Exception(sprintf('Error in config for route %s!', $name));
            }
        }
        return $this;
    }

    /**
     * Ajout d'un module
     *
     * @param array $p_config
     *
     * @return \FreeFW\Http\Router
     */
    public function addModule($p_config)
    {
        if (array_key_exists('routes', $p_config)) {
            $this->addRoutes($p_config['routes']);
        }
        return $this;
    }

    /**
     * Affichage des routes
     */
    public function dumpRoutes()
    {
        foreach ($this->namedRoutes as $name => $route) {
            var_dump($route);
        }
    }

    /**
     * Affectation du chemin de base
     *
     * @param $basePath
     *
     * @return \FreeFW\Router\Router
     */
    public function setBasePath($basePath)
    {
        $this->basePath = rtrim($basePath, '/');
        return $this;
    }

    /**
     * Retourne le chemin de base
     *
     * @return string
     */
    public function getBasePath()
    {
        return $this->basePath;
    }

    /**
     * Retourne une route en fonction de son nom
     *
     * @param string $p_name
     *
     * @return \FreeFW\Router\Route|false
     */
    public function getRouteByName($p_name)
    {
        foreach ($this->routes as $idx => $route) {
            if (strtoupper($route->getName()) == strtoupper($p_name)) {
                $route->setBasePath($this->getBasePath());
                return $route;
            }
        }
        return false;
    }

    /**
     * Retourne la liste des routes
     *
     * @return array
     */
    public function getRoutes()
    {
        return $this->routes;
    }
}
