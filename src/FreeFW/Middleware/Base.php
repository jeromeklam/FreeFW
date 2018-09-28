<?php
namespace FreeFW\Middleware;

use \Psr\Http\Message\ResponseFactoryInterface;
use \Psr\Http\Message\ResponseInterface;
use \Psr\Http\Message\StreamInterface;
use \FreeFW\Http\Response;

/**
 *
 * @author jerome.klam
 *
 */
class Base implements ResponseFactoryInterface
{

    /**
     * Default contentType
     * @var string
     */
    const CONTENT_TYPE_JSON    = 'application/json';
    const CONTENT_TYPE_APIJSON = 'application/vnd.api+json';
    const CONTENT_TYPE_DEFAULT = 'default';

    /**
     * Response default content-type
     * @var string
     */
    protected $content_type = self::CONTENT_TYPE_DEFAULT;

    /**
     * Set content type
     *
     * @param string $p_type
     *
     * @return \FreeFW\Middleware\Base
     */
    protected function setContentType($p_type)
    {
        $this->content_type = $p_type;
        return $this;
    }

    /**
     * Create a new response.
     *
     * @param integer $code HTTP status code
     * @param string  $reason_text
     *
     * @return ResponseInterface
     */
    public function createResponse(int $code = 200, string $reasonPhrase = ''): ResponseInterface
    {
        $headers = [];
        if ($this->content_type != self::CONTENT_TYPE_DEFAULT) {
            $headers['Content-Type'] = $this->content_type;
        }
        $response = new Response($code, $headers);
        if ($reasonPhrase !== null) {
            return $response->withStatus($code, $reasonPhrase);
        }
        return $response;
    }
    public function createStreamFromFile(string $filename, string $mode = 'r'): StreamInterface
    {}

    public function createStream(string $content = ''): StreamInterface
    {}

    public function createStreamFromResource($resource): StreamInterface
    {}

}
