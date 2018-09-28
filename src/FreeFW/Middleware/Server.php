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
class Server extends \FreeFW\Middleware\Base implements MiddlewareInterface
{

    /**
     * Behaviour
     */
    use \FreeFW\Behaviour\DI;
    use \FreeFW\Behaviour\LoggerAwareTrait;

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
        $di        = ResourceDi::getInstance();
        $ssoServer = $di->getShared('sso');
        if (!$ssoServer->verifyApiScope('server')) {
            self::debug('server.401');
            return $this->createResponse(401);
        }
        $response = $handler->handle($request);
        $appId    = \FreeFW\Sso\Http\Remote::getApplicationCookie();
        $ssoId    = \FreeFW\Sso\Http\Remote::getSSOCookie();
        $response = $response->withHeader('AppId', $appId);
        $response = $response->withHeader('SsoId', $ssoId);
        // response must be converted to correct type
        return $response;
    }
}
