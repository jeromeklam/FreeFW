<?php
namespace FreeFW\Middleware;

use \Psr\Http\Server\MiddlewareInterface;
use \Psr\Http\Server\RequestHandlerInterface;
use \Psr\Http\Message\ServerRequestInterface;
use \Psr\Http\Message\ResponseInterface;

/**
 * Auth negociator
 *
 * @author jeromeklam
 */
class AuthNegociator implements
    MiddlewareInterface,
    \Psr\Log\LoggerAwareInterface,
    \FreeFW\Interfaces\ConfigAwareTraitInterface,
    \FreeFW\Interfaces\AuthAdapterInterface
{

    /**
     * Behaviour
     */
    use \Psr\Log\LoggerAwareTrait;
    use \FreeFW\Behaviour\EventManagerAwareTrait;
    use \FreeFW\Behaviour\ConfigAwareTrait;
    use \FreeFW\Behaviour\HttpFactoryTrait;

    /**
     * Formats
     * @var array
     */
    protected $formats = [
        'application/vnd.api+json' => [
            'class'   => 'FreeFW::Middleware::JsonApi',
            'default' => true
        ]
    ];

    /**
     * Secured ?
     * @var bool
     */
    protected $secured = false;

    /**
     * Constructor
     *
     * @param array $p_formats
     */
    public function __construct(array $p_formats = [])
    {
        if (count($p_formats) > 0) {
            $this->formats = $p_formats;
        }
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
        $allowed = true;
        if ($this->secured) {
            $allowed = false;
        }
        if (!$allowed) {
            return $this->createResponse(401);
        }
        return $p_handler->handle($p_request);
    }

    /**
     *
     * {@inheritDoc}
     * @see \FreeFW\Interfaces\AuthAdapterInterface::isSecured()
     */
    public function isSecured(): bool
    {
        return $this->secured;
    }

    /**
     *
     * {@inheritDoc}
     * @see \FreeFW\Interfaces\AuthAdapterInterface::setSecured()
     */
    public function setSecured(bool $p_secured = true)
    {
        $this->secured = $p_secured;
        return $this;
    }
}
