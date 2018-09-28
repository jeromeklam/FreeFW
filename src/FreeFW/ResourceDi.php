<?php
/**
 * Gestion des ressources communes (shared)
 *
 * @author jeromeklam
 * @package DI
 * @category Application
 */
namespace FreeFW;

use \Psr\Http\Message\ResponseFactoryInterface;
use \Psr\Http\Message\ResponseInterface;
use \Psr\Http\Message\StreamInterface;
use \FreeFW\Http\Response;

/**
 * Singleton et container des différents élements
 * @author jeromeklam
 */
class ResourceDi implements ResponseFactoryInterface
{

    /**
     * Instance
     *
     * @var FreeFW\ResourceDI
     */
    private static $instance = null;

    /**
     * Server SSO
     *
     * @var \FreeFW\Interfaces\SSO
     */
    private $sso = false;

    /**
     * Requête
     *
     * @var \FreeFW\Interfaces\Request
     */
    private $request = false;

    /**
     * Configuration
     *
     * @var \FreeFW\Application\Config
     */
    private $config = false;

    /**
     * Router
     *
     * @var \FreeFW\Interfaces\Router
     */
    private $router = false;

    /**
     * Objets partagés
     *
     * @var array
     */
    private $shared = array();

    /**
     * Liste des modules
     *
     * @var Array
     */
    private $modules = array();

    /**
     * DB connexions
     * @var array
     */
    private $connexions = array();

    /**
     * Middlewares
     * @var array
     */
    private $middlewares = array();

    /**
     * Constructeur protégé
     */
    protected function __construct()
    {
    }

    /**
     * Destructeur
     */
    public function __destruct()
    {
        $fields = '';
        foreach ($this->shared as $key => $object) {
            $object = null;
        }
    }

    /**
     * Retourne une instance
     *
     * @return \Spaig\ResourceDI
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Affectation du serveur SSO
     *
     * @param \FreeFW\Interfaces\SSO $p_sso
     *
     * @return this
     */
    public function setSSO(\FreeFW\Interfaces\SSO $p_sso)
    {
        $this->sso = $p_sso;
        return $this;
    }

    /**
     * Retourne le serveur SSO
     *
     * @return \FreeFW\Interfaces\SSO
     */
    public function getSSO()
    {
        return $this->sso;
    }

    /**
     * Retourne la requête
     *
     * @return \FreeFW\Interfaces\Request
     */
    public function getRequest()
    {
        if ($this->request === false) {
            $this->request = new \FreeFW\Http\Request();
        }
        return $this->request;
    }

    /**
     * Affectation de la requête
     *
     * @param \FreeFW\Interfaces\Request $p_request
     *
     * @return \FreeFW\ResourceDi
     */
    public function setRequest($p_request)
    {
        $this->request = $p_request;
        return $this;
    }

    /**
     * Set config
     *
     * @param \FreeFW\Application\Config $p_config
     *
     * @return \FreeFW\DI
     */
    public function setConfig($p_config)
    {
        $this->config = $p_config;
        return $this;
    }

    /**
     * Get config
     *
     * @return \FreeFW\Application\Config
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Affectation du router
     *
     * @param \FreeFW\Interfaces\Router $p_router
     *
     * @return \FreeFW\resourceDI
     */
    public function setRouter($p_router)
    {
        $this->router = $p_router;
        return $this;
    }

    /**
     * Retourne le router
     *
     * @return \FreeFW\Interfaces\Router
     */
    public function getRouter()
    {
        return $this->router;
    }

    /**
     * Ajout d'un nouveau module
     *
     * @param string $p_path
     * @param string $p_name
     * @param string $p_nameSpace
     * @param string $p_layout
     *
     * @return \FreeFW\Application\Base
     */
    public function registerModule($p_path, $p_name, $p_nameSpace, $p_layout)
    {
        if (!array_key_exists($p_name, $this->modules)) {
            $parts = explode('/', $p_name);
            $this->modules[] = array(
                'short'  => array_pop($parts),
                'path'   => $p_path,
                'name'   => $p_name,
                'ns'     => $p_nameSpace,
                'layout' => $p_layout
            );
        }
        return $this;
    }

    /**
     * Retourne les modules
     *
     * @return array
     */
    public function getModules()
    {
        return $this->modules;
    }

    /**
     * Partage de ressource
     *
     * @param string $p_name
     * @param mixed  $p_value
     *
     * @eturn this
     */
    public function setShared($p_name, $p_value)
    {
        $this->shared[$p_name] = $p_value;
        return $this;
    }

    /**
     * Récupération d'une ressource partagée
     *
     * @param string $p_name
     *
     * @return mixed
     */
    public function getShared($p_name)
    {
        if (array_key_exists($p_name, $this->shared)) {
            return $this->shared[$p_name];
        }
        return false;
    }

