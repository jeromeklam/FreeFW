<?php
namespace FreeFW\Http;

use \InvalidArgumentException;
use \Psr\Http\Message\ServerRequestInterface;
use \Psr\Http\Message\UriInterface;
use \Psr\Http\Message\StreamInterface;
use \Psr\Http\Message\UploadedFileInterface;

/**
 * Server-side HTTP request
 *
 * Extends the Request definition to add methods for accessing incoming data,
 * specifically server parameters, cookies, matched path parameters, query
 * string arguments, body parameters, and upload file information.
 *
 * "Attributes" are discovered via decomposing the request (and usually
 * specifically the URI path), and typically will be injected by the application.
 *
 * Requests are considered immutable; all methods that might change state are
 * implemented such that they retain the internal state of the current
 * message and return a new instance that contains the changed state.
 */
class ServerRequest extends Request implements ServerRequestInterface
{
    /**
     * @var array
     */
    private $attributes = [];

    /**
     * @var array
     */
    private $cookieParams = [];

    /**
     * @var null|array|object
     */
    private $parsedBody;

    /**
     * @var array
     */
    private $queryParams = [];

    /**
     * @var array
     */
    private $serverParams;

    /**
     * @var array
     */
    private $uploadedFiles = [];

    /**
     * @param string                               $method       HTTP method
     * @param string|UriInterface                  $uri          URI
     * @param array                                $headers      Request headers
     * @param string|null|resource|StreamInterface $body         Request body
     * @param string                               $version      Protocol version
     * @param array                                $serverParams Typically the $_SERVER superglobal
     */
    public function __construct(
        $method,
        $uri,
        array $headers = [],
        $body = null,
        $version = '1.1',
        array $serverParams = []
    ) {
        $this->serverParams = $serverParams;
        parent::__construct($method, $uri, $headers, $body, $version);
    }

    /**
     * Return an UploadedFile instance array.
     *
     * @param array $files A array which respect $_FILES structure
     *
     * @throws InvalidArgumentException for unrecognized values
     *
     * @return array
     */
    public static function normalizeFiles(array $files)
    {
        $normalized = [];
        foreach ($files as $key => $value) {
            if ($value instanceof UploadedFileInterface) {
                $normalized[$key] = $value;
            } else {
                if (is_array($value) && isset($value['tmp_name'])) {
                    $normalized[$key] = self::createUploadedFileFromSpec($value);
                } else {
                    if (is_array($value)) {
                        $normalized[$key] = self::normalizeFiles($value);
                        continue;
                    } else {
                        throw new InvalidArgumentException('Invalid value in files specification');
                    }
                }
            }
        }
        return $normalized;
    }

    /**
     * Create and return an UploadedFile instance from a $_FILES specification.
     * If the specification represents an array of values, this method will
     * delegate to normalizeNestedFileSpec() and return that return value.
     *
     * @param array $value $_FILES struct
     *
     * @return array|UploadedFileInterface
     */
    private static function createUploadedFileFromSpec(array $value)
    {
        if (is_array($value['tmp_name'])) {
            return self::normalizeNestedFileSpec($value);
        }
        return new UploadedFile(
            $value['tmp_name'],
            (int) $value['size'],
            (int) $value['error'],
            $value['name'],
            $value['type']
        );
    }

    /**
     * Normalize an array of file specifications.
     *
     * Loops through all nested files and returns a normalized array of
     * UploadedFileInterface instances.
     *
     * @param array $files
     * @return UploadedFileInterface[]
     */
    private static function normalizeNestedFileSpec(array $files = [])
    {
        $normalizedFiles = [];

        foreach (array_keys($files['tmp_name']) as $key) {
            $spec = [
                'tmp_name' => $files['tmp_name'][$key],
                'size'     => $files['size'][$key],
                'error'    => $files['error'][$key],
                'name'     => $files['name'][$key],
                'type'     => $files['type'][$key],
            ];
            $normalizedFiles[$key] = self::createUploadedFileFromSpec($spec);
        }

        return $normalizedFiles;
    }

