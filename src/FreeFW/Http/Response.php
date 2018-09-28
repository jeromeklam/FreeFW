<?php
namespace FreeFW\Http;

use \FreeFW\Tools\Stream;

/**
 * Response classique
 */
class Response extends \FreeFW\Session\Storage implements \Psr\Http\Message\ResponseInterface
{

    /**
     * Comportements
     */
    use \FreeFW\Behaviour\DI;
    use \FreeFW\Http\MessageTrait;

    /**
     * Modes d'affichage
     *
     * @var string
     */
    const MODE_DOWNLOAD = 'DOWNLOAD';
    const MODE_SHOW     = 'SHOW';

    /**
     * Modes de redirection
     * @var string
     */
    const REDIRECT_NONE      = 'NONE';
    const REDIRECT_IMMEDIATE = 'IMMEDIATE';
    const REDIRECT_STANDARD  = 'STANDARD';

    /**
     * Types de réponse
     * @var string
     */
    const TYPE_OTHER = 'OTHER';
    const TYPE_JSON  = 'JSON';

    /** @var array Map of standard HTTP status code/reason phrases */
    private static $phrases = [
        100 => 'Continue',
        101 => 'Switching Protocols',
        102 => 'Processing',
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        207 => 'Multi-status',
        208 => 'Already Reported',
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        306 => 'Switch Proxy',
        307 => 'Temporary Redirect',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Time-out',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Large',
        415 => 'Unsupported Media Type',
        416 => 'Requested range not satisfiable',
        417 => 'Expectation Failed',
        418 => 'I\'m a teapot',
        422 => 'Unprocessable Entity',
        423 => 'Locked',
        424 => 'Failed Dependency',
        425 => 'Unordered Collection',
        426 => 'Upgrade Required',
        428 => 'Precondition Required',
        429 => 'Too Many Requests',
        431 => 'Request Header Fields Too Large',
        451 => 'Unavailable For Legal Reasons',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Time-out',
        505 => 'HTTP Version not supported',
        506 => 'Variant Also Negotiates',
        507 => 'Insufficient Storage',
        508 => 'Loop Detected',
        511 => 'Network Authentication Required',
    ];

    /**
     * Status (http status code)
     * @var number
     */
    protected $status = 200;

    /**
     * Type de la réponse
     * @var string
     */
    protected $type = self::TYPE_OTHER;

    /**
     * Message
     * @var string
     */
    protected $message = 'ok';

    /**
     * Content
     * @var mixed
     */
    protected $content = null;

    /**
     * Mode d'affichage
     * @var string
     */
    protected $mode = self::MODE_SHOW;

    /**
     * Nom
     * @var string
     */
    protected $name = null;

    /**
     * om de la route
     * @var string
     */
    protected $routeName = false;

    /**
     * Route params
     * @var array
     */
    protected $routeParams = array();

    /**
     * Contenu de redirection
     * @var mixed
     */
    protected $redirectcontent = false;

    /**
     * Redirection
     * @var string
     */
    protected $redirectmode = self::REDIRECT_NONE;

    /**
     * Errors
     * @var array
     */
    protected $errors = array();

    /**
     * API version
     * @var string
     */
    protected $version = "undefined";

    /**
     * Retourne le mode de redirection
     *
     * @return string
     */
    public function getRedirectMode()
    {
        return $this->redirectmode;
    }

    /**
     * @param int                                  $status  Status code
     * @param array                                $headers Response headers
     * @param string|null|resource|StreamInterface $body    Response body
     * @param string                               $version Protocol version
     * @param string|null                          $reason  Reason phrase
     */
    public function __construct(
        $status = 200,
        array $headers = [],
        $body = null,
        $version = '1.1',
        $reason = null
    ) {
        $this->status = (int) $status;
        if ($body !== '' && $body !== null) {
            $this->stream = Stream::streamFor($body);
        }
        $this->setHeaders($headers);
        if ($reason == '' && isset(self::$phrases[$this->status])) {
            $this->message = self::$phrases[$this->status];
        } else {
            $this->message = (string) $reason;
        }
        if ($version == '' || $version === null) {
            $vesion = '1.1';
        }
        $this->protocol = $version;
    }

