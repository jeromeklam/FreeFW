<?php
namespace FreeFW\Router;

/**
 * Uses
 */
use \FreeFW\Constants AS FFCST;

/**
 * Standard route
 *
 * @author jeromeklam
 */
class Route implements \Psr\Log\LoggerAwareInterface
{

    /**
     * Behaviour
     */
    use \Psr\Log\LoggerAwareTrait;
    use \FreeFW\Behaviour\ConfigAwareTrait;

    /**
     * Routes constants
     * @var string
     */
    const ROUTE_COLLECTION     = 'collection';
    const ROUTE_COMMENT        = 'comment';
    const ROUTE_METHOD         = 'method';
    const ROUTE_MODEL          = 'model';
    const ROUTE_URL            = 'url';
    const ROUTE_CONTROLLER     = 'controller';
    const ROUTE_FUNCTION       = 'function';
    const ROUTE_AUTH           = 'auth';
    const ROUTE_MIDDLEWARE     = 'middleware';
    const ROUTE_PARAMETERS     = 'parameters';
    const ROUTE_RESULTS        = 'results';
    const ROUTE_INCLUDE        = 'include';
    const ROUTE_SCOPE          = 'scope';
    const ROUTE_CACHE          = 'cache';
    const ROUTE_ROLE           = 'role';
    const ROUTE_ADD_PROPERTIES = 'default';

    /**
     * Include constants
     * @var string
     */
    const ROUTE_INCLUDE_DEFAULT  = 'default';
    const ROUTE_INCLUDE_REQUIRED = 'required';
    const ROUTE_INCLUDE_LIST     = 'list';

    /**
     * Résultat
     * @var string
     */
    const ROUTE_RESULTS_TYPE    = 'type';
    const ROUTE_RESULTS_MODEL   = 'model';
    const ROUTE_RESULTS_COMMENT = 'comment';

    /**
     * Parameter position
     * @var string
     */
    const ROUTE_PARAMETER_ORIGIN_BODY   = 'body';
    const ROUTE_PARAMETER_ORIGIN_PATH   = 'path';
    const ROUTE_PARAMETER_ORIGIN_QUERY  = 'query';
    const ROUTE_PARAMETER_ORIGIN_COOKIE = 'cookie';
    const ROUTE_PARAMETER_ORIGIN_HEADER = 'header';

    /**
     * Options des paramètres
     * @var string
     */
    const ROUTE_PARAMETER_TYPE     = 'type';
    const ROUTE_PARAMETER_COMMENT  = 'comment';
    const ROUTE_PARAMETER_REQUIRED = 'required';
    const ROUTE_PARAMETER_ORIGIN   = 'origin';

    /**
     * Methods constants
     * @var string
     */
    const METHOD_GET    = 'get';
    const METHOD_POST   = 'post';
    const METHOD_PUT    = 'put';
    const METHOD_DELETE = 'delete';
    const METHOD_ALL    = 'all';

    /**
     * Roles
     * @var string
     */
    const ROLE_GET_ONE      = 'getone';
    const ROLE_CREATE_ONE   = 'createone';
    const ROLE_UPDATE_ONE   = 'updateone';
    const ROLE_DELETE_ONE   = 'delete';
    const ROLE_GET_FILTERED = 'getfiltered';
    const ROLE_AUTOCOMPLETE = 'autocomplete';

    /**
     * Auth constants
     * @var string
     */
    const AUTH_NONE = 'NONE';
    const AUTH_IN   = 'IN';
    const AUTH_OUT  = 'OUT';
    const AUTH_BOTH = 'BOTH';

    /**
     * Lists
     * @var string
     */
    const RESULT_LIST   = 'list';
    const RESULT_OBJECT = 'object';
    const RESULT_DATA   = 'data';

    /**
     * Uniq id
     * @var string
     */
    protected $id = null;

    /**
     * Method
     * @var string
     */
    protected $method = null;

