<?php
namespace FreeFW\Middleware;

use \Psr\Http\Server\MiddlewareInterface;
use \Psr\Http\Server\RequestHandlerInterface;
use \Psr\Http\Message\ResponseFactoryInterface;
use \Psr\Http\Message\ServerRequestInterface;
use \Psr\Http\Message\ResponseInterface;
use \FreeFW\Http\Response;
use \FreeFW\ResourceDi;
use \FreeFW\Auth\Hawk\HeaderParameters;

/**
 *
 * @author jerome.klam
 *
 */
class Hawk extends \FreeFW\Middleware\Base implements MiddlewareInterface
{

    /**
     * before Middleware
     *
     * @param ServerRequestInterface $request
     */
    protected function beforeProcess(ServerRequestInterface $request)
    {
        $di        = \FreeFW\ResourceDi::getInstance();
        $ssoServer = $di->getShared('sso');
        if ($ssoServer === null || $ssoServer === false) {
            if ($request->hasHeader('authorization')) {
                $ssoConfig               = $di->getConfig()->get('sso');
                $headers                 = HeaderParameters::getInstance($request);
                $brk_key                 = $headers->getApplicationId('id');
                $ssoConfig['broker-key'] = $brk_key;
                $ssoServer               = \FreeFW\Sso\Server::getInstance($ssoConfig);
                $di->setShared('sso', $ssoServer);
            }
        }
        return $ssoServer;
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
        try {
            $sso      = $this->beforeProcess($request);
            $response = $handler->handle($request);
            // response must be converted to correct type
            return $response;
        } catch (\Exception $ex) {
            return $this->createResponse(500);
        }
    }
}
