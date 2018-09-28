<?php
namespace FreeFW\Development\Api;

/**
 * Server
 * @author jerome.klam
 *
 */
class Server
{

    /**
     * Nom du serveur
     * @var string
     */
    protected $name = null;

    /**
     * Description du serveur
     * @var string
     */
    protected $description = null;

    /**
     * Url du serveur
     * @var string
     */
    protected $url = null;

    /**
     * Variables pour le serveur
     * @var array
     */
    protected $vars = [];

    /**
     * Répertoire des tests
     * @var string
     */
    protected $tests = false;

    /**
     * Headers
     * @var array
     */
    protected $headers = [];

    /**
     * Auth
     * @var array
     */
    protected $auth = [];

    /**
     * Création d'un serveur à partir de la configuration
     *
     * @param array $p_config
     *
     * @return \FreeFW\Development\Api\Server
     */
    public static function getFromConfig($p_config)
    {
        $server = new static();
        if (is_array($p_config)) {
            if (array_key_exists('role', $p_config)) {
                $server->setName($p_config['role']);
            }
            if (array_key_exists('url', $p_config)) {
                $server->setUrl($p_config['url']);
            }
            if (array_key_exists('params', $p_config)) {
                if (is_array($p_config['params'])) {
                    foreach ($p_config['params'] as $name => $oneParam) {
                        $param = \FreeFW\Development\Api\Param::getFromConfig($oneParam);
                        $param->setName($name);
                        $server->addVar($param);
                    }
                }
            }
            if (array_key_exists('tests', $p_config)) {
                $server->setTests($p_config['tests']);
            }
            if (array_key_exists('headers', $p_config)) {
                $server->setHeaders($p_config['headers']);
            }
            if (array_key_exists('auth', $p_config)) {
                $server->setAuth($p_config['auth']);
            }
        }
        return $server;
    }

    /**
     * Affectation du nom
     *
     * @param string $p_name
     *
     * @return \FreeFW\Development\Api\Server
     */
    public function setName($p_name)
    {
        $this->name = $p_name;
        return $this;
    }

    /**
     * Retourne le nom du serveur
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Affectation de la description
     *
     * @param string $p_desc
     *
     * @return \FreeFW\Development\Api\Server
     */
    public function setDescription($p_desc)
    {
        $this->description = $p_desc;
        return $this;
    }

    /**
     * Récupération de la desqcription
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Affectation de l'url
     *
     * @param string $p_url
     *
     * @return \FreeFW\Development\Api\Server
     */
    public function setUrl($p_url)
    {
        $this->url = $p_url;
        return $this;
    }

    /**
     * Récupération de l'url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Affectation du répertoire des tests
     *
     * @param string $p_tests
     *
     * @return \FreeFW\Development\Api\Server
     */
    public function setTests($p_tests)
    {
        $this->tests = $p_tests;
        return $this;
    }

    /**
     * Récupération du répertoire des tests
     *
     * @return string
     */
    public function getTests()
    {
        return $this->tests;
    }

    /**
     * Affectation des en-têtes
     *
     * @param mixed $p_headers
     *
     * @return \FreeFW\Development\Api\Server
     */
    public function setHeaders($p_headers)
    {
        $this->headers = $p_headers;
        return $this;
    }

    /**
     * Récupération des en-têtes
     *
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * Affectation de 'authentification
     *
     * @param mixed $p_auth
     *
     * @return \FreeFW\Development\Api\Server
     */
    public function setAuth($p_auth)
    {
        $this->auth = $p_auth;
        return $this;
    }

    /**
     * Retourne l'authentification
     *
     * @return array
     */
    public function getAuth()
    {
        return $this->auth;
    }

    /**
     * Purge des variables
     *
     * @return \FreeFW\Development\Api\Server
     */
    public function flushVars()
    {
        $this->vars = [];
        return $this;
    }

    /**
     * Retourne les variables
     *
     * @return array
     */
    public function getVars()
    {
        return $this->vars;
    }

    /**
     * Ajout d'une variable
     *
     * @param \FreeFW\Development\Api\Param $p_param
     *
     * @return \FreeFW\Development\Api\Server
     */
    public function addVar($p_param)
    {
        $this->vars[] = $p_param;
        return $this;
    }

    /**
     * Retourne un objet swagger version 3
     *
     * @param number $p_version
     *
     * @return \stdClass
     */
    public function getForSwagger($p_version = 3)
    {
        $server              = new \stdClass();
        $server->url         = $this->getUrl();
        $server->description = $this->getDescription();
        if (count($this->vars) > 0) {
            $vars = new \stdClass();
            foreach ($this->getVars() as $idxV => $oneVar) {
                $name          = $oneVar->getname();
                $vars->{$name} = $oneVar->getForSwagger();
            }
            $server->variables = $vars;
        }
        return $server;
    }

    /**
     * Retourne les en-têtes de sortie
     *
     * @param string $p_auth
     *
     * @return array
     */
    public function getOutHeaders($p_auth = false)
    {
        $headers = [];
        if (is_array($this->headers) && count($this->headers) > 0) {
            if (array_key_exists('out', $this->headers)) {
                foreach ($this->headers['out'] as $idxO => $out) {
                    $headers[$idxO] = $out;
                }
            }
        }
        if ($p_auth !== false && is_array($this->auth) && count($this->auth) > 0) {
            if (array_key_exists('users', $this->auth)) {
                if (array_key_exists($p_auth, $this->auth['users'])) {
                    $user = $this->auth['users'][$p_auth];
                    //
                    $headers[$this->auth['name']] = [
                        'type'  => 'string',
                        'value' => sprintf($this->auth['value'], $user['token'])
                    ];
                }
            }
        }
        return $headers;
    }
}
