<?php
namespace FreeFW\Middleware;

use \Psr\Http\Server\MiddlewareInterface;
use \Psr\Http\Server\RequestHandlerInterface;
use \Psr\Http\Message\ServerRequestInterface;
use \Psr\Http\Message\ResponseInterface;

/**
 * Api Middleware
 *
 * @author jeromeklam
 */
class ApiNegociator implements
    MiddlewareInterface,
    \Psr\Log\LoggerAwareInterface,
    \FreeFW\Interfaces\ConfigAwareTraitInterface
{

    /**
     * Behaviour
     */
    use \Psr\Log\LoggerAwareTrait;
    use \FreeFW\Behaviour\EventManagerAwareTrait;
    use \FreeFW\Behaviour\ConfigAwareTrait;

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
        // Get Content-Type and check if defined...
        $contentType = $p_request->getHeaderLine('Content-Type');
        $class       = false;
        if (array_key_exists($contentType, $this->formats)) {
            $class = $this->formats[$contentType]['class'];
        } else {
            foreach ($this->formats as $name => $format) {
                if ($format['default']) {
                    $class = $format['class'];
                }
            }
        }
        if ($class) {
            // Ok, encode, decode, ...
            $this->logger->debug(sprintf('FreeFW.Middleware.ApiNegociator %s', $class));
            $mid = \FreeFW\DI\DI::get($class);
            if ($mid instanceof \FreeFW\Interfaces\ApiAdapterInterface) {
                try {
                    if ($mid->checkRequest($p_request)) {
                        return $mid->encodeResponse(
                            $p_handler->handle(
                                $mid->decodeRequest($p_request)
                            )
                        );
                    } else {
                        $this->logger->debug(sprintf('FreeFW.Middleware.ApiNegociator.notchecked %s', $class));
                        if ($mid->canOverride()) {
                            return $p_handler->handle($p_request);
                        }
                    }
                } catch (\Exception $ex) {
                    return $mid->createErrorResponse($ex);
                }
                return $mid->createUnsupportedRequestResponse();
            } else {
                return $this->createResponse(500, []);
            }
        }
        // Not found
        return $this->createResponse(405, []);
    }
}