    /**
     * Affectation d'un contenu pour la redirection
     *
     * @param array $p_content
     *
     * @return $this;
     */
    public function forwardParams($p_content)
    {
        $this->redirectcontent = $p_content;
        return $this;
    }

    /**
     * Redirection
     *
     * @param string  $p_routeName
     * @param boolean $p_immediate
     * @param array   $p_params
     *
     * @return $this;
     */
    public function redirect($p_routeName, $p_immediate = false, $p_params = array())
    {
        $this->withStatus(307, 'Redirect');
        $this->routeName   = $p_routeName;
        $this->routeParams = $p_params;
        if ($p_immediate === true) {
            $this->redirectmode = self::REDIRECT_IMMEDIATE;
        } else {
            $this->redirectmode = self::REDIRECT_STANDARD;
        }
        return $this;
    }

    /**
     * On demande une redirection vers la page principale
     *
     * return $this;
     */
    public function redirectHome()
    {
        $this->withStatus(302, 'HOME');
        $this->routeName    = 'default';
        $this->redirectmode = self::REDIRECT_STANDARD;
        return $this;
    }

    /**
     * Retourne sur l'appelant
     *
     * @return $this;
     */
    public function redirectReferer()
    {
        $this->withStatus(302);
        $this->routeName    = 'referer';
        $this->redirectmode = self::REDIRECT_STANDARD;
        return $this;
    }

    /**
     * @see \Psr\Http\Message\ResponseInterface
     */
    public function withStatus($code, $reasonPhrase = '')
    {
        $this->status = (int) $code;
        if ($reasonPhrase == '' && isset(self::$phrases[$this->status])) {
            $reasonPhrase = self::$phrases[$this->status];
        }
        $this->message = $reasonPhrase;
        return $this;
    }

    /**
     * @see \Psr\Http\Message\ResponseInterface
     */
    public function getStatusCode()
    {
        return $this->status;
    }

    /**
     * @see \Psr\Http\Message\ResponseInterface
     */
    public function getReasonPhrase()
    {
        return $this->message;
    }

    /**
     * Afectation du contenu
     *
     * @var mixed $p_content
     *
     * @return \FreeFW\Http\Response
     */
    public function setContent($p_content = null)
    {
        $this->content = $p_content;
        return $this;
    }

    /**
     * Retourne le contenu
     *
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Génération du contenu
     */
    public function render()
    {
        $session = self::getDIShared('session');
        if ($session !== false) {
            $session->set('redirect-content', $this->redirectcontent);
        }
        if ($this->routeName !== false && $this->routeName !== '') {
            if ($this->routeName != 'referer') {
                $router = \FreeFW\Http\Router::getInstance();
                $route  = $router->getRouteByName($this->routeName);
                $url    = $route->renderHref();
            } else {
                $url = $_SERVER['HTTP_REFERER'];
            }
            header('location: ' . $url);
            exit(0);
        } else {
            header('Cache-Control: no-cache, must-revalidate');
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
            header('HTTP/' . $this->getProtocolVersion() . ' ' . $this->getStatusCode());
            foreach ($this->getHeaders() as $idx => $oneHeader) {
                header($idx . ': ' . implode(',', $oneHeader));
            }
            echo $this->stream;
        }
    }

    /**
     * Affectation du mode d'affichage
     *
     * @param string $pmode
     *
     * @return \FreeFW\Http\Response
     */
    public function setMode($pmode)
    {
        $this->mode = $pmode;

        return $this;
    }

    /**
     * Récupération du mode
     *
     * @return string
     */
    public function getMode()
    {
        return $this->mode;
    }

    /**
     * Affectation du nom d'affichage
     *
     * @param string $pname
     *
     * @return \FreeFW\Http\Response
     */
    public function setName($pname)
    {
        $this->name = $pname;

        return $this;
    }

