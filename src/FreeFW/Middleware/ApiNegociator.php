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
    \FreeFW\Interfaces\ConfigAwareTraitInterface,
    \FreeFW\Interfaces\ApiNegociatorInterface
{

    /**
     * comportements
     */
    use \Psr\Log\LoggerAwareTrait;
    use \FreeFW\Behaviour\EventManagerAwareTrait;
    use \FreeFW\Behaviour\ConfigAwareTrait;
    use \FreeFW\Behaviour\HttpFactoryTrait;

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
     * Formats
     * @var array
     */
    protected $formats = [
        'application/vnd.api+json' => [
            'class'   => 'FreeFW::Middleware::JsonApi',
            'default' => true
        ],
        'application/json' => [
            'class'   => 'FreeFW::Middleware::Json',
            'default' => false
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
     * Set methods
     *
     * @param array $p_methods
     *
     * @return \FreeFW\Interfaces\ApiNegociatorInterface
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
     * @return \FreeFW\Interfaces\ApiNegociatorInterface
     */
    public function setOverride(bool $p_override = true) : self
    {
        $this->override = $p_override;
        return $this;
    }


    /**
     *
     * {@inheritDoc}
     * @see \FreeFW\Interfaces\ApiNegociatorInterface::createUnsupportedRequestResponse()
     */
    public function createUnsupportedRequestResponse(): ResponseInterface
    {
        return $this->createResponse(415);
    }

    /**
     *
     * {@inheritDoc}
     * @see \FreeFW\Interfaces\ApiNegociatorInterface::createErrorResponse()
     */
    public function createErrorResponse(\Exception $p_ex): ResponseInterface
    {
        $document = new \FreeFW\JsonApi\V1\Model\Document();
        $error    = new \FreeFW\JsonApi\V1\Model\ErrorObject(
            500,
            $p_ex->getMessage(),
            $p_ex->getCode()
        );
        $document->addError($error);
        $response = $this->createResponse(500);
        return $response->withBody(
            \GuzzleHttp\Psr7\stream_for(
                json_encode($document)
            )
        );
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
            $method = $p_request->getMethod();
            if (!in_array($method, $this->methods, true)) {
                return $this->createResponse(405, "Method not allowed !");
            }
            $contentType = trim($p_request->getHeaderLine('Content-Type'));
            if ($contentType == '') {
                $contentType = trim($p_request->getHeaderLine('Accept'));
            }
            if (isset($this->formats[$contentType])) {
                $class = $this->formats[$contentType]['class'];
            } else {
                return $this->createResponse(415, "Unsupported media type !");
                foreach ($this->formats as $name => $format) {
                    if ($format['default']) {
                        $class = $format['class'];
                    }
                }
            }
            if ($class) {
                // Ok, encode, decode, ...
                $this->logger->debug(sprintf('FreeFW.Middleware.ApiNegociator %s', $class));
                $mid       = \FreeFW\DI\DI::get($class);
                $apiParams = $p_request->getAttribute('api_params', false);
                if ($apiParams === false) {
                    $apiParams = $mid->decodeRequest($p_request);
                    $p_request = $p_request->withAttribute('api_params', $apiParams);
                }
                $response  = $p_handler->handle($p_request);
                if ($response instanceof \FreeFW\Psr7\Response) {
                    return $response;
                }
                $apiParams = $p_request->getAttribute('api_params');
                return $mid->encodeResponse(
                    $response, $apiParams
                );
            } else {
                return $this->createResponse(500, "No api class found !");
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
    }
}
