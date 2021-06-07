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
class RouteHandler implements
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
     * Get route includes
     *
     * @return array
     */
    public function getRouteIncludes()
    {
        return $this->include;
    }

    /**
     * Check route and get route properties
     *
     * @param ServerRequestInterface $p_request
     *
     * @return bool
     */
    public function beforeProcess(\FreeFW\Router\Route $p_route)
    {
        $this->model      = $p_route->getDefaultModel();
        $this->controller = $p_route->getController();
        $this->function   = $p_route->getFunction();
        $this->params     = $p_route->getParams();
        $this->include    = $p_route->getInclude();
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
        $apiParams = $p_request->getAttribute('api_params', false);
        $route     = $p_request->getAttribute('route');
        $this->logger->info('FreeFW.Middleware.RouteHandler : ' . $this->model);
        if ($route instanceof \FreeFW\Router\Route) {
            $this->beforeProcess($route);
            $p_request->default_model = $this->model;
            $object                   = \FreeFW\DI\DI::get($this->controller);
            if ($apiParams instanceof \FreeFW\Http\ApiParams) {
                $this->setInclude($apiParams);
                $p_request = $p_request->withAttribute('api_params', $apiParams);
            }
            $this->logger->info('FreeFW.Middleware.RouteHandler.call ' . $this->controller . '.' . $this->function);
            $response = call_user_func_array([$object, $this->function], array_merge([$p_request], $this->params));
        } else {
            $this->logger->info('FreeFW.Middleware.RouteHandler.noRoute');
            $response = $this->createResponse(412, "No route or wrong config !");
        }
        return $response;
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
        if (array_key_exists(FFCSTRT::ROUTE_INCLUDE_REQUIRED,$this->include)) {
            $includes = array_merge(
                $includes,
                $p_apiParams->renderInclude($this->include[FFCSTRT::ROUTE_INCLUDE_REQUIRED])
            );
        }
        $p_apiParams->setInclude($includes);
    }
}
