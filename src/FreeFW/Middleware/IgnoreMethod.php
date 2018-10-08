<?php
namespace FreeFW\Middleware;

use \Psr\Http\Server\MiddlewareInterface;
use \Psr\Http\Server\RequestHandlerInterface;
use \Psr\Http\Message\ResponseFactoryInterface;
use \Psr\Http\Message\ServerRequestInterface;
use \Psr\Http\Message\ResponseInterface;

/**
 *
 * @author jerome.klam
 *
 */
class IgnoreMethod implements
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
     * Metods to ignore
     * @var array
     */
    protected $ignoreHttpMethods = ['HEAD', 'OPTIONS'];

    /**
     * Set ignored methods
     *
     * @param array $p_methods
     *
     * @return \FreeFW\Middleware\IgnoreMethod
     */
    public function setIgnoredHttpMethods(array $p_methods)
    {
        $this->ignoreHttpMethods = $p_methods;
        return $this;
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
        $method = $p_request->getMethod();
        if (!in_array($method, $this->ignoreHttpMethods, true)) {
            return $p_handler->handle($p_request);
        }
        return $this->createResponse(405, []);
    }
}
