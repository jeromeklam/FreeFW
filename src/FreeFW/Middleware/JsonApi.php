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
class JsonApi implements
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
    protected $contentTypes = ['application/vnd.api+json'];

    /**
     *
     * {@inheritDoc}
     * @see \FreeFW\Interfaces\ApiAdapterInterface::decodeRequest()
     */
    public function decodeRequest(ServerRequestInterface $p_request): \FreeFW\Http\ApiParams
    {
        $this->logger->debug(sprintf('FreeFW.Middleware.JsonApi.decode.start'));
        $apiParams = new \FreeFW\Http\ApiParams();
        // Datas
        $contents = $p_request->getBody()->getContents();
        $json     = json_decode($contents, false);
        $version  = '1.0';
        if ($json && $json instanceof \stdClass) {
            if (isset($json->jsonapi) && isset($json->jsonapi->version)) {
                $version = $json->jsonapi->version;
            }
            if (isset($json->data)) {
                $decoder  = new \FreeFW\JsonApi\V1\Decoder();
                $document = new \FreeFW\JsonApi\V1\Model\Document($json);
                $body     = $decoder->decode($document);
                $apiParams->setData($body);
            } else {
                // @todo
            }
        } else {
            // @todo
        }
        $params = $p_request->getQueryParams();
        /**
         * Handle specific parameters
         */
        // Fields
        if (array_key_exists('fields', $params)) {
            $apiParams->setFields($params['fields']);
        }
        // Include
        if (array_key_exists('include', $params)) {
            $apiParams->setInclude($params['include']);
        }
        // Option
        if (array_key_exists('option', $params)) {
            $options = $params['option'];
            $apiParams->setOption($options);
            if (is_array($options) && isset($options['lang'])) {
                \FreeFW\DI\DI::setShared('lang', $options['lang']);
            }
            if (is_array($options) && isset($options['money'])) {
                \FreeFW\DI\DI::setShared('money', $options['money']);
            }
        }
        // Filters
        if (array_key_exists('filter', $params)) {
            $filters = $params['filter'];
            // Transform filters to \FreeFW\Model\Conditions...
            $conditions = new \FreeFW\Model\Conditions();
            $conditions->initFromArray($filters);
            $apiParams->setFilters($conditions);
        }
        // Sort
        if (array_key_exists('sort', $params)) {
            $apiParams->setSort($params['sort']);
        }
        // Page
        if (array_key_exists('page', $params)) {
            $page = $params['page'];
            if (is_array($page)) {
                $start = 0;
                $len   = 25;
                if (array_key_exists('number', $page) || array_key_exists('size', $page)) {
                    $number = 1;
                    $size   = 25;
                    if (array_key_exists('number', $page)) {
                        $number = $page['number'];
                    }
                    if (array_key_exists('size', $page)) {
                        $size = $page['size'];
                    }
                    $start = ($number - 1) * $size;
                    $len   = $size;
                }
                if (array_key_exists('offset', $page) || array_key_exists('limit', $page)) {
                    $offset = 1;
                    $limit  = 25;
                    if (array_key_exists('offset', $page)) {
                        $offset = $page['offset'];
                    }
                    if (array_key_exists('limit', $page)) {
                        $limit = $page['limit'];
                    }
                    $start = $offset - 1;
                    $len   = $limit - $offset;
                }
            } else {
                throw new \FreeFW\JsonApi\FreeFWJsonApiException(
                    sprintf('Incorrect values for page parameter !')
                );
            }
            $apiParams
                ->setStart($start)
                ->setLength($len)
            ;
        }
        // Next
        $this->logger->debug(sprintf('FreeFW.Middleware.JsonApi.decode.end'));
        return $apiParams;
    }

    /**
     *
     * {@inheritDoc}
     * @see \FreeFW\Interfaces\ApiAdapterInterface::encodeResponse()
     */
    public function encodeResponse(ResponseInterface $p_response, \FreeFW\Http\ApiParams $p_api_params): ResponseInterface
    {
        $this->logger->debug(sprintf('FreeFW.Middleware.JsonApi.encode.start'));
        if (intval($p_response->getStatusCode()) < 300) {
            $body = $p_response->getBody();
            if (is_object($body)) {
                if ($body instanceof StreamInterface) {
                    $this->logger->debug('FreeFW.Middleware.JsonApi.encode.before.unserialize');
                    $content = $body->getContents();
                    $object  = unserialize($content);
                    $this->logger->debug('FreeFW.Middleware.JsonApi.encode.after.unserialize');
                    if ($object instanceof \FreeFW\Interfaces\ApiResponseInterface) {
                        if ($object->isSingleElement()) {
                            $encoder = new \FreeFW\JsonApi\V1\Encoder();
                            $encoder->setLogger($this->logger);
                            $this->logger->debug('FreeFW.Middleware.JsonApi.encode.before.encode');
                            $document   = $encoder->encode($object, $p_api_params);
                            $json       = json_encode($document);
                            $this->logger->debug('FreeFW.Middleware.JsonApi.encode.after.encode');
                            if ($document->hasErrors()) {
                                $p_response = $this->createResponse(
                                    $document->getHttpCode(),
                                    $json
                                );
                            } else {
                                $p_response = $p_response->withBody(\GuzzleHttp\Psr7\Utils::streamFor($json));
                            }
                        } else {
                            $encoder  = new \FreeFW\JsonApi\V1\Encoder();
                            $encoder->setLogger($this->logger);
                            $this->logger->debug('FreeFW.Middleware.JsonApi.encode.before.encode');
                            $document = $encoder->encodeList($object, $p_api_params);
                            $p_response = $p_response->withBody(
                                \GuzzleHttp\Psr7\Utils::streamFor(
                                    json_encode($document)
                                )
                            );
                            $this->logger->debug('FreeFW.Middleware.JsonApi.encode.after.encode');
                        }
                    } else {
                        $document   = new \FreeFW\JsonApi\V1\Model\Document();
                        $p_response = $p_response->withBody(
                            \GuzzleHttp\Psr7\Utils::streamFor(
                                json_encode($document)
                            )
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
            $body = $p_response->getBody();
            if (is_object($body)) {
                $document = new \FreeFW\JsonApi\V1\Model\Document();
                if ($body instanceof StreamInterface) {
                    $content = $body->getContents();
                    if ($content != '') {
                        try {
                            $object = @unserialize($content);
                            if ($object instanceof \FreeFW\Interfaces\ValidatorInterface) {
                                $encoder = new \FreeFW\JsonApi\V1\Encoder();
                                $encoder->setLogger($this->logger);
                                /**
                                 * @var \FreeFW\Core\Error $oneError
                                 */
                                foreach ($object->getErrors() as $idx => $oneError) {
                                    $error = new \FreeFW\JsonApi\V1\Model\ErrorObject(
                                        $oneError->getType(),
                                        $oneError->getMessage(),
                                        $oneError->getCode(),
                                        $oneError->getField()
                                    );
                                    $document->addError($error);
                                }
                            } else {
                                $error = new \FreeFW\JsonApi\V1\Model\ErrorObject(
                                    $p_response->getStatusCode(),
                                    $p_response->getReasonPhrase()
                                );
                                $document->addError($error);
                            }
                        } catch (\Exception $ex) {
                            $error = new \FreeFW\JsonApi\V1\Model\ErrorObject(500, $content);
                            $document->addError($error);
                        }
                    } else {
                        // @todo...
                        if ($p_response->getStatusCode() >= 300) {
                            $error = new \FreeFW\JsonApi\V1\Model\ErrorObject(
                                $p_response->getStatusCode(),
                                $p_response->getReasonPhrase()
                            );
                            $document->addError($error);
                        }
                    }
                } else {
                    $error = new \FreeFW\JsonApi\V1\Model\ErrorObject(500, 'Unknown Error 2');
                    $document->addError($error);
                }
                $p_response = $p_response->withBody(
                    \GuzzleHttp\Psr7\Utils::streamFor(
                        json_encode($document)
                    )
                );
            } else {
                $document = new \FreeFW\JsonApi\V1\Model\Document();
                $error    = new \FreeFW\JsonApi\V1\Model\ErrorObject(
                    $p_response->getStatusCode(),
                    $p_response->getReasonPhrase()
                );
                $document->addError($error);
                $p_response = $p_response->withBody(
                    \GuzzleHttp\Psr7\Utils::streamFor(
                        json_encode($document)
                    )
                );
            }
        }
        $this->logger->debug(sprintf('FreeFW.Middleware.JsonApi.encode.end'));
        return $p_response->withHeader('Content-Type', 'application/vnd.api+json');
    }
}