    /**
     * Récupération du nom
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Affectation de la version
     *
     * @param string $p_version
     *
     * @return \FreeFW\Http\JsonApi\FreeFWResponse
     */
    public function setVersion($p_version)
    {
        $this->version = $p_version;
        return $this;
    }

    /**
     * Récupération de la version
     *
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Réponse de type JSON
     *
     * @return boolean
     */
    public function isJson()
    {
        return false;
    }

    /**
     * Ajout d'une erreur
     *
     * @param unknown $p_error
     *
     * @return \FreeFW\Http\Response
     */
    public function addError($p_error)
    {
        if ($this->errors === null) {
            $this->errors = array();
        }
        if (array_key_exists('field', $p_error)) {
            if (!array_key_exists($p_error['field'], $this->errors)) {
                $this->errors[$p_error['field']] = array();
            }
            $this->errors[$p_error['field']][] = $p_error;
        } else {
            $this->errors[] = $p_error;
        }
        return $this;
    }

    /**
     * Ajout d'erreurs
     *
     * @param array $p_errors
     *
     * @return \FreeFW\Http\Response
     */
    public function addErrors($p_errors)
    {
        if ($this->errors === null) {
            $this->errors = array();
        }
        $this->errors = array_merge($this->errors, $p_errors);
        return $this;
    }

    /**
     * Retourne la liste des erreurs
     *
     * @return array|null
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Purge des erreurs
     *
     * @return \FreeFW\Http\Response
     */
    public function flushErrors()
    {
        $this->errors = [];
        return $this;
    }

    /**
     * Ajout d'une erreur standardisée
     *
     * @param string $p_code
     * @param string $p_title
     * @param string $p_detail
     *
     * @return \FreeFW\Http\Response
     */
    public function addFormattedError($p_code, $p_title, $p_detail)
    {
        if ($this->errors === null) {
            $this->errors = array();
        }
        $this->errors[] = array(
            'id'     => uniqid(),
            'code'   => $p_code,
            'title'  => $p_title,
            'detail' => $p_detail
        );
        return $this;
    }

    /**
     * La réponse contient des erreurs
     *
     * @return boolean
     */
    public function hasErrors()
    {
        if (count($this->errors) > 0) {
            return true;
        }
        return false;
    }

    /**
     * Default phrase for HTTP Code
     *
     * @param mixed $p_code
     *
     * @return mixed|string
     */
    public static function getReasonMessageForCode($p_code)
    {
        if (array_key_exists($p_code, self::$phrases)) {
            return self::$phrases[$p_code];
        }
        return '';
    }

    /**
     *
     * @param unknown $pname
     * @param unknown $p_args
     */
    public function __call($pname, $p_args)
    {
    }

    /**
     * Sauvegarde en session
     *
     * @param \FreeFW\Interfaces\Session $p_session
     */
    protected function storeToSession(\FreeFW\Interfaces\Session $p_session)
    {
        if (is_array($this->errors) && count($this->errors) > 0) {
            $ses = json_encode($this->errors);
            $p_session->set('responseerrors', $ses);
        } else {
            $p_session->remove('responseerrors');
        }
        if ($this->content !== null) {
            $p_session->set('responsecontent', serialize($this->content));
        } else {
            $p_session->remove('responsecontent');
        }
    }

    /**
     * Récupération de la session
     *
     * @param \FreeFW\Interfaces\Session $p_session
     */
    protected function restoreFromSession(\FreeFW\Interfaces\Session $p_session)
    {
        $ses = $p_session->get('responseerrors');
        if ($ses !== null && $ses !== false && $ses != '') {
            $arr = json_decode($ses, true);
            if (is_array($arr)) {
                $this->errors = $arr;
            } else {
                $this->errors = [];
            }
        } else {
            $this->errors = [];
        }
        $ses = $p_session->get('responsecontent');
        if ($ses !== null && $ses !== false && $ses != '') {
            $this->content = unserialize($ses);
        } else {
            $this->content = null;
        }
        $p_session->remove('responseerrors');
        $p_session->remove('responsecontent');
    }
}