    /**
     * Url
     * @var string
     */
    protected $url = null;

    /**
     * Contoller : ns::Controller::class
     * @var string
     */
    protected $controller = null;

    /**
     * Function to execute
     * @var string
     */
    protected $function = null;

    /**
     * Secured route ?
     * @var string
     */
    protected $auth = self::AUTH_NONE;

    /**
     * Default model
     * @var mixed
     */
    protected $default_model = null;

    /**
     * Params
     * @var array
     */
    protected $params = [];

    /**
     * Include
     * @var array
     */
    protected $include = [];

    /**
     * Collection
     * @var string
     */
    protected $collection = null;

    /**
     * Comment
     * @var string
     */
    protected $comment = null;

    /**
     * Parameters
     * @var array
     */
    protected $parameters = null;

    /**
     * Responses
     * @var array
     */
    protected $responses = null;

    /**
     * Scopes
     * @var array
     */
    protected $scope = null;

    /**
     * Rôle
     * @var string
     */
    protected $role = null;

    /**
     * Set uniq id
     *
     * @param string $p_id
     *
     * @return \FreeFW\Router\Route
     */
    public function setId($p_id)
    {
        $this->id = $p_id;
        return $this;
    }

    /**
     * Get uniq id
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set HTTP method
     *
     * @param string $p_method
     *
     * @return \FreeFW\Router\Route
     */
    public function setMethod($p_method)
    {
        $this->method = $p_method;
        return $this;
    }

    /**
     * Get method
     *
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Set url
     *
     * @param string $p_url
     *
     * @return \FreeFW\Router\Route
     */
    public function setUrl($p_url)
    {
        $this->url = $p_url;
        return $this;
    }

    /**
     * get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set Controller
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
     * Get controller
     *
     * @return string
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * Set function
     *
     * @param string $p_function
     *
     * @return \FreeFW\Router\Route
     */
    public function setFunction($p_function)
    {
        $this->function = $p_function;
        return $this;
    }

    /**
     * get function
     *
     * @return string
     */
    public function getFunction()
    {
        return $this->function;
    }

    /**
     * Set auth
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
     * Get auth
     *
     * @return string
     */
    public function getAuth()
    {
        return $this->auth;
    }

    /**
     * Set default model
     *
     * @param mixed $p_model
     *
     * @return \FreeFW\Router\Route
     */
    public function setDefaultModel($p_model)
    {
        $this->default_model = $p_model;
        return $this;
    }

    /**
     * Get default model
     *
     * @return mixed
     */
    public function getDefaultModel()
    {
        return $this->default_model;
    }

    /**
     * Set params
     *
     * @param array $p_params
     *
     * @return \FreeFW\Router\Route
     */
    public function setParams($p_params)
    {
        if (is_array($p_params)) {
            $this->params = $p_params;
        }
        return $this;
    }

    /**
     * Get Params
     *
     * @return array|boolean
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * Set include
     *
     * @param array $p_include
     *
     * @return \FreeFW\Router\Route
     */
    public function setInclude($p_include)
    {
        if (is_array($p_include)) {
            $this->include = $p_include;
        }
        return $this;
    }

    /**
     * Get include
     *
     * @return array
     */
    public function getInclude() : array
    {
        return $this->include;
    }

    /**
     * Set collection
     *
     * @param string $p_collection
     *
     * @return \FreeFW\Router\Route
     */
    public function setCollection($p_collection)
    {
        $this->collection = $p_collection;
        return $this;
    }

    /**
     * Get collection
     *
     * @return string
     */
    public function getCollection()
    {
        return $this->collection;
    }

    /**
     * Set comment
     *
     * @param string $p_comment
     *
     * @return \FreeFW\Router\Route
     */
    public function setComment($p_comment)
    {
        $this->comment = $p_comment;
        return $this;
    }