    /**
     * Return a ServerRequest populated with superglobals:
     * $_GET
     * $_POST
     * $_COOKIE
     * $_FILES
     * $_SERVER
     *
     * @return ServerRequestInterface
     */
    public static function fromGlobals()
    {
        $method   = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'GET';
        $headers  = function_exists('getallheaders') ? getallheaders() : [];
        $uri      = self::getUriFromGlobals();
        // Special case : convert data to $_POST for PUT method
        if ((strtoupper($method) == 'PUT' || strtoupper($method) == 'POST') &&
            is_array($_POST) && count($_POST) == 0) {
            $content = file_get_contents("php://input", 'r+');
            $post    = json_decode($content, true);
            if (!$post) {
                $post = [];
                parse_str($content, $post);
            }
            $body = null;
        } else {
            $body = new \FreeFW\Stream\LazyOpenStream('php://input', 'r+');
            $post = $_POST;
        }
        $protocol = isset($_SERVER['SERVER_PROTOCOL']) ? str_replace('HTTP/', '', $_SERVER['SERVER_PROTOCOL']) : '1.1';
        $serverRequest = new ServerRequest($method, $uri, $headers, $body, $protocol, $_SERVER);
        return $serverRequest
            ->withCookieParams($_COOKIE)
            ->withQueryParams($_GET)
            ->withParsedBody($post)
            ->withUploadedFiles(self::normalizeFiles($_FILES))
        ;
    }

