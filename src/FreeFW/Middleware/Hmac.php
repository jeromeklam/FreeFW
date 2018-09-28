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
class Hmac extends \FreeFW\Middleware\Base implements MiddlewareInterface
{

    /**
     * before Middleware
     *
     * @param ServerRequestInterface $request
     * @param String                 $p_api_id
     *
     * @retuen \FreeFW\Sso\Server
     */
    protected function beforeProcess(ServerRequestInterface $request, $p_api_id)
    {
        $di        = \FreeFW\ResourceDi::getInstance();
        $ssoServer = $di->getShared('sso');
        if ($ssoServer === null || $ssoServer === false) {
            $ssoConfig = $di->getConfig()->get('sso');
            $brk_key   = null;
            $brk_key   = $p_api_id;
            if (is_array($brk_key)) {
                $brk_key = $brk_key[0];
            }
            $ssoConfig['broker-key'] = $brk_key;
            $ssoServer               = \FreeFW\Sso\Server::getInstance($ssoConfig);
            $di->setShared('sso', $ssoServer);
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
        $api_id = false;
        if ($request->hasHeader('ApiId')) {
            $api_id = $request->getHeader('ApiId');
        } else {
            if ($request->hasHeader('Api-Id')) {
                $api_id = $request->getHeader('Api-Id');
            } else {
                if ($request->hasHeader('API_ID')) {
                    $api_id = $request->getHeader('API_ID');
                }
            }
        }
        if ($api_id) {
            try {
                $sso      = $this->beforeProcess($request, $api_id);
                $response = $handler->handle($request);
                // response must be converted to correct type
                return $response;
            } catch (\Exception $ex) {
                return $this->createResponse(412);
            }
        } else {
            return $this->createResponse(401);
        }
    }
}
