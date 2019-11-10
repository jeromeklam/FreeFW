<?php
namespace FreeFW\Router;

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
     * Methods constants
     * @var string
     */
    const METHOD_GET    = 'get';
    const METHOD_POST   = 'post';
    const METHOD_UPDATE = 'update';
    const METHOD_DELETE = 'delete';

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
    const RESULT_LIST = 'list';

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
    protected $contoller = null;

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
        $this->contoller = $p_controller;
        return $this;
    }

    /**
     * Get controller
     *
     * @return string
     */
    public function getController()
    {
        return $this->contoller;
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
        return "([/][\w-\._@%]*)";
    }

    /**
     * Render route
     *
     * @param \Psr\Http\Message\ServerRequestInterface $p_request
     */
    public function render(\Psr\Http\Message\ServerRequestInterface $p_request)
    {
        $cls = \FreeFW\DI\DI::get($this->contoller);
        if (method_exists($cls, $this->function)) {
            // Must go through middlewares....
            // The final is the route execution
            $defaultModel     = $this->getDefaultModel();
            $routerMiddleware = new \FreeFW\Middleware\Router(
                $cls,
                $this->function,
                $defaultModel
            );
            // Middleware pipeline
            $pipeline = new \FreeFW\Middleware\Pipeline();
            $pipeline->setConfig($this->config);
            $pipeline->setLogger($this->logger);
            // Pipe default config middleware
            $midCfg  = $this->config->get('middleware');
            $authMid = false;
            if (is_array($midCfg)) {
                foreach ($midCfg as $idx => $middleware) {
                    $newMiddleware = \FreeFW\DI\DI::get($middleware);
                    if ($newMiddleware instanceof \FreeFW\Interfaces\AuthAdapterInterface) {
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
            $pipeline->pipe($routerMiddleware);
            // Go
            return $pipeline->handle($p_request);
        } else {
            throw new \FreeFW\Core\FreeFWException(
                sprintf('Function %s not found in %s class !', $this->function, $this->$contoller)
            );
        }
        return false;
    }
}
