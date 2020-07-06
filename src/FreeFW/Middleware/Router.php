<?php
namespace FreeFW\Middleware;

use \Psr\Http\Server\MiddlewareInterface;
use \Psr\Http\Server\RequestHandlerInterface;
use \Psr\Http\Message\ResponseFactoryInterface;
use \Psr\Http\Message\ServerRequestInterface;
use \Psr\Http\Message\ResponseInterface;
use \FreeFW\Router\Route as FFCSTRT;

/**
 *
 * @author jerome.klam
 *
 */
class Router implements
    MiddlewareInterface,
    \Psr\Log\LoggerAwareInterface,
    \FreeFW\Interfaces\ConfigAwareTraitInterface
{

    /**
     * comportements
     */
    use \Psr\Log\LoggerAwareTrait;
    use \FreeFW\Behaviour\EventManagerAwareTrait;
    use \FreeFW\Behaviour\ConfigAwareTrait;

    /**
     * Controller
     * @var string
     */
    protected $controller = null;

    /**
     * Function
     * @var string
     */
    protected $function = null;

    /**
     * default model
     * @var string
     */
    protected $model = null;

    /**
     * Include
     * @var array
     */
    protected $include = [];

    /**
     * Params
     * @var array
     */
    protected $params = [];

    /**
     * Constructor
     *
     * @param string $p_controller
     * @param string $p_function
     * @param string $p_model
     * @param array  $p_params
     * @param array  $p_include
     */
    public function __construct($p_controller, $p_function, $p_model = null, $p_params = [], $p_include = [])
    {
        $this->controller = $p_controller;
        $this->function   = $p_function;
        $this->model      = $p_model;
        $this->params     = [];
        $this->include    = [];
        if (is_array($p_params)) {
            $this->params = $p_params;
        }
        if (is_array($p_include)) {
            $this->include = $p_include;
        }
    }

    public function getRouteIncludes()
    {
        return $this->include;
    }
    /**
     * Process an incoming server request and return a response, optionally delegating
     * to the next middleware component to create the response.
     *
     * @param ServerRequestInterface  $p_request
     * @param RequestHandlerInterface $p_handler
     *
     * @return ResponseInterface
     */
    public function process(
        ServerRequestInterface $p_request,
        RequestHandlerInterface $p_handler
    ): ResponseInterface {
        $p_request->default_model = $this->model;
        $object                   = \FreeFW\DI\DI::get($this->controller);
        $apiParams                = $p_request->getAttribute('api_params', false);
        if ($apiParams instanceof \FreeFW\Http\ApiParams) {
//          if (array_key_exists('limit', $this->include)) {
                // @todo, no mode than limit...
//          }
            $this->setInclude($apiParams);
            var_dump($apiParams->getInclude());
            $p_request = $p_request->withAttribute('api_params', $apiParams);
        }
        return call_user_func_array([$object, $this->function], array_merge([$p_request], $this->params));
    }

    /**
     * Construit l'include d'apiParams
     *
     * @param \FreeFW\Http\ApiParams $p_apiParams
     * @return \FreeFW\Http\ApiParams
     */
    protected function setInclude($p_apiParams)
    {
        $includes = $p_apiParams->getInclude();

        if (count($includes) == 0 && array_key_exists(FFCSTRT::ROUTE_INCLUDE_DEFAULT, $this->include)) {
            $includes = $p_apiParams->renderInclude($this->include[FFCSTRT::ROUTE_INCLUDE_DEFAULT]);
        }

        if (array_key_exists(FFCSTRT::ROUTE_INCLUDE_LIST,$this->include)) {
            if (count($p_apiParams->renderInclude($this->include[FFCSTRT::ROUTE_INCLUDE_LIST])) > 0) {
                $includes = array_intersect(
                                $includes,
                                $p_apiParams->renderInclude($this->include[FFCSTRT::ROUTE_INCLUDE_LIST])
                            );
            }
        }

        if (array_key_exists(FFCSTRT::ROUTE_INCLUDE_REQJIRED,$this->include)) {
            $includes = array_merge(
                            $includes,
                            $p_apiParams->renderInclude($this->include[FFCSTRT::ROUTE_INCLUDE_REQJIRED])
                        );
        }

        $p_apiParams->setInclude($includes);
    }
}
