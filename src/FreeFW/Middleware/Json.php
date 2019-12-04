<?php
namespace FreeFW\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;

/**
 * JsonApi Middleware
 *
 * @author jeromeklam
 */
class Json implements 
    \FreeFW\Interfaces\ApiAdapterInterface,
    \Psr\Log\LoggerAwareInterface
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
    protected $contentTypes = ['application/json'];
    
    /**
     *
     * {@inheritDoc}
     * @see \FreeFW\Middleware\ApiAdapter::decodeRequest()
     */
    public function decodeRequest(ServerRequestInterface $p_request): \FreeFW\Http\ApiParams
    {
        $this->logger->debug(sprintf('FreeFW.Middleware.Json.decode.start'));
        $apiParams = new \FreeFW\Http\ApiParams();
        // Next
        $this->logger->debug(sprintf('FreeFW.Middleware.Json.decode.end'));
        return $apiParams;
    }
    
    /**
     *
     * {@inheritDoc}
     * @see \FreeFW\Middleware\ApiAdapter::encodeResponse()
     */
    public function encodeResponse(ResponseInterface $p_response, \FreeFW\Http\ApiParams $p_api_params): ResponseInterface
    {
        $this->logger->debug(sprintf('FreeFW.Middleware.Json.encode.start'));
        $body       = $p_response->getBody();
        if (is_object($body)) {
            if ($body instanceof StreamInterface) {
                $content = $body->getContents();
                $object  = unserialize($content);
                $p_response = $p_response->withBody(
                    \GuzzleHttp\Psr7\stream_for(
                        json_encode($object)
                    )
                );
            }
        }
        $this->logger->debug(sprintf('FreeFW.Middleware.Json.encode.end'));
        return $p_response->withHeader('Content-Type', 'application/json');
    }
}
