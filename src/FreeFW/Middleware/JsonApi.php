<?php
namespace FreeFW\Middleware;

use \Neomerx\JsonApi\Document\Link;
use \Neomerx\JsonApi\Document\Error;
use \Neomerx\JsonApi\Contracts\Document\LinkInterface;
use \Neomerx\JsonApi\Encoder\Encoder;
use \Neomerx\JsonApi\Encoder\EncoderOptions;
use \Neomerx\JsonApi\Encoder\Parameters\EncodingParameters;
use \Neomerx\JsonApi\Exceptions\ErrorCollection;
use \Psr\Http\Server\MiddlewareInterface;
use \Psr\Http\Server\RequestHandlerInterface;
use \Psr\Http\Message\ResponseFactoryInterface;
use \FreeFW\Http\Response;
use \FreeFW\Tools\PBXString as Str;
use \FreeFW\Tools\Stream;
use \FreeFW\ResourceDi;
use \Psr\Http\Message\ServerRequestInterface;
use \Psr\Http\Message\StreamInterface;
use \Psr\Http\Message\ResponseInterface;

/**
 *
 * @author jerome.klam
 *
 */
class JsonApi extends \FreeFW\Middleware\Base implements MiddlewareInterface
{

    /**
     * Encoder schemas
     * @var array
     */
    protected static $schemas = null;

    /**
     * Constructeur
     */
    public function __construct()
    {
        $this->setContentType(self::CONTENT_TYPE_APIJSON);
    }

    /**
     * Retourne les schémas
     *
     * @return array
     */
    public static function getSchemas($p_version)
    {
        if (self::$schemas === null) {
            self::$schemas = ResourceDi::getInstance()->getSchemas($p_version);
        }
        if (!is_array(self::$schemas)) {
            self::$schemas = [];
        }
        return self::$schemas;
    }

    /**
     * Retourne les paramètres standards définis pour le format json-api
     *
     * @param ServerRequestInterface $p_request
     *
     * @return \FreeFW\Model\ApiParams
     */
    protected function decodeStandardQueryParameters($p_request, $version)
    {
        $jsonApiParams = new \FreeFW\Model\ApiParams();
        $jsonApiParams->setSchemas(self::getSchemas($version));
        $attributes = $p_request->getAttributes();
        $attributes = array_merge($attributes, $p_request->getQueryParams());
        foreach ($attributes as $key => $value) {
            switch ($key) {
                case 'depuis':
                case 'from' :
                    $jsonApiParams->setFrom($value);
                    break;
                case 'longueur':
                case 'len' :
                    $jsonApiParams->setLen($value);
                    break;
                case 'page' :
                    $jsonApiParams->setPage($value);
                    break;
                case 'query_id':
                    $jsonApiParams->setQueryId($value);
                    break;
                case 'filter':
                    $jsonApiParams->setFilters($value);
                    break;
                case 'include':
                    $jsonApiParams->setIncluded($value);
                    break;
                case 'sort':
                    $jsonApiParams->setSort($value);
                    break;
                case 'fields':
                    $jsonApiParams->setFields($value);
                    break;
                case 'data':
                    $jsonApiParams->setData($value);
                    break;
                case 'query_mode':
                    $jsonApiParams->setMode($value);
                    break;
                case 'query_andor':
                    $jsonApiParams->setAndor($value);
                    break;
            }
        }
        return $jsonApiParams;
    }

    /**
     * Process an incoming server request and return a response, optionally delegating
     * to the next middleware component to create the response.
     *
     * @param ServerRequestInterface  $request
     * @param RequestHandlerInterface $handler
     *
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $version = $handler->getVersion();
        $options = JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES;
        if (!$this->validateExistingContentType($request) || !$this->hasCorrectHeadersForData($request)) {
            $errors   = (new ErrorCollection())->add(self::buildUnsupportedMediaTypeError());
            $encoder  = Encoder::instance([], new EncoderOptions($options));
            $response = $this->createResponse(415);
            return $response->withBody(Stream::streamFor($encoder->encodeErrors($errors)));
        }
        if (!$this->hasCorrectlySetAcceptHeader($request)) {
            $errors   = (new ErrorCollection())->add(self::buildUnacceptableError());
            $encoder  = Encoder::instance([], new EncoderOptions($options));
            $response = $this->createResponse(406);
            return $response->withBody(Stream::streamFor($encoder->encodeErrors($errors)));
        }
        $schs = [];
        foreach (self::getSchemas($version) as $idx => $sch) {
            $schs[$idx] = $sch['to'];
        }
        try {
            //
            $complement = $this->decodeStandardQueryParameters($request, $version);
            // $request content type must be verified
            $response = $handler->handle($request->withAttribute('api_params', $complement));
            if ($response->hasAttribute('api_version')) {
                $version = $response->getAttribute('api_version');
            }
            // response must be converted to correct type
            $encoder = Encoder::instance(
                $schs,
                new EncoderOptions($options)
            );
            $encoder  = $encoder->withLinks([]);
            $body     = $response->getBody();
            $response = $response->withHeader('Content-Type', self::CONTENT_TYPE_APIJSON);
            if (is_object($body)) {
                if ($body instanceof StreamInterface) {
                    $content = $body->getContents();
                    if ($content != '' && $content !== false) {
                        try {
                            $object = @unserialize($content);
                            if ($object !== false) {
                                if ($object instanceof \FreeFW\Http\Errors) {
                                    $errors     = $object->getErrors();
                                    $jsonErrors = new ErrorCollection();
                                    foreach ($errors as $idx => $oneError) {
                                        $error = self::buildStandardError($oneError);
                                        $jsonErrors->add($error);
                                    }
                                    return $response->withBody(Stream::streamFor($encoder->encodeErrors($jsonErrors)));
                                } else {
                                    $params = new EncodingParameters(
                                        $complement->getIncluded(),
                                        $complement->getFields()
                                    );
                                    return $response->withBody(Stream::streamFor($encoder->encodeData($object, $params)));
                                }
                            }
                        } catch (\Exception $ex) {
                            // @todo
                            var_dump($ex);die;
                            return $response->withBody(Stream::streamFor($encoder->encodeData(null)));
                        }
                    } else {
                        return $response->withBody(Stream::streamFor($encoder->encodeData(null)));
                    }
                    $body->rewind();
                }
            } else {
                if ($body === null) {
                    return $response->withBody(Stream::streamFor($encoder->encodeData(null)));
                }
            }
        } catch (\Exception $ex) {
            $jsonErrors = new ErrorCollection();
            $jsonErrors->addDataError($ex->getMessage());
            $response   = $this->createResponse(500);
            $encoder    = Encoder::instance(
                $schs,
                new EncoderOptions($options)
            );
            return $response->withBody(Stream::streamFor($encoder->encodeErrors($jsonErrors)));
        }
        return $response;
    }

    /**
     * Validate contentType
     *
     * @param ServerRequestInterface $request
     *
     * @return boolean
     */
    protected function validateExistingContentType(ServerRequestInterface $request)
    {
        $accept = $request->getHeader('Accept');
        return Str::is(self::CONTENT_TYPE_APIJSON, $accept) || Str::is('', $accept);
    }