    /**
     * Le nom de la connexion dans le fichier de config
     *
     * @param string $p_cnx
     *
     * @return mixed
     */
    public function getConnexion($p_cnx = 'default')
    {
        if (!array_key_exists($p_cnx, $this->connexions)) {
            $config = $this->getConfig();
            $cfg    = $config->get('database', false);
            if (is_array($cfg)) {
                if (array_key_exists('dsn', $cfg)) {
                    if (strpos($cfg['dsn'], 'mysql') !== false) {
                        $this->connexions[$p_cnx] = new \FreeFW\Model\Connexion\Mysql(
                            $cfg['dsn'],
                            $cfg['user'],
                            $cfg['paswd']
                        );
                    } else {
                        throw new \Exception(sprintf('Database dsn %s not supported !', $p_cnx));
                    }
                } else {
                    if (array_key_exists($p_cnx, $cfg)) {
                        $cfg = $cfg[$p_cnx];
                        if (strpos($cfg['dsn'], 'mysql') !== false) {
                            $this->connexions[$p_cnx] = new \FreeFW\Model\Connexion\Mysql(
                                $cfg['dsn'],
                                $cfg['user'],
                                $cfg['paswd']
                            );
                        } else {
                            if (strpos($cfg['dsn'], 'oci') !== false) {
                                $this->connexions[$p_cnx] = new \FreeFW\Model\Connexion\Oracle(
                                    $cfg['dsn'],
                                    $cfg['user'],
                                    $cfg['paswd']
                                );
                            } else {
                                throw new \Exception(sprintf('Database dsn %s not supported !', $p_cnx));
                            }
                        }
                    } else {
                        throw new \Exception(sprintf('Database dsn %s not defined !', $p_cnx));
                    }
                }
            } else {
                throw new \Exception(sprintf('Database configuration %s not found in config !', $p_cnx));
            }
        }
        return $this->connexions[$p_cnx];
    }

    /**
     * Get registered models schemas
     *
     * @return array
     */
    public function getSchemas($p_version = '')
    {
        $schemas  = array();
        $provider = new \Neomerx\JsonApi\Factories\Factory();
        foreach ($this->getModules() as $idx => $oneModule) {
            $ns       = $oneModule['ns'];
            $realNs   = str_replace('.', '\\', $ns);
            $realPath = rtrim($oneModule['path'], '/') . '/' . ltrim($oneModule['name'], '/') .
                        '/src/' . str_replace('.', '/', $ns);
            $schema   = rtrim($realPath, '/') . '/Schema/';
            if ($p_version != '') {
                $schema = $schema . \FreeFW\Tools\PBXString::toCamelCase($p_version, true) . '/';
            }
            $realPath = rtrim($realPath, '/') . '/Model/';
            $iterator = new \DirectoryIterator($realPath);
            foreach ($iterator as $fileinfo) {
                if ($fileinfo->isFile()) {
                    $parts = pathinfo($fileinfo->getFilename());
                    if (file_exists($schema . $parts['filename'] . '.php')) {
                        $from = $realNs . '\\Model\\' . $parts['filename'];
                        if ($p_version == '') {
                            $to = $realNs . '\\Schema\\' . $parts['filename'];
                        } else {
                            $to = $realNs . '\\Schema\\' . \FreeFW\Tools\PBXString::toCamelCase($p_version, true) .
                                  '\\' . $parts['filename'];
                        }
                        $name = false;
                        if (class_exists($to)) {
                            $instance = new $to($provider);
                            $name     = $instance->getResourceType();
                        }
                        $schemas[$from] = [
                            'to'   => $to,
                            'name' => $name
                        ];
                    }
                }
            }
        }
        return $schemas;
    }

    /**
     * Create a new response.
     *
     * @param integer $code HTTP status code
     *
     * @return ResponseInterface
     */
    public function createResponse(int $code = 200, string $reasonPhrase = ''): ResponseInterface
    {
        $response = new Response($code);
        return $response;
    }

    /**
     * Create a custom response
     *
     * @param number $code
     * @param array  $headers
     * @param mixed  $body
     *
     * @return ResponseInterface
     */
    public function createCustomResponse($code = 200, $headers = array(), $body = null)
    {
        $response = new Response($code, $headers, $body);
        return $response;
    }

    /**
     * Add new middleware
     *
     * @param string $p_className
     *
     * @return \FreeFW\ResourceDi
     */
    public function addMiddleware($p_className)
    {
        $this->middlewares[] = $p_className;
        return $this;
    }

    /**
     * Get all middlewares
     *
     * @return array
     */
    public function getMiddlewares()
    {
        return $this->middlewares;
    }
    public function createStreamFromFile(string $filename, string $mode = 'r'): StreamInterface
    {}

    public function createStream(string $content = ''): StreamInterface
    {}

    public function createStreamFromResource($resource): StreamInterface
    {}

}
