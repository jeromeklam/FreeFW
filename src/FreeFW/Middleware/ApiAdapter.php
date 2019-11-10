<?php
namespace FreeFW\Middleware;

use \Psr\Http\Server\MiddlewareInterface;
use \Psr\Http\Server\RequestHandlerInterface;
use \Psr\Http\Message\ServerRequestInterface;
use \Psr\Http\Message\ResponseInterface;

/**
 *
 * @author jeromeklam
 *
 */
abstract class ApiAdapter implements
    MiddlewareInterface,
    \Psr\Log\LoggerAwareInterface,
    \FreeFW\Interfaces\ConfigAwareTraitInterface,
    \FreeFW\Interfaces\ApiAdapterInterface
{

    /**
     * comportements
     */
    use \Psr\Log\LoggerAwareTrait;
    use \FreeFW\Behaviour\EventManagerAwareTrait;
    use \FreeFW\Behaviour\ConfigAwareTrait;
    use \FreeFW\Behaviour\HttpFactoryTrait;

    /**
     * Allowed types
     * @var array
     */
    protected $contentTypes = [];

    /**
     * Can override type ?
     * @var bool
     */
    protected $override = false;

    /**
     * Accepted methods
     * @var string[]
     */
    protected $methods = ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'COPY', 'LOCK', 'UNLOCK'];

    /**
     * Set content types
     *
     * @param array $p_types
     *
     * @return \FreeFW\Middleware\ApiAdapter
     */
    public function setContentTypes(array $p_types)
    {
        $this->contentTypes = $p_types;
        return $this;
    }

    /**
     * Set methods
     *
     * @param array $p_methods
     *
     * @return \FreeFW\Middleware\ApiAdapter
     */
    public function setMethods(array $p_methods)
    {
        $this->methods = $p_methods;
        return $this;
    }

    /**
     * Set override
     *
     * @param bool $p_override
     *
     * @return \FreeFW\Middleware\ApiAdapter
     */
    public function setOverride(bool $p_override = true) : self
    {
        $this->override = $p_override;
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
        try {
            if ($this->checkRequest($p_request)) {
                return $this->encodeResponse(
                    $p_handler->handle(
                        $this->decodeRequest($p_request)
                    )
                );
            } else {
                if ($this->override) {
                    return $p_handler->handle($p_request);
                }
            }
        } catch (\Exception $ex) {
            return $this->createErrorResponse($ex);
        }
        return $this->createUnsupportedRequestResponse();
    }

    /**
     *
     * {@inheritDoc}
     * @see \FreeFW\Interfaces\ApiAdapterInterface::canOverride()
     */
    public function canOverride() : bool
    {
        return $this->override;
    }

    /**
     *
     * {@inheritDoc}
     * @see \FreeFW\Interfaces\ApiAdapterInterface::checkRequest()
     */
    public function checkRequest(ServerRequestInterface $p_request) : bool
    {
        $method = $p_request->getMethod();
        if (!in_array($method, $this->methods, true)) {
            return false;
        }
        $contentType = $p_request->getHeaderLine('Content-Type');
        foreach ($this->contentTypes as $allowedType) {
            if (stripos($contentType, $allowedType) === 0) {
                return true;
            }
        }
        return false;
    }
}