    /**
     * Get a Uri populated with values from $_SERVER.
     *
     * @return UriInterface
     */
    public static function getUriFromGlobals()
    {
        $uri     = new Uri('');
        $uri     = $uri->withScheme(!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http');
        $hasPort = false;
        if (isset($_SERVER['HTTP_HOST'])) {
            $hostHeaderParts = explode(':', $_SERVER['HTTP_HOST']);
            $uri = $uri->withHost($hostHeaderParts[0]);
            if (isset($hostHeaderParts[1])) {
                $hasPort = true;
                $uri = $uri->withPort($hostHeaderParts[1]);
            }
        } elseif (isset($_SERVER['SERVER_NAME'])) {
            $uri = $uri->withHost($_SERVER['SERVER_NAME']);
        } elseif (isset($_SERVER['SERVER_ADDR'])) {
            $uri = $uri->withHost($_SERVER['SERVER_ADDR']);
        }

        if (!$hasPort && isset($_SERVER['SERVER_PORT'])) {
            $uri = $uri->withPort($_SERVER['SERVER_PORT']);
        }

        $hasQuery = false;
        if (isset($_SERVER['REQUEST_URI'])) {
            $requestUriParts = explode('?', $_SERVER['REQUEST_URI']);
            $uri = $uri->withPath($requestUriParts[0]);
            if (isset($requestUriParts[1])) {
                $hasQuery = true;
                $uri = $uri->withQuery($requestUriParts[1]);
            }
        }

        if (!$hasQuery && isset($_SERVER['QUERY_STRING'])) {
            $uri = $uri->withQuery($_SERVER['QUERY_STRING']);
        }

        return $uri;
    }


    /**
     * {@inheritdoc}
     */
    public function getServerParams()
    {
        return $this->serverParams;
    }

    /**
     * {@inheritdoc}
     */
    public function getUploadedFiles()
    {
        return $this->uploadedFiles;
    }

    /**
     * {@inheritdoc}
     */
    public function withUploadedFiles(array $uploadedFiles)
    {
        $new = clone $this;
        $new->uploadedFiles = $uploadedFiles;

        return $new;
    }

    /**
     * {@inheritdoc}
     */
    public function getCookieParams()
    {
        return $this->cookieParams;
    }

    /**
     * {@inheritdoc}
     */
    public function withCookieParams(array $cookies)
    {
        $new = clone $this;
        $new->cookieParams = $cookies;

        return $new;
    }

    /**
     * {@inheritdoc}
     */
    public function getQueryParams()
    {
        return $this->queryParams;
    }

    /**
     * {@inheritdoc}
     */
    public function withQueryParams(array $query)
    {
        $new = clone $this;
        $new->queryParams = $query;

        return $new;
    }

    public function mergeQueryParams(array $query)
    {
        $new = clone $this;
        $new->queryParams = array_merge_recursive($this->queryParams, $query);

        return $new;
    }

    /**
     * {@inheritdoc}
     */
    public function getParsedBody()
    {
        return $this->parsedBody;
    }

    /**
     * {@inheritdoc}
     */
    public function withParsedBody($data)
    {
        $new = clone $this;
        $new->parsedBody = $data;
        return $new;
    }

    /**
     * {@inheritdoc}
     */
    public function getAttributes()
    {
        if (is_array($this->parsedBody)) {
            return array_merge($this->attributes, $this->parsedBody);
        }
        return array_merge($this->attributes);
    }

    /**
     * {@inheritdoc}
     */
    public function getAttribute($attribute, $default = null)
    {
        if (array_key_exists($attribute, $this->attributes)) {
            return $this->attributes[$attribute];
        }
        if (is_array($this->parsedBody) && array_key_exists($attribute, $this->parsedBody)) {
            return $this->parsedBody[$attribute];
        }
        if (is_array($this->queryParams) && array_key_exists($attribute, $this->queryParams)) {
            return $this->queryParams[$attribute];
        }
        return $default;
    }

    /**
     * {@inheritdoc}
     */
    public function withAttribute($attribute, $value)
    {
        $new = clone $this;
        $new->attributes[$attribute] = $value;
        return $new;
    }

    /**
     * {@inheritdoc}
     */
    public function withoutAttribute($attribute)
    {
        if (false === array_key_exists($attribute, $this->attributes)) {
            return $this;
        }
        $new = clone $this;
        unset($new->attributes[$attribute]);
        return $new;
    }

    /**
     * Retourne le nom du programme appelant
     *
     * @return string
     */
    public function getCaller()
    {
        $prg = 'app';
        if (is_array($_SERVER)) {
            $parts = pathinfo($_SERVER["PHP_SELF"]);
            $prg   = $parts['filename'];
        }
        return $prg;
    }

    /**
     * Get client IP
     *
     * @return string
     */
    public static function getClientIp()
    {
        //Just get the headers if we can or else use the SERVER global
        if (function_exists('apache_request_headers')) {
            $headers = apache_request_headers();
        } else {
            $headers = $_SERVER;
        }
        //Get the forwarded IP if it exists
        if (array_key_exists('X-Forwarded-For', $headers) &&
            filter_var($headers['X-Forwarded-For'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            $the_ip = $headers['X-Forwarded-For'];
        } else {
            if (array_key_exists('HTTP_X_FORWARDED_FOR', $headers) &&
                filter_var($headers['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
                $the_ip = $headers['HTTP_X_FORWARDED_FOR'];
            } else {
                if (array_key_exists('X-ClientSide', $headers)) {
                    $parts  = explode(':', $headers['X-ClientSide']);
                    $the_ip = $parts[0];
                } else {
                    $the_ip = filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
                }
            }
        }
        return $the_ip;
    }

    /**
     * Appel local ?
     *
     * @return boolean
     */
    public function isLocalCall()
    {
        $ip = $this->getClientIp();
        if ($ip == '127.0.0.1') {
            return true;
        }
        return false;
    }

    /**
     * Get one queryParam / attribute
     *
     * @param string $attribute
     * @param mixed  $default
     *
     * @return mixed|string
     */
    public function get($attribute, $default = null)
    {
        if (array_key_exists($attribute, $this->queryParams)) {
            return $this->queryParams[$attribute];
        } else {
            if (array_key_exists($attribute, $this->attributes)) {
                return $this->attributes[$attribute];
            }
        }
        return $default;
    }

    /**
     * Check attrribute existance
     *
     * @param string $attribute
     *
     * @return boolean
     */
    public function hasAttribute($attribute)
    {
        if (array_key_exists($attribute, $this->attributes)) {
            return true;
        }
        if (is_array($this->parsedBody) && array_key_exists($attribute, $this->parsedBody)) {
            return true;
        }
        if (is_array($this->queryParams) && array_key_exists($attribute, $this->queryParams)) {
            return true;
        }
        return false;
    }

    /**
     * Fet referer
     *
     * @return string
     */
    public function getReferer()
    {
        if (array_key_exists('HTTP_REFERER', $_SERVER)) {
            return $_SERVER['HTTP_REFERER'];
        }
        return '';
    }

    /**
     * Direct getter
     *
     * @param string $name
     *
     * @throws \Exception
     *
     * @return mixed
     */
    public function __get($name)
    {
        if ($this->hasAttribute($name)) {
            return $this->getAttribute($name);
        } else {
            throw new \Exception(sprintf('%f property not found !', $name));
        }
    }

    /**
     * Return uri as string
     *
     * @return string
     */
    public function __toString()
    {
        return (string)$this->getUri();
    }
}