    /**
     * Correct headers ?
     *
     * @return boolean
     */
    protected function hasCorrectHeadersForData(ServerRequestInterface $request)
    {
        if ($this->clientRequestHasJsonApiData($request)) {
            if (!Str::is('multipart/form-data', $request->getHeader('Content-Type'))) {
                return true;
            }
            return $this->contentTypeIsValid($request->getHeader('Content-Type'));
        }
        return true;
    }

    /**
     * Accept Header correct ?
     *
     * @param ServerRequestInterface $request
     *
     * @return boolean
     */
    protected function hasCorrectlySetAcceptHeader(ServerRequestInterface $request)
    {
        $accept = implode(';', $request->getHeader('Accept'));
        if ('*/*' !== $accept) {
            $val1 = substr_count($accept, self::CONTENT_TYPE_APIJSON);
            $val2 = substr_count($accept, self::CONTENT_TYPE_APIJSON . ';');
            return $val1 > $val2;
        }
        return true;
    }

    /**
     * Request has data ?
     *
     * @return boolean
     */
    protected function clientRequestHasJsonApiData(ServerRequestInterface $request)
    {
        return !(empty($request->getBody()) && empty($request->getAttributes()));
    }

    /**
     * Correct contentType ?
     *
     * @return boolean
     */
    protected function contentTypeIsValid(string $contentType)
    {
        return Str::is(self::CONTENT_TYPE_APIJSON, $contentType);
    }

    /**
     *
     * @param mixed         $id
     * @param LinkInterface $aboutLink
     * @param mixed         $code
     * @param array         $source
     * @param mixed         $meta
     *
     * @return \Neomerx\JsonApi\Document\Error
     */
    public static function buildUnsupportedMediaTypeError(
        $id = null,
        LinkInterface $aboutLink = null,
        $code = null,
        array $source = null,
        $meta = null
    ) {
        return new Error(
            $id ?? null,
            $aboutLink ?? new Link('http://jsonapi.org/format/#content-negotiation-clients'),
            '415',
            $code ?? null,
            'Unsupported Media Type',
            'Content-Type of a request containing JSON data must be application/vnd.api+json',
            $source,
            $meta
        );
    }

    /**
     *
     * @param mixed         $id
     * @param LinkInterface $aboutLink
     * @param mixed         $code
     * @param array         $source
     * @param mixed         $meta
     *
     * @return \Neomerx\JsonApi\Document\Error
     */
    public static function buildUnacceptableError(
        $id = null,
        LinkInterface $aboutLink = null,
        $code = null,
        array $source = null,
        $meta = null
    ) {
        return new Error(
            $id ?? null,
            $aboutLink ?? new Link('http://jsonapi.org/format/#content-negotiation-clients'),
            '406',
            $code ?? null,
            'Not Acceptable',
            'Accept header must accept application/vnd.api+json at least once without parameters',
            $source,
            $meta
        );
    }

    /**
     * Get jsonApi error from standard Http error
     *
     * @param \FreeFW\Http\Error $error
     *
     * @return \Neomerx\JsonApi\Document\Error
     */
    public static function buildStandardError(\FreeFW\Http\Error $error)
    {
        $metas = null;
        if ($error->getField() != '') {
            $metas = ['field' => $error->getField()];
        }
        return new Error(
            null,
            $error->getLink() ?? new Link('http://jsonapi.org/format'),
            $error->getStatus() ?? '400',
            $error->getCode() ?? null,
            $error->getShort(),
            $error->getDescription(),
            [
                'pointer'   => 'data/attributes/' . $error->getField(),
                'parameter' => $error->getField()
            ],
            $metas
        );
    }
}
