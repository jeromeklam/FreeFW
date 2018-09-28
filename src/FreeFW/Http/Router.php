<?php
/**
 * Classe de gestion d'un router HTTP
 *
 * @author jeromeklam
 * @package Routing
 * @category HTTP
 */
namespace FreeFW\Http;

/**
 * Router
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
     * Préparation de la recherche de la route
     *
     * @return \FreeFW\Router\Route|false
     */
    public function matchCurrentRequest()
    {
        $requestMethod = (
            isset($_POST['_method'])
            && ($_method = strtoupper($_POST['_method']))
            && in_array($_method, array('PUT', 'DELETE'))
        ) ? $_method : $_SERVER['REQUEST_METHOD'];
        if (array_key_exists('HTTP_X_HTTP_METHOD_OVERRIDE', $_SERVER)
            && $_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'] == "DELETE"
        ) {
            $requestMethod = "DELETE";
        }
        $requestUrl = '/' . ltrim($_SERVER['REQUEST_URI'], '/');
        // Variables spécifiques API
        $version = '';
        if (array_key_exists('_api_vers', $_GET)) {
            $version = $_GET['_api_vers'];
        }
        $type = false;
        if (array_key_exists('_api_type', $_GET)) {
            $type = $_GET['_api_type'];
        }
        // Suite
        if (array_key_exists('_request', $_GET)) {
            $requestUrl = '/' . ltrim($_GET['_request'], '/');
        }
        if (($pos = strpos($requestUrl, '?')) !== false) {
            if (array_key_exists('_url', $_GET)) {
                $requestUrl = $_GET['_url'];
            } else {
                $requestUrl = substr($requestUrl, 0, $pos);
            }
        }
        return $this->match($requestUrl, $requestMethod, $version, $type);
    }

    /**
     * Recherche de la route
     *
     * @param string $requestUrl
     * @param string $requestMethod
     * @param string $p_version
     * @param string $p_type
     *
     * @return \FreeFW\Router\Route|false
     */
    public function match($requestUrl, $requestMethod = 'GET', $p_version = '', $p_type = false)
    {
        $end = false;
        if ($this->basePath != '') {
            $end = strpos($requestUrl, $this->basePath);
            if ($end !== false && $end == 0) {
                $requestUrl = substr($requestUrl, strlen($this->basePath));
            }
        }
        if ($p_version != '') {
            $end = strpos($requestUrl, '/' . $p_version);
            if ($end !== false && $end == 0) {
                $requestUrl = substr($requestUrl, strlen('/' . $p_version));
            }
        }
        // self::debug($requestUrl);
        // Parcours des route pour trouver celle qui colle
        foreach ($this->routes->getAll() as $oneRroute) {
            if (!in_array($requestMethod, (array)$oneRroute->getMethods())) {
                continue;
            }
            $currentDir = dirname($_SERVER['SCRIPT_NAME']);
            if ($currentDir != '/') {
                $requestUrl = str_replace($currentDir, '', $requestUrl);
            }
            // var_dump("#^" . $oneRroute->getRegex() . "$#", $requestUrl);
            $params = array();
            if ($oneRroute->getUrl() == $requestUrl) {
                // Ok, pas besoin de compliquer....
            } else {
                // @todo : ajouter la notion obligatoire pour les paramètres
                if (!preg_match("#^" . $oneRroute->getRegex() . "$#", $requestUrl, $matches)) {
                    continue;
                }
                // On a trouvé une route, on récupère les paramètres.
                // On va en profiter pour les injecter dans la requête
                $matchedText = array_shift($matches);
                //var_dump("/:([\w-\._@%]+)/", $oneRroute->getUrl());
                //
                if (preg_match_all("/:([\w-\._@%]+)/", $oneRroute->getUrl(), $argument_keys)) {
                    $argument_keys = $argument_keys[1];
                    if (count($argument_keys) != count($matches)) {
                        continue;
                    }
                    foreach ($argument_keys as $key => $name) {
                        if (isset($matches[$key]) && $matches[$key] !== null
                            && $matches[$key] != '' && $matches[$key] != '/'
                        ) {
                            $params[$name] = ltrim($matches[$key], '/'); // Pour les paramètres optionnel
                        } else {
                            $params[$name] = '';
                        }
                    }
                }
            }
            $request = $this->getDIRequest();
            // On injecte les paramètres
            if (is_array($params) && count($params) >= 0) {
                $this->setDIRequest($request->mergeQueryParams($params));
            }
            if (($method = $oneRroute->getForcedMethod()) !== false) {
                $this->setDIRequest($request->withMethod($method));
            }
            // Je gère les paramètres décrits sur la route : @TODO
            // Suite
            $oneRroute
                ->setParameters($params)
                ->setBasePath($this->basePath)
            ;
            $this->setCurrentRoute($oneRroute);
            return $oneRroute;
        }
        return false;
    }

    /**
     * Génère une route
     *
     * @param string $routeName
     * @param array  $params
     *
     * @throws Exception
     *
     * @return string
     */
    public function generate($routeName, array $params = array())
    {
        if (!isset($this->namedRoutes[$routeName])) {
            throw new \Exception("No route with the name $routeName has been found.");
        }
        $route = $this->namedRoutes[$routeName];
        $url   = $route->getUrl();
        if ($params && preg_match_all("/:(\w+)/", $url, $param_keys)) {
            $param_keys = $param_keys[1];
            foreach ($param_keys as $key) {
                if (isset($params[$key])) {
                    $url = preg_replace("/:(\w+)/", $params[$key], $url, 1);
                }
            }
        }
        return $url;
    }

    /**
     * Reprise des routes depuis un fichier de config
     * array['routes'] = array(
     *     'test' => array('/api/test', '\Yzynet\Controller\Client'
     *     ),
     *     ...
     * );
     *
     * @param array $p_config
     *
     * @return \FreeFW\Router\Router
     */
    public static function getInstance(array $p_config = null)
    {
        if (self::$instance === null) {
            self::$instance = new self();
            if (is_array($p_config)) {
                if (array_key_exists('routes', $p_config)) {
                    self::$instance->addRoutes($p_config['routes']);
                }
                if (isset($p_config['base_path'])) {
                    self::$instance->setBasePath($p_config['base_path']);
                }
            }
        }
        return self::$instance;
    }
}
