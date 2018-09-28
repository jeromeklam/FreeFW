<?php
/**
 * Route
 *
 * @author jeromeklam
 * @package Routing
 * @category Model
 */
namespace FreeFW\Router;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Route
 * @author jeromeklam
 */
class Route implements \JsonSerializable
{

    /**
     * Type de réponse
     *
     * @var string
     */
    const TYPE_APP     = 'APP';
    const TYPE_JSON    = 'JSON';
    const TYPE_HTML    = 'HTML';
    const TYPE_TEXT    = 'TEXT';
    const TYPE_TWIG    = 'TWIG';
    const TYPE_CMD     = 'CMD';
    const TYPE_API     = 'API';
    const TYPE_JSONAPI = 'JSONAPI';

    /**
     * Authentification
     * @var string
     */
    const AUTH_USER   = 'AUTH_USER';
    const AUTH_SERVER = 'AUTH_SERVER';
    const AUTH_NONE   = 'AUTH_NONE';
    const AUTH_AUTO   = 'AUTH_AUTO';

    /**
     * Principaux rôles
     * @var string
     */
    const ROLE_LIST  = 'LIST';
    const ROLE_GET   = 'GET';
    const ROLE_ADD   = 'CREATE';
    const ROLE_MAJ   = 'UPDATE';
    const ROLE_DEL   = 'DELETE';
    const ROLE_OTHER = 'OTHER';

    /**
     * Méthodes
     * @var string
     */
    const METHOD_GET     = 'GET';
    const METHOD_POST    = 'POST';
    const METHOD_PUT     = 'PUT';
    const METHOD_DELETE  = 'DELETE';
    const METHOD_OPTIONS = 'OPTIONS';

    /**
     * securiées standard
     *
     * @var string
     */
    const SECURE_PUBLIC = 'PUBLIC';
    const SECURE_LOGGED = 'LOGGED';

    /**
     * url de la route
     *
     * @var string
     */
    private $url;

    /**
     * Version
     * @var string
     */
    private $version = null;

    /**
     * BasePath
     * @var string
     */
    private $basePath = null;

    /**
     * Methodes autorisées
     *
     * @var string[]
     */
    private $methods = array('GET', 'POST', 'PUT', 'DELETE', 'CMD');

    /**
     * Nom de la route
     * @var string
     */
    private $name = null;

    /**
     * Main package
     * @var string
     */
    private $package = null;

    /**
     * Titre
     * @var string
     */
    private $title = null;

    /**
     * Filtres
     * @var array
     */
    private $filters = array();

    /**
     * Type de réponse attendue
     * @var String
     */
    private $type = self::TYPE_APP;

    /**
     * Rôle principal de la route
     * @var string
     */
    private $role = self::ROLE_OTHER;

    /**
     * Paramètres
     * @var array
     */
    private $parameters = array();

    /**
     * Description des paramètres
     * @var array
     */
    private $params = array();

    /**
     * Résultats possibles
     * @var array
     */
    private $results = array();

    /**
     * Nom des paramètres
     *
     * @exemple [ [0] => [ ["link_id"] => "12312" ] ]
     * @var     bool
     */
    private $parametersByName;

    /**
     * Controller
     * @var array
     */
    private $controller;

    /**
     * Mode de sécurité de l'url
     * @var string
     */
    private $secure = self::SECURE_PUBLIC;

    /**
     * Authentification
     * @var string
     */
    private $auth = self::AUTH_NONE;

    /**
     * Méthode forcée en retour
     * @var mixed
     */
    private $forced_method = false;

    /**
     * Menu
     * @var string
     */
    private $menus = false;

    /**
     * Middlewares spécifiques à la route
     * @var array
     */
    private $spec_middleware = array();

    /**
     * Middlewares config
     * @param array
     */
    private $middlewares = array();

    /**
     * Description
     * @var array
     */
    private $description = null;

    /**
     * Description gérée ??
     * @var boolean
     */
    private $processed = false;

    /**
     * Constructeur
     *
     * @param string $p_url
     * @param string $p_name
     * @param string $p_controller
     * @param array  $p_methods
     */
    public function __construct($p_url, $p_name = null, $p_controller = null, $p_methods = array())
    {
        $this
            ->setUrl($p_url)
            ->setName($p_name)
            ->setController($p_controller)
            ->setMethods($p_methods)
        ;
    }