    /**
     * Get comment
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set responses
     *
     * @param array $p_responses
     *
     * @return \FreeFW\Router\Route
     */
    public function setResponses($p_responses)
    {
        $this->responses = $p_responses;
        return $this;
    }

    /**
     * Get responses
     *
     * @return array
     */
    public function getResponses()
    {
        return $this->responses;
    }

    /**
     * Set parameters
     *
     * @param array $p_parameters
     *
     * @return \FreeFW\Router\Route
     */
    public function setParameters($p_parameters)
    {
        $this->parameters = $p_parameters;
        return $this;
    }

    /**
     * Get parameters
     *
     * @return array
     */
    public function getParameters()
    {
        if (!is_array($this->parameters)) {
            $this->parameters = [];
        }
        return $this->parameters;
    }

    /**
     * Set scope
     *
     * @param mixed $p_scope
     *
     * @return \FreeFW\Router\Route
     */
    public function setScope($p_scope)
    {
        if (is_array($p_scope)) {
            $this->scope = $p_scope;
        } else {
            $this->scope = explode(',', $p_scope);
        }
        return $this;
    }

    /**
     * Get scope
     *
     * @return array
     */
    public function getScope()
    {
        if (!is_array($this->scope)) {
            $this->scope = [];
        }
        return $this->scope;
    }

    /**
     * Set role
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
     * Get role
     *
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Get route regexp
     *
     * @return string
     */
    public function getRegex()
    {
        return preg_replace_callback("/\/(:\w+)/", array(&$this, 'substituteFilter'), $this->url);
    }

    /**
     * Filters for regexp
     *
     * @return string
     */
    private function substituteFilter($matches)
    {
        if (isset($matches[1]) && isset($this->filters[$matches[1]])) {
            return $this->filters[$matches[1]];
        }
        return "([/]" . FFCST::PARAM_REGEX . ")";
    }

    /**
     * Render route
     *
     * @param \Psr\Http\Message\ServerRequestInterface $p_request
     */
    public function render(\Psr\Http\Message\ServerRequestInterface $p_request)
    {
        // Must go through middlewares....
        // The final is the route execution
        $defaultModel     = $this->getDefaultModel();
        $routerMiddleware = new \FreeFW\Middleware\Router(
            $this->controller,
            $this->function,
            $defaultModel,
            $this->params,
            $this->include
        );
        // Middleware pipeline
        $pipeline = new \FreeFW\Middleware\Pipeline();
        $pipeline->setAppConfig($this->getAppConfig());
        $pipeline->setLogger($this->logger);
        // Pipe default config middleware
        $midCfg  = $this->getAppConfig()->get('middleware');
        $authMid = false;
        if (is_array($midCfg)) {
            foreach ($midCfg as $idx => $middleware) {
                $newMiddleware = \FreeFW\DI\DI::get($middleware);
                if ($newMiddleware instanceof \FreeFW\Interfaces\AuthNegociatorInterface) {
                    $authMid = true;
                    switch ($this->getAuth()) {
                        case \FreeFW\Router\Route::AUTH_BOTH:
                            $newMiddleware->setSecured(true);
                            $newMiddleware->setIdentityGeneration(true);
                            break;
                        case \FreeFW\Router\Route::AUTH_IN:
                            $newMiddleware->setSecured(true);
                            break;
                        case \FreeFW\Router\Route::AUTH_OUT:
                            $newMiddleware->setIdentityGeneration(true);
                            break;
                        default:
                            break;
                    }
                }
                $pipeline->pipe($newMiddleware);
            }
        }
        // Inject route middlewares...
        // Check ...
        if ($this->getAuth() !== \FreeFW\Router\Route::AUTH_NONE && ! $authMid) {
            throw new \FreeFW\Core\FreeFWException('Secured route with no Auth middleware !');
        }
        // Last middleware is router
//        var_dump($routerMiddleware->getRouteIncludes());
        $pipeline->pipe($routerMiddleware);
        // Go
        return $pipeline->handle($p_request);
    }
}
