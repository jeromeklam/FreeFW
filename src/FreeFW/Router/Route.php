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
     * Methos constants
     * @var string
     */
    const METHOD_GET    = 'get';
    const METHOD_POST   = 'post';
    const METHOD_UPDATE = 'update';
    const METHOD_DELETE = 'delete';

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
     * @var bool
     */
    protected $secured = false;

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
     * Set secured
     *
     * @param bool $p_secured
     *
     * @return \FreeFW\Router\Route
     */
    public function setSecured($p_secured)
    {
        $this->secured = $p_secured;
        return $this;
    }

    /**
     * Get secured
     *
     * @return bool
     */
    public function getSecured()
    {
        return $this->secured;
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
            $routerMiddleware = new \FreeFW\Middleware\Router($cls, $this->function);
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
                        $newMiddleware->setSecured($this->getSecured());
                    }
                    $pipeline->pipe($newMiddleware);
                }
            }
            // Inject route middlewares...
            // Check ...
            if ($this->getSecured() && ! $authMid) {
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