    /**
     * Affectation des middlewares spécifiques
     *
     * @param array $p_specMiddleware
     *
     * @return \FreeFW\Router\Route
     */
    public function setSpecMiddleware($p_specMiddleware)
    {
        if (is_array($p_specMiddleware)) {
            $this->spec_middleware = $p_specMiddleware;
        }
        return $this;
    }

    /**
     * Retourne les middlewares spécifiques
     *
     * @return array
     */
    public function getSpecMiddleware()
    {
        return $this->spec_middleware;
    }

    /**
     * Affectation des middlewares
     *
     * @param array $p_middlewares
     *
     * @return \FreeFW\Router\Route
     */
    public function setMiddlewares($p_middlewares)
    {
        if (is_array($p_middlewares)) {
            $this->middlewares = $p_middlewares;
        }
        return $this;
    }

    /**
     * Get middlewares
     *
     * @return array
     */
    public function getMiddlewares()
    {
        return $this->middlewares;
    }

    /**
     * Retourne le type
     *
     * #return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Affectation du type
     *
     * @param string $p_type
     *
     * @return \FreeFW\Router\Route
     */
    public function setType($p_type)
    {
        $this->type = $p_type;
        return $this;
    }

    /**
     * Récupération du rôle
     *
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Affectation du rôle
     *
     * @param string $p_role
     *
     * @return \FreeFW\Router\Route
     */
    public function setRole($p_role)
    {
        $this->role = $p_role;
        return $this;
    }

    /**
     * Retourne l'url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Affectation de l'url
     *
     * @param string $p_url
     *
     * @return \FreeFW\Router\Route
     */
    public function setUrl($p_url)
    {
        $this->url = (string)$p_url;
        return $this;
    }

    /**
     * Affectation de la version
     *
     * @param string $p_version
     *
     * @return \FreeFW\Router\Route
     */
    public function setVersion($p_version)
    {
        $this->version = $p_version;
        return $this;
    }

    /**
     * Retourne la version
     *
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Affectation du package
     *
     * @param string $p_package
     *
     * @return \FreeFW\Router\Route
     */
    public function setPackage($p_package)
    {
        $this->package = $p_package;
        return $this;
    }

    /**
     * Récupération du package
     *
     * @return string
     */
    public function getPackage()
    {
        return $this->package;
    }

    /**
     * Affectation du controlleur
     *
     * @param string $p_controller
     *
     * @return \FreeFW\Router\Route
     */
    public function setController($p_controller)
    {
        $this->controller = $p_controller;
        return $this;
    }

    /**
     * Récupération du controller
     *
     * @return array
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * Affectation du titrte
     *
     * @param string $p_title
     *
     * @return \FreeFW\Router\Route
     */
    public function setTitle($p_title)
    {
        $this->title = $p_title;
        return $this;
    }

    /**
     * Retourne le titre
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Affectation du chemin de base
     *
     * @param string $p_basePath
     *
     * @return \FreeFW\Router\Route
     */
    public function setBasePath($p_basePath)
    {
        $this->basePath = $p_basePath;
        return $this;
    }

    /**
     * Retourne les méthodes
     *
     * @return array
     */
    public function getMethods()
    {
        return $this->methods;
    }

    /**
     * Retourne la première méthode
     *
     * @return string
     */
    public function getFirstMethod()
    {
        $method = 'GET';
        if (is_array($this->methods)) {
            if (count($this->methods) > 0) {
                $method = $this->methods[0];
            }
        } else {
            $temp   = explode(',', $this->methods);
            $method = $temp[0];
        }
        return strtoupper(trim($method));
    }

    /**
     * Affectation de la méthode
     *
     * @param string $p_method
     *
     * @return \FreeFW\Router\Route
     */
    public function setMethod($p_method)
    {
        $this->methods = array($p_method);
        return $this;
    }

    /**
     * Affectation des méthodes
     *
     * @var mixed $p_methods
     *
     * @return \FreeFW\Router\Route
     */
    public function setMethods($p_methods)
    {
        if (is_array($p_methods)) {
            $this->methods = $p_methods;
        } else {
            $this->methods = array($p_methods);
        }
        return $this;
    }

