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
        $this->logger->debug(sprintf('FreeFW.Middleware.JsonApi.decode.start'));
        $apiParams = new \FreeFW\Http\ApiParams();
        // Datas
        $contents = $p_request->getBody()->getContents();
        $json     = json_decode($contents, false);
        if ($json && $json instanceof \stdClass) {
            if (isset($json->data)) {
                $decoder  = new \FreeFW\JsonApi\V1\Decoder();
                $document = new \FreeFW\JsonApi\V1\Model\Document($json);
                $body    = $decoder->decode($document);
                $apiParams->setData($body);
            } else {
                // @todo
            }
        }
        $this->logger->debug(sprintf('FreeFW.Middleware.JsonApi.decode.end'));
        $p_request = $p_request->withAttribute('api_params', $apiParams);
        return $p_request;
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
        $response = $this->createResponse(500);
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
        $this->logger->debug(sprintf('FreeFW.Middleware.JsonApi.encode.start'));
        if ($p_response->getStatusCode() < 300) {
            $body = $p_response->getBody();
            if (is_object($body)) {
                if ($body instanceof StreamInterface) {
                    $content = $body->getContents();
                    $object  = unserialize($content);
                    if ($object instanceof \FreeFW\Interfaces\ApiResponseInterface) {
                        $encoder    = new \FreeFW\JsonApi\V1\Encoder();
                        $document   = $encoder->encode($object);
                        $json       = json_encode($document);
                        if ($document->hasErrors()) {
                            $p_response = $this->createResponse(
                                $document->getHttpCode(),
                                $json
                            );
                        } else {
                            $p_response = $p_response->withBody(\GuzzleHttp\Psr7\stream_for($json));
                        }
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
        } else {
            $document = new \FreeFW\JsonApi\V1\Model\Document();
            $error    = new \FreeFW\JsonApi\V1\Model\ErrorObject(
                $p_response->getStatusCode(),
                $p_response->getReasonPhrase()
            );
            $document->addError($error);
            $p_response = $p_response->withBody(
                \GuzzleHttp\Psr7\stream_for(
                    json_encode($document)
                )
            );
        }
        $this->logger->debug(sprintf('FreeFW.Middleware.JsonApi.encode.end'));
        return $p_response->withHeader('Content-Type', 'application/vnd.api+json');
    }
}
