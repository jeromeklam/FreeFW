<?php
namespace FreeFW\Middleware;

use \Psr\Http\Server\MiddlewareInterface;
use \Psr\Http\Server\RequestHandlerInterface;
use \Psr\Http\Message\ResponseFactoryInterface;
use \Psr\Http\Message\ServerRequestInterface;
use \Psr\Http\Message\ResponseInterface;
use \FreeFW\Http\Response;
use \FreeFW\ResourceDi;

/**
 *
 * @author jerome.klam
 *
 */
class Router extends \FreeFW\Middleware\Base implements MiddlewareInterface
{

    /**
     * Behaviour
     */
    use \FreeFW\Behaviour\LoggerAwareTrait;

    /**
     *
     * @param ServerRequestInterface $request
     *
     * @return \FreeFW\Router\Route
     */
    protected function beforeProcess(ServerRequestInterface $request)
    {
        $di     = \FreeFW\ResourceDi::getInstance();
        $router = $di->getShared('router');
        return $router->matchCurrentRequest($request);
    }

    /**
     * Process an incoming server request and return a response, optionally delegating
     * to the next middleware component to create the response.
     *
     * @param ServerRequestInterface  $request
     * @param RequestHandlerInterface $handler
     *
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $route = $this->beforeProcess($request);
        if ($route !== false) {
            $request = $request->withAttribute('api_version', $route->getVersion());
            $request = $request->withAttribute('route', $route);
            // Gestion des valeurs par défaut, paramètres obligatoires de la requête
            $routeParams = $route->getParams();
            $extended    = [];
            foreach ($routeParams as $idx => $oneParam) {
                if (($val = $request->getAttribute($oneParam->getName(), null)) === null) {
                    if ($oneParam->isRequired()) {
                        if ($oneParam->getDefault() !== null) {
                            $request = $request->withAttribute($oneParam->getName(), $oneParam->getDefault());
                        } else {
                            self::info(sprintf('middleware.route.required : %s', $idx));
                            return $this->createResponse(400);
                        }
                    } else {
                        if ($oneParam->getDefault() !== null) {
                            $request = $request->withAttribute($oneParam->getName(), $oneParam->getDefault());
                        }
                    }
                } else {
                    // cast...
                    switch ($oneParam->getType()) {
                        case \FreeFW\Router\Param::TYPE_BOOLEAN:
                            if ($val == '1' || $val == true || strtoupper($val) == 'O' ||
                                strtoupper($val) == 'Y') {
                                $request = $request->withAttribute($oneParam->getName(), true);
                            } else {
                                $request = $request->withAttribute($oneParam->getName(), false);
                            }
                            break;
                    }
                }
                if ($oneParam->getType() == \FreeFW\Router\Param::TYPE_FILTER) {
                    $extended = $oneParam->getExtended();
                    if (is_array($extended)) {
                        $filters = $request->getAttribute($oneParam->getName(), []);
                        foreach ($extended as $idx2 => $oneParam2) {
                            if (!array_key_exists($idx2, $filters)) {
                                if ($oneParam2->isRequired()) {
                                    if ($oneParam2->getDefault() !== null) {
                                        $filters[$idx2] = $oneParam2->getDefault();
                                    } else {
                                        self::info(sprintf('middleware.route.required : %s', $idx2));
                                        return $this->createResponse(400);
                                    }
                                } else {
                                    if ($oneParam2->getDefault() !== null) {
                                        $filters[$idx2] = $oneParam2->getDefault();
                                    }
                                }
                            }
                        }
                        $request = $request->withAttribute($oneParam->getName(), $filters);
                    }
                }
            }
            // Tout paramètre uri/query doit-être décrit...
            foreach ($request->getQueryParams() as $idx => $param) {
                if (!in_array($idx, array('_request'))) {
                    if (!array_key_exists($idx, $routeParams)) {
                        self::info(sprintf('middleware.route.not-found : %s', $idx));
                        return $this->createResponse(400);
                    }
                }
            }
            // Suite
            self::debug(sprintf('request.uri : %s', $request->getUri()));
            self::info(sprintf('middleware.route : %s', $route));
            try {
                $cls = $route->getControllerClass();
                // @todo verify, ...
                $handler
                    ->addMiddlewareByKey($route->getType())
                    ->addMiddlewareByKey($route->getAuth())
                    ->addMiddlewareByKey($route->getMiddlewares())
                    ->setVersion($route->getVersion())
                    ->setFunction(
                        new $cls(),
                        $route->getFunctionName(),
                        $route->getParameters()
                    )
                ;
                $response = $handler->handle($request);
            } catch (\Exception $ex) {
                self::error($ex->getMessage());
                $response = $this->createResponse(500, $ex->getMessage());
            }
        } else {
            $response = $this->createResponse(404);
        }
        // response must be converted to correct type
        return $response;
    }
}