    /**
     * Retourne le nom de la route
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Affectation du nom de la route
     *
     * @var string $name
     *
     * @return \FreeFW\Router\Route
     */
    public function setName($name)
    {
        $this->name = (string)$name;
        return $this;
    }

    /**
     * Affectation de la sécurité de la route
     *
     * @param string $p_secure
     *
     * @return \FreeFW\Router\Route
     */
    public function setSecure($p_secure)
    {
        $this->secure = false;
        if ($p_secure === true || $p_secure === 1 || in_array(strtoupper($p_secure), ['Y', 'O', 'LOGGED', 'SERVER'])) {
            $this->secure = true;
        }
        return $this;
    }

    /**
     * Retourne le type de sécurité de la route
     *
     * @return string
     */
    public function getSecure()
    {
        return $this->secure;
    }

    /**
     * Affectation auth
     *
     * @param string $p_auth
     *
     * @return \FreeFW\Router\Route
     */
    public function setAuth($p_auth)
    {
        $this->auth = $p_auth;
        return $this;
    }

    /**
     * Retourne le auth
     *
     * @return string
     */
    public function getAuth()
    {
        return $this->auth;
    }

    /**
     * Force la route affectée à la request...
     *
     * @return \FreeFW\Router\Route
     */
    public function setForcedMethod($p_forcedMethod)
    {
        $this->forced_method = $p_forcedMethod;
        return $this;
    }

    /**
     * Retourne la mùéthode forcée
     *
     * @return mixed
     */
    public function getForcedMethod()
    {
        return $this->forced_method;
    }

    /**
     * Retourne les menus
     */
    public function getMenus()
    {
        return $this->menus;
    }

    /**
     * Affectation des menus
     * @param array $menu
     * @return \FreeFW\Router\Route
     */
    public function setMenus($menu)
    {
        $this->menus = $menu;
        return $this;
    }

    /**
     * Affectation des filtres
     *
     * @var array   $filters
     * @var boolean $parametersByName
     *
     * @return \FreeFW\Router\Route
     */
    public function setFilters(array $filters, $parametersByName = false)
    {
        $this->filters = $filters;
        if ($parametersByName) {
            $this->parametersByName = true;
        }
        return $this;
    }

    /**
     * Retourne l'expression régulière de détection
     *
     * @return string
     */
    public function getRegex()
    {
        return preg_replace_callback("/\/(:\w+)/", array(&$this, 'substituteFilter'), $this->url);
    }

    /**
     * Remplacement des filtres
     *
     * @return string
     */
    private function substituteFilter($matches)
    {
        if (isset($matches[1]) && isset($this->filters[$matches[1]])) {
            return $this->filters[$matches[1]];
        }
        return "([/][\w-\._@%]*)";
    }

    /**
     * Retourne les paramètres
     *
     * @return array
     */
    public function getParams()
    {
        if ($this->processed == false && is_array($this->description)) {
            $this->processed = true;
            $this->params    = array();
            if (array_key_exists('params', $this->description)) {
                foreach ($this->description['params'] as $idx => $oneParam) {
                    $oneParam = array_merge(
                        [
                            'type'        => \FreeFW\Router\Param::TYPE_STRING,
                            'required'    => false,
                            'default'     => null,
                            'description' => $idx,
                            'comment'     => null,
                            'from'        => \FreeFW\Router\Param::FROM_QUERY,
                            'extended'    => false
                        ],
                        $oneParam
                    );
                    if (array_key_exists($idx, $this->parameters)) {
                        $oneParam['default'] = $this->parameters[$idx];
                    }
                    $extended = $oneParam['extended'];
                    // Cas particuliers
                    if ($oneParam['type'] == \FreeFW\Router\Param::TYPE_FILTER) {
                        $extended = [];
                        foreach ($oneParam['extended'] as $idx2 => $oneParam2) {
                            $oneParam2 = array_merge(
                                [
                                    'type'        => \FreeFW\Router\Param::TYPE_STRING,
                                    'required'    => false,
                                    'default'     => null,
                                    'description' => $idx,
                                    'comment'     => null,
                                    'from'        => \FreeFW\Router\Param::FROM_QUERY,
                                    'extended'    => false
                                ],
                                $oneParam2
                            );
                            $param2 = new \FreeFW\Router\Param();
                            $param2
                                ->setName($idx2)
                                ->setType($oneParam2['type'])
                                ->setDescription($oneParam2['description'])
                                ->setDefault($oneParam2['default'])
                                ->setRequired($oneParam2['required'])
                            ;
                            $extended[$idx2] = $param2;
                        }
                    }
                    // suite
                    $param = new \FreeFW\Router\Param();
                    $param
                        ->setName($idx)
                        ->setType($oneParam['type'])
                        ->setDescription($oneParam['description'])
                        ->setDefault($oneParam['default'])
                        ->setRequired($oneParam['required'])
                        ->setExtended($extended)
                    ;
                    $this->params[$idx] = $param;
                }
            }
        }
        if ($this->params === null) {
            $this->params = array();
        }
        return $this->params;
    }

