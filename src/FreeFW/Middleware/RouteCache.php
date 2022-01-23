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
class RouteCache implements
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
    use \FreeFW\Behaviour\HttpFactoryTrait;

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
        /**
         * @var \FreeFW\http\ApiParams $apiParams
         */
        $apiParams = $p_request->getAttribute('api_params', false);
        /**
         * @var \FreeFW\Router\Route $route
         */
        $route    = $p_request->getAttribute('route');
        $routeId  = $route->getId();
        $cacheKey = $routeId . '.' . $apiParams->getUniqId();
        $this->logger->info('FreeFW.Middleware.RouteCache : ' . $cacheKey);
        /**
         * @var \Psr\Cache\CacheItemPoolInterface $cache
         */
        $cache   = \FreeFW\DI\DI::getShared('cache');
        if ($cache && $cache->hasItem($cacheKey)) {
            $item = $cache->getItem($cacheKey);
            if ($item && $item->isHit()) {
                $this->logger->info('FreeFW.Middleware.RouteCache.cached');
                $cacheEntry = $item->get();
                $response = $this->createResponse($cacheEntry['status']);
                $body = \GuzzleHttp\Psr7\Utils::streamFor($cacheEntry['body']);
                return $response->withBody($body);
            }
        }
        $this->logger->info('FreeFW.Middleware.RouteCache.end');
        $response = $p_handler->handle($p_request);
        if ($cache) {
            $responseBody = (string)$response->getBody();
            $response->getBody()->rewind();
            $cacheEntry = [
                'status' => $response->getStatusCode(),
                'headers' => $response->getHeaders(),
                'body' => $responseBody
            ];
            $item = new \FreeFW\Cache\Item($cacheKey);
            $item->set($cacheEntry);
            $cache->save($item);
        }
        return $response;
    }
}
