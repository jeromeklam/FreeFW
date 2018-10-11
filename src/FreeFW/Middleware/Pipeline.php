<?php
namespace FreeFW\Middleware;

use \Psr\Http\Message\ResponseInterface;
use \Psr\Http\Message\ServerRequestInterface;
use \Psr\Http\Server\MiddlewareInterface;
use \Psr\Http\Server\RequestHandlerInterface;

/**
 * Middleware pipeline
 *
 * @author jeromeklam
 */
class Pipeline implements
    RequestHandlerInterface,
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
     * Middlewares
     * @var \SplQueue
     */
    protected $middlewares = null;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->middlewares = new \SplQueue();
    }

    /**
     * Perform a deep clone.
     */
    public function __clone()
    {
        $this->middlewares = clone $this->middlewares;
    }

    /**
     * Add new middleware to pipeline
     *
     * @param \Psr\Http\Server\MiddlewareInterface $p_middleware
     *
     * @return \FreeFW\Middleware\Pipeline
     */
    public function pipe(MiddlewareInterface $p_middleware)
    {
        $this->middlewares->enqueue($p_middleware);
        return $this;
    }

    /**
     * Add new middleware to pipeline
     *
     * @param string $p_middleware
     *
     * @return \FreeFW\Middleware\Pipeline
     */
    public function pipeFromDI(string $p_middleware)
    {
        $middleware = \FreeFW\DI\DI::get($p_middleware);
        $this->middlewares->enqueue($middleware);
        return $this;
    }

    /**
     * handle pipeline
     *
     * @param \Psr\Http\Message\ServerRequestInterface $p_request
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function handle(ServerRequestInterface $p_request): ResponseInterface
    {
        if (!$this->middlewares->isEmpty()) {
            $nextHandler = clone $this;
            $middleware  = $nextHandler->middlewares->dequeue();
            $this->logger->debug(sprintf('FreeFW.Middleware.Pipeline >> %s', get_class($middleware)));
            $response = $middleware->process($p_request, $nextHandler);
            $this->logger->debug(sprintf('FreeFW.Middleware.Pipeline << %s', get_class($middleware)));
            return $response;
        } else {
            // @todo
        }
    }
}