    /**
     * Retourne les paramètres
     *
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * Affectation des paramètres
     *
     * @var array $parameters
     *
     * @return \FreeFW\Router\Route
     */
    public function setParameters(array $parameters)
    {
        $this->parameters = $parameters;
        return $this;
    }

    /**
     * Ajout d'un paramètre
     *
     * @param \FreeFW\Router\Param $p_param
     *
     * @return \FreeFW\Router\Route
     */
    public function addParameter($p_param)
    {
        $this->parameters[] = $p_param;
        return $this;
    }

    /**
     * Ajout d'un résultat
     *
     * @param \FreeFW\Router\Result $p_result
     *
     * @return \FreeFW\Router\Route
     */
    public function addResult($p_result)
    {
        $this->results[] = $p_result;
        return $this;
    }

    /**
     * Retourne la liste des résultats
     *
     * @return array
     */
    public function getResults()
    {
        return $this->results;
    }

    /**
     * Affectation de la description
     *
     * @param array $p_description
     *
     * @return \FreeFW\Router\Route
     */
    public function setDescription($p_description)
    {
        $this->description = $p_description;
        return $this;
    }

    /**
     * Retourne la description
     *
     * @return array
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Génération de la partie href d'une route
     *
     * @param array   $p_params
     * @param boolean $p_withOthers
     *
     * @return string
     */
    public function renderHref($p_params = array(), $p_withOthers = true)
    {
        $url   = $this->url;
        $taken = array();
        if (preg_match_all("/:([\w-\._@%]+)/", $url, $argument_keys)) {
            foreach ($argument_keys as $key => $name) {
                $taken[] = $name[0];
                if (is_array($name) && array_key_exists($name[0], $p_params)) {
                    $url = str_replace(':'.$name[0], $p_params[$name[0]], $url);
                } else {
                    $url = str_replace(':'.$name[0], '', $url);
                }
            }
        }
        if ($p_withOthers) {
            // Les autres paramètres non utilisés seront ajoutés en tant que paramètres standards
            // Pour l'instant pas de tests spécifiques ou de propriétés sur la route
            $others = array();
            foreach ($p_params as $nKey => $nVal) {
                if (!in_array($nKey, $taken)) {
                    $others[$nKey] = $nVal;
                }
            }
            if (count($others) > 0) {
                $query = http_build_query($others);
                $url .= '?' . $query;
            }
        }
        if ($this->basePath !== null) {
            return $this->basePath . $url;
        } else {
            return $url;
        }
    }

    /**
     * Génération de la liste des paramètres non compris directement dans l'url
     *
     * @param array $p_params
     *
     * @return string
     */
    public function getExtraParams($p_params = array())
    {
        $left  = array();
        $taken = array();
        $url   = $this->url;
        if (preg_match_all("/:([\w-\._@%]+)/", $url, $argument_keys)) {
            foreach ($argument_keys as $key => $name) {
                $taken[] = $name[0];
                if (is_array($name) && array_key_exists($name[0], $p_params)) {
                    $url = str_replace(':'.$name[0], $p_params[$name[0]], $url);
                } else {
                    $url = str_replace(':'.$name[0], '', $url);
                }
            }
        }
        // Les autres paramètres non utilisés seront ajoutés en tant que paramètres standards
        // Pour l'instant pas de tests spécifiques ou de propriétés sur la route
        foreach ($p_params as $nKey => $nVal) {
            if (!in_array($nKey, $taken)) {
                $left[$nKey] = array('name' => $nKey, 'value' => $nVal);
            }
        }
        return $left;
    }

