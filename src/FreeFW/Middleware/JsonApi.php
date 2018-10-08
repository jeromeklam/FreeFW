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
class JsonApi extends \FreeFW\Middleware\ApiAdapter
{

    /**
     * Allowed types
     * @var array
     */
    protected $contentTypes = ['application/vnd.api+json'];

    /**
     *
     * {@inheritDoc}
     * @see \FreeFW\Middleware\ApiAdapter::decodeRequest()
     */
    public function decodeRequest(ServerRequestInterface $p_request): ServerRequestInterface
    {
        $apiParams = new \FreeFW\Http\ApiParams();
        return $p_request->withAttribute('api_params', $apiParams);
    }

    /**
     *
     * {@inheritDoc}
     * @see \FreeFW\Middleware\ApiAdapter::createUnsupportedRequestResponse()
     */
    public function createUnsupportedRequestResponse(): ResponseInterface
    {
        return $this->createResponse(415);
    }

    /**
     *
     * {@inheritDoc}
     * @see \FreeFW\Middleware\ApiAdapter::createErrorResponse()
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
        $response = $this->createResponse(200);
        return $response->withBody(
            \GuzzleHttp\Psr7\stream_for(
                json_encode($document)
            )
        );
    }

    /**
     *
     * {@inheritDoc}
     * @see \FreeFW\Middleware\ApiAdapter::encodeResponse()
     */
    public function encodeResponse(ResponseInterface $p_response): ResponseInterface
    {
        $body = $p_response->getBody();
        if (is_object($body)) {
            if ($body instanceof StreamInterface) {
                $content = $body->getContents();
                $object  = unserialize($content);
                if ($object instanceof \FreeFW\Interfaces\ApiResponseInterface) {
                    $encoder    = new \FreeFW\JsonApi\V1\Encoder();
                    $data       = $encoder->encode($object);
                    $json       = json_encode($data);
                    $p_response = $p_response->withBody(\GuzzleHttp\Psr7\stream_for($json));
                } else {
                    $p_response = $this->createErrorResponse(
                        new \Exception('Api error : body is not an ApiResponseInterface !')
                    );
                }
            } else {
                $p_response = $this->createErrorResponse(
                    new \Exception('Api error : body is not a StreamInterface !')
                );
            }
        } else {
            $p_response = $this->createErrorResponse(
                new \Exception('Api error : body is not an object !')
            );
        }
        return $p_response->withHeader('Content-Type', 'application/vnd.api+json');
    }
}
