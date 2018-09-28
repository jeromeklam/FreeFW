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
class Broker extends \FreeFW\Middleware\Base implements MiddlewareInterface
{

    /**
     * Behaviour
     */
    use \FreeFW\Behaviour\DI;
    use \FreeFW\Behaviour\LoggerAwareTrait;

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
            $ssoConfig = $di->getConfig()->get('sso');
            $ssoServer = \FreeFW\Sso\Server::getInstance($ssoConfig);
            $di->setShared('sso', $ssoServer);
        }
        $cfg = $di->getConfig();
        // Je commence par injecter les config.
        $brokerCfg  = $ssoServer->getConfiguration();
        if (array_key_exists('email.provider', $brokerCfg)) {
            $provider = $brokerCfg['email.provider'];
            // smtp://user:password@server:port
            $parts0 = explode('://', $provider);
            if (count($parts0) == 2) {
                $parts1 = explode('@', $parts0[1]);
                if (count($parts1) == 2) {
                    $parts2 = explode(':', $parts1[0]);
                    $parts3 = explode(':', $parts1[1]);
                    $cfg->set('mailer:mode', $parts0[0]);
                    $cfg->set('mailer:server', $parts3[0]);
                    $cfg->set('mailer:port', $parts3[1]);
                    $cfg->set('mailer:username', $parts2[0]);
                    $cfg->set('mailer:password', $parts2[1]);
                }
            }
        }
        if (array_key_exists('email.fromemail', $brokerCfg)) {
            $cfg->set('mailer:from_email', $brokerCfg['email.fromemail']);
        }
        if (array_key_exists('email.fromname', $brokerCfg)) {
            $cfg->set('mailer:from_name', $brokerCfg['email.fromname']);
        }
        if (array_key_exists('sms.provider', $brokerCfg)) {
            $provider = $brokerCfg['sms.provider'];
            // novadys://user:password@endpoint:port
            $parts0 = explode('://', $provider);
            if (count($parts0) == 2) {
                $parts1 = explode('@', $parts0[1]);
                if (count($parts1) == 2) {
                    $parts2 = explode(':', urldecode($parts1[0]));
                    $parts3 = explode(':', urldecode($parts1[1]));
                    $cfg->set('sms:provider', $parts0[0]);
                    $endpoint = $parts3[0];
                    if ($parts3[1] == '443') {
                        $endpoint = 'https://' . $endpoint;
                    } else {
                        if ($parts3[1] == '443') {
                            $endpoint = 'http://' . $endpoint;
                        } else {
                            $endpoint = 'http://' . $endpoint . ':' . $parts3[1];
                        }
                    }
                    $cfg->set('sms:endpoint', $endpoint);
                    $cfg->set('sms:username', $parts2[0]);
                    $cfg->set('sms:password', $parts2[1]);
                }
            }
            if (array_key_exists('sms.origin', $brokerCfg)) {
                $cfg->set('sms:sms_origin', $brokerCfg['sms.origin']);
            }
            if (array_key_exists('sms.sender', $brokerCfg)) {
                $cfg->set('sms:sms_sender', $brokerCfg['sms.sender']);
            }
            if (array_key_exists('cim.component', $brokerCfg)) {
                $cfg->set('sms:key1', $brokerCfg['cim.component']);
            }
            if (array_key_exists('cim.customer', $brokerCfg)) {
                $cfg->set('sms:key2', $brokerCfg['cim.customer']);
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
            $sso = $this->beforeProcess($request);
            if ($sso !== false) {
                $request  = $request->withAttribute('broker', $sso->getIdentifier());
                $response = $handler->handle($request);
                $appId    = \FreeFW\Sso\Http\Remote::getApplicationCookie();
                $ssoId    = \FreeFW\Sso\Http\Remote::getSSOCookie();
                $response = $response->withHeader('AppId', $appId);
                $response = $response->withHeader('SsoId', $ssoId);
            } else {
                self::debug('broker.401');
                $response = $this->createResponse(401);
            }
            // response must be converted to correct type
            return $response;
        } catch (\Exception $ex) {
            // @todo : critical
            return $this->createResponse(500);
        }
    }
}