    /**
     * Exécution
     *
     * @return \FreeFW\Router\Route
     */
    public function dispatch($p_app)
    {
        $action = explode('::', $this->getController());
        if (count($action)<=1) {
            throw new \Exception(sprintf('Route %s is not well configured, :: missing !', $this->name));
        }
        if (!class_exists($action[0])) {
            throw new \Exception(sprintf('Class %s doesn\'t exists !', $action[0]));
        }
        //var_dump($this->parameters);
        $instance = new $action[0];
        if ($this->parametersByName) {
            $this->parameters = $this->parameters;
        }
        // @todo : vérifier le nombre de paramètres...
        if (method_exists($instance, 'isDI') && $p_app !== null) {
            switch ($this->getType()) {
                case self::TYPE_TWIG:
                    $instance->setResponse(new \FreeFW\Http\TwigResponse());
                    break;
                case self::TYPE_JSON:
                    $resp = $p_app->getResponse();
                    if (!$resp->isJson()) {
                        $instance->setResponse(new \FreeFW\Http\FreeFWResponse());
                    } else {
                        $instance->setResponse($resp);
                    }
                    break;
                case self::TYPE_JSONAPI:
                    $resp = $p_app->getResponse();
                    if (!$resp->isJson()) {
                        $instance->setResponse(new \FreeFW\Http\JsonApi\Response());
                    } else {
                        $instance->setResponse($resp);
                    }
                    break;
                case self::TYPE_TEXT:
                case self::TYPE_HTML:
                    $instance->setResponse(new \FreeFW\Http\HtmlResponse());
                    break;
                case self::TYPE_CMD:
                    $instance->setResponse(new \FreeFW\Console\Response());
                    break;
                default:
                    $instance->setResponse($p_app->getResponse());
                    break;
            }
        }
        if (!method_exists($instance, $action[1])) {
            throw new \Exception(sprintf('Method %s not found in %s class !', $action[1], $action[0]));
        }
        // Cas spécifique de la ligne de commande
        if ($this->getType() == self::TYPE_CMD) {
            $request = $p_app->getDIRequest();
            $output  = new \FreeFW\Console\Output\ConsoleOutput();
            $input   = new \FreeFW\Console\Input\ParameterInput($request->getAttributes());
            return call_user_func_array(array($instance, $action[1]), array($output, $input));
        } else {
            return call_user_func_array(array($instance, $action[1]), $this->parameters);
        }
    }

    /**
     * Return controller class name
     *
     * @return string
     */
    public function getControllerClass()
    {
        $action = explode('::', $this->getController());
        return $action[0];
    }

    /**
     * Return function name
     *
     * @return string
     */
    public function getFunctionName()
    {
        $action = explode('::', $this->getController());
        return $action[1];
    }

    /**
     * Return new pipeline from middlewares
     *
     * @return \FreeFW\Middleware\Pipeline
     */
    protected function getPipeline()
    {
        $action = explode('::', $this->getControlle());
        if (count($action)<=1) {
            throw new \Exception(sprintf('Route %s is not well configured, :: missing !', $this->name));
        }
        if (!class_exists($action[0])) {
            throw new \Exception(sprintf('Class %s doesn\'t exists !', $action[0]));
        }
        //var_dump($this->parameters);
        $instance = new $action[0]();
        $response = new \FreeFW\Http\Response();
        $instance->setResponse($response);
        if ($this->parametersByName) {
            $this->parameters = $this->parameters;
        }
        $pipeline = new \FreeFW\Middleware\Pipeline($instance, $action[1], $this->parameters);
        if (array_key_exists($this->getType(), $this->middlewares)) {
            foreach ($this->middlewares[$this->getType()] as $idx => $middleware) {
                $pipeline->addMiddlewareByKey($middleware);
            }
        }
        if (array_key_exists($this->getSecure(), $this->middlewares)) {
            foreach ($this->middlewares[$this->getSecure()] as $idx => $middleware) {
                $pipeline->addMiddlewareByKey($middleware);
            }
        }
        return $pipeline;
    }

