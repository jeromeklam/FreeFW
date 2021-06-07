<?php
namespace FreeFW\Application;

use GuzzleHttp;

/**
 * Application application
 *
 * @author jeromeklam
 */
class Application extends \FreeFW\Core\Application
{

    /**
     * Behaviour
     */
    use \FreeFW\Behaviour\HttpFactoryTrait;

    /**
     * Application instance
     * @var \FreeFW\Application\Application
     */
    protected static $instance = null;

    /**
     * Constructor
     *
     * @param \FreeFW\Application\Config $p_config
     */
    protected function __construct(
        \FreeFW\Application\Config $p_config,
        \Psr\Log\LoggerInterface $p_logger
    ) {
        parent::__construct($p_config, $p_logger);
        \FreeFW\DI\DI::setShared('config', $p_config);
        \FreeFW\DI\DI::setShared('logger', $p_logger);
    }

    /**
     * Get Application instance
     *
     * @param \FreeFW\Application\Config $p_config
     *
     * @return \FreeFW\Application\Application
     */
    public static function getInstance(
        \FreeFW\Application\Config $p_config,
        \Psr\Log\LoggerInterface $p_logger
    ) {
        if (self::$instance === null) {
            self::$instance = new static($p_config, $p_logger);
        }
        return self::$instance;
    }

    /**
     * Send an HTTP response
     *
     * @return void
     */
    protected function send(\Psr\Http\Message\ResponseInterface $p_response)
    {
        $http_line = sprintf(
            'HTTP/%s %s %s',
            $p_response->getProtocolVersion(),
            $p_response->getStatusCode(),
            $p_response->getReasonPhrase()
        );
        header($http_line, true, $p_response->getStatusCode());
        foreach ($p_response->getHeaders() as $name => $values) {
            foreach ($values as $value) {
                header("$name: $value", false);
            }
        }
        $stream = $p_response->getBody();
        if ($stream->isSeekable()) {
            $stream->rewind();
        }
        while (!$stream->eof()) {
            echo $stream->read(1024 * 8);
        }
    }

    /**
     * Send route for http code
     *
     * @param mixed $p_http_code
     */
    public function sendHttpCode($p_http_code)
    {
        $this->logger->debug('Application.sendHttpCode.start');
        try {
            $request     = \FreeFW\Http\ApiServerRequest::fromGlobals();
            $this->route = $this->router->findSpecificRoute($p_http_code);
            if ($this->route) {
                $this->route->setLogger($this->logger);
                $this->route->setAppConfig($this->getAppConfig());
                // Prepare Middleware pipeline
                $pipeline = new \FreeFW\Middleware\Pipeline();
                $pipeline->setAppConfig($this->getAppConfig());
                $pipeline->setLogger($this->logger);
                // Pipe default config middleware
                $midCfg  = $this->getAppConfig()->get('middleware');
                $authMid = false;
                if (is_array($midCfg)) {
                    foreach ($midCfg as $middleware) {
                        $newMiddleware = \FreeFW\DI\DI::get($middleware);
                        $pipeline->pipe($newMiddleware);
                        if ($newMiddleware instanceof \FreeFW\Interfaces\AuthNegociatorInterface) {
                            $authMid = true;
                        }
                    }
                }
                // Check ...
                if ($this->route->getAuth() !== \FreeFW\Router\Route::AUTH_NONE && ! $authMid) {
                    throw new \FreeFW\Core\FreeFWException('Secured route with no Auth middleware !');
                }
                // Render
                $request  = $request->withAttribute('route', $this->route);
                $response = $pipeline->handle($request);
                $this->send($response);
            } else {
                // @todo
                $response = $this->createResponse(404, 'Not found');
                $this->send($response);
            }
            $this->afterRender();
        } catch (\Exception $ex) {
            // @todo : handle 500 response
            $response = $this->createResponse(500, $ex->getMessage());
            $this->send($response);
        }
        $this->logger->debug('Application.sendHttpCode.end');
    }

    /**
     * Handle request
     */
    public function handle()
    {
        $this->logger->info('FreeFW.Application.handle.start');
        try {
            /**
             * @var \FreeFW\Http\ApiServerRequest $request
             */
            $request     = \FreeFW\Http\ApiServerRequest::fromGlobals();
            $response    = null;
            $processed   = false;
            $this->route = $this->router->findRoute($request);
            while (!$processed) {
                $processed = true;
                if ($this->route) {
                    $this->route->setLogger($this->logger);
                    $this->route->setAppConfig($this->getAppConfig());
                    // Prepare Middleware pipeline
                    $pipeline = new \FreeFW\Middleware\Pipeline();
                    $pipeline->setAppConfig($this->getAppConfig());
                    $pipeline->setLogger($this->logger);
                    // Pipe default config middleware
                    $midCfg  = $this->getAppConfig()->get('middleware');
                    $authMid = false;
                    if (is_array($midCfg)) {
                        foreach ($midCfg as $middleware) {
                            $newMiddleware = \FreeFW\DI\DI::get($middleware);
                            if ($newMiddleware instanceof \FreeFW\Interfaces\AuthNegociatorInterface) {
                                $authMid = true;
                            } else {
                                if ($newMiddleware instanceof \FreeFW\Middleware\RouteHandler) {
                                    // Inject route middlewares
                                    $routeMid = $this->route->getMiddleware();
                                    if (is_array($routeMid)) {
                                        foreach ($routeMid as $middlewareR) {
                                            $newMiddlewareR = \FreeFW\DI\DI::get($middlewareR);
                                            $pipeline->pipe($newMiddlewareR);
                                        }
                                    }
                                }
                            }
                            $pipeline->pipe($newMiddleware);
                        }
                    }
                    // Check ...
                    if ($this->route->getAuth() !== \FreeFW\Router\Route::AUTH_NONE && ! $authMid) {
                        throw new \FreeFW\Core\FreeFWException('Secured route with no Auth middleware !');
                    }
                    // Render
                    $request  = $request->withAttribute('route', $this->route);
                    $response = $pipeline->handle($request);
                    if ($response instanceof \FreeFW\Http\RedirectResponse) {
                        $processed = false;
                        $newRoute  = $this->router->findRouteByModelAndRole(
                            str_replace('_', '::Model::', $response->getModel()),
                            $response->getRole()
                        );
                        $newRoute->setParams($response->getParams());
                        $this->route = $newRoute;
                        $apiParams   = new \FreeFW\Http\ApiParams();
                        $request     = $request->withAttribute('api_params', $apiParams);
                    }
                } else {
                    $this->fireEvent(\FreeFW\Constants::EVENT_ROUTE_NOT_FOUND);
                    $response = null;
                }
            }
            if ($response) {
                $this->send($response);
            }
            $this->afterRender();
        } catch (\Exception $ex) {
            // @todo : handle 500 response
            $response = $this->createResponse(500, $ex->getMessage());
            $this->send($response);
        }
        $this->logger->info('FreeFW.Application.handle.end');
    }
}