    /**
     * Process an incoming server request and return a response, optionally delegating
     * to the next middleware component to create the response.
     *
     * @param ServerRequestInterface $request
     *
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request)
    {
        // Make a pipeline with all middlewares
        $pipeline = $this->getPipeline();
        return $pipeline->process($request);
    }

    /**
     * Génération de la partie href d'une route
     *
     * @param array   $p_params
     * @param boolean $p_withOthers
     *
     * @return string
     */
    public static function renderHrefForObj($p_url, $p_obj, $p_withOthers = false)
    {
        $url   = $p_url;
        $taken = array();
        if (preg_match_all("/:([\w-\._@%]+)/", $url, $argument_keys)) {
            foreach ($argument_keys as $key => $name) {
                $taken[] = $name[0];
                $method  = 'get' . \FreeFW\Tools\PBXString::toCamelCase(strtolower($name[0]), true);
                if (method_exists($p_obj, $method)) {
                    $url = str_replace(':'.$name[0], $p_obj->$method(), $url);
                }
            }
        }
        if ($p_withOthers) {
            // Les autres paramètres non utilisés seront ajoutés en tant que paramètres standards
            // Pour l'instant pas de tests spécifiques ou de propriétés sur la route
            $others = array();
            foreach ($p_params as $nKey => $nVal) {
                $method  = \FreeFW\Tools\PBXString::toCamelCase(strtolower($nKey), true);
                if (method_exists($p_obj, $method)) {
                    $others[$nKey] = $p_obj->$method();
                }
            }
            if (count($others) > 0) {
                $query = http_build_query($others);
                $url .= '?' . $query;
            }
        }
        return $url;
    }

    /**
     * Génération de la partie href d'une route
     *
     * @param array   $p_params
     * @param boolean $p_withOthers
     *
     * @return string
     */
    public static function renderFullHref($p_url, $p_params = array(), $p_withOthers = false)
    {
        $url   = $p_url;
        $taken = array();
        if (preg_match_all("/:([\w-\._@%]+)/", $url, $argument_keys)) {
            foreach ($argument_keys as $key => $name) {
                $taken[] = $name[0];
                if (is_array($name) && array_key_exists($name[0], $p_params)) {
                    $url = str_replace(':'.$name[0], $p_params[$name[0]], $url);
                } else {
                    $url = str_replace(':'.$name[0], '', $url);
                }
            }
        }
        if ($p_withOthers) {
            // Les autres paramètres non utilisés seront ajoutés en tant que paramètres standards
            // Pour l'instant pas de tests spécifiques ou de propriétés sur la route
            $others = array();
            foreach ($p_params as $nKey => $nVal) {
                if (!in_array($nKey, $taken)) {
                    $others[$nKey] = $nVal;
                }
            }
            if (count($others) > 0) {
                $query = http_build_query($others);
                $url .= '?' . $query;
            }
        }
        return $url;
    }

    /**
     * Comparaison de 2 routes
     *
     * @param \FreeFW\Router\Route $a
     * @param \FreeFW\Router\Route $b
     *
     * @return number
     */
    public static function compareTo($a, $b)
    {
        return strcmp($a->getUrl(), $b->getUrl());
    }

    /**
     * Retourne l'objet sous forme de chaine
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getUrl() . ' -- ' . $this->getType();
    }

    /**
     * Conversion en tableau
     *
     * @return array
     */
    public function __toArray(){
        return array(
            'name'   => $this->getName(),
            'url'    => $this->getUrl(),
            'method' => $this->getFirstMethod()
        );
    }

    /**
     * {@inheritDoc}
     * @see JsonSerializable::jsonSerialize()
     */
    public function jsonSerialize()
    {
        return $this->__toArray();
    }

    /**
     * Cration du mock d'une route
     *
     * @param array $p_queryParams
     *
     * @return array
     */
    public function createMockRoute($p_uri = array(), $p_queryParams = array())
    {
        $result = $this->__toArray();
        if (is_array($p_uri) && count($p_uri) > 0) {
            $result['url']     = $this->renderHref($p_uri);
            $result['fullUrl'] = $result['url'];
        } else {
            $result['fullUrl'] = $result['url'];
        }
        if (is_array($p_queryParams) && count($p_queryParams) > 0) {
            $result['fullUrl'] = $result['fullUrl'] . '?' . http_build_query($p_queryParams);
        }
        $result['query'] = $p_queryParams;
        return $result;
    }
}
