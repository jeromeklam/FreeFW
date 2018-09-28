<?php
namespace FreeFW\Development\Api;

/**
 *
 * @author jerome.klam
 *
 */
abstract class Api
{

    /**
     * Comportement
     */
    use \FreeFW\Behaviour\DI;
    use \FreeFW\Behaviour\LoggerAwareTrait;

    /**
     * Nom de l'api
     * @var string
     */
    protected $name = null;

    /**
     * Base url
     * @var string
     */
    protected $base = '';

    /**
     * Description de l'api
     * @var string
     */
    protected $description = null;

    /**
     * Versions
     * @var array
     */
    protected $versions = array();

    /**
     * Liste des packages
     * @var array
     */
    protected $packages = array();

    /**
     * Liste des contacts
     * @var array
     */
    protected $contacts = [];

    /**
     * Licence
     * @var \FreeFW\Development\Api\Licence
     */
    protected $licence = null;

    /**
     * Serveurs
     * @var array
     */
    protected $servers = [];

    /**
     * Middlewares
     * @var array
     */
    protected $middlewares = [];

    /**
     * Documentation
     * @var array
     */
    protected $doc = [];

    /**
     * Constructeur
     *
     * @param string $p_filename
     */
    public function __construct()
    {
        $config             = self::getDIConfig();
        $this->middlewares  = $config->get('app:middlewares:micro');
    }

    /**
     * Retourne vrai si on utilise une API json-api
     *
     * @return boolean
     */
    protected function isJsonApi()
    {
        if (array_key_exists('API', $this->middlewares)) {
            if (in_array('json_api', $this->middlewares['API'])) {
                return true;
            }
        }
        return false;
    }

    /**
     * Retourne vrai si on utilise une API json-api
     *
     * @return boolean
     */
    protected function isServerSecure()
    {
        if (array_key_exists('SECURE', $this->middlewares)) {
            if (in_array('server', $this->middlewares['SECURE'])) {
                return true;
            }
        }
        return false;
    }

    /**
     * Ajout des routes sur une version
     *
     * @return \FreeFW\Development\Api\Version
     */
    protected function addRoutes($p_version, $p_routeFile, $p_middleware)
    {
        if (is_file($p_routeFile)) {
            include($p_routeFile);
            foreach ($routes as $rName => $oneRoute) {
                $newRoute = new \FreeFW\Router\Route(
                    $oneRoute['url'],
                    $rName,
                    str_replace('.', '::', $oneRoute['controller']),
                    explode(',', $oneRoute['methods'])
                    );
                if (array_key_exists('version', $oneRoute)) {
                    $newRoute->setVersion($oneRoute['version']);
                }
                if (array_key_exists('role', $oneRoute)) {
                    $newRoute->setRole($oneRoute['role']);
                }
                if (array_key_exists('secured', $oneRoute)) {
                    $newRoute->setSecure($oneRoute['secured']);
                }
                if (array_key_exists('auth', $oneRoute)) {
                    $newRoute->setAuth($oneRoute['auth']);
                }
                if (array_key_exists('type', $oneRoute)) {
                    $newRoute->setType($oneRoute['type']);
                }
                if (array_key_exists('filters', $oneRoute)) {
                    $newRoute->setFilters($oneRoute['filters'], true);
                }
                if (array_key_exists('force', $oneRoute)) {
                    $newRoute->setForcedMethod($oneRoute['force']);
                }
                if (array_key_exists('menus', $oneRoute)) {
                    $newRoute->setMenus($oneRoute['menus']);
                }
                if (array_key_exists('middleware', $oneRoute)) {
                    $newRoute->setMiddlewares($oneRoute['middleware']);
                }
                if (array_key_exists('package', $oneRoute)) {
                    $newRoute->setPackage($oneRoute['package']);
                }
                if (array_key_exists('help', $oneRoute)) {
                    $newRoute->setDescription($oneRoute['help']);
                } else {
                    if (array_key_exists('description', $oneRoute)) {
                        $newRoute->setDescription($oneRoute['description']);
                    }
                }
                if (isset($oneRoute['help'])) {
                    $help = $oneRoute['help'];
                    if (array_key_exists('title', $help)) {
                        $newRoute->setTitle($help['title']);
                    }
                    if (array_key_exists('params', $help)) {
                        foreach ($help['params'] as $pName => $oneParam) {
                            $param = new \FreeFW\Router\Param();
                            $param
                                ->setName($pName)
                                ->setDescription($oneParam['description'])
                                ->setType($oneParam['type'])
                                ->setFrom($oneParam['from'])
                            ;
                            if (array_key_exists('required', $oneParam)) {
                                $param->setRequired($oneParam['required']);
                            }
                            if (array_key_exists('default', $oneParam)) {
                                $param->setDefault($oneParam['default']);
                            }
                            $newRoute->addParameter($param);
                        }
                    }
                    if (array_key_exists('results', $help)) {
                        foreach ($help['results'] as $pHttp => $oneResult) {
                            $result = new \FreeFW\Router\Result();
                            $result->setHttp($pHttp);
                            if (array_key_exists('type', $oneResult)) {
                                $result->setType($oneResult['type']);
                            }
                            if (array_key_exists('object', $oneResult)) {
                                $result->setObject($oneResult['object']);
                            }
                            if (array_key_exists('comments', $oneResult)) {
                                $result->setComments($oneResult['comments']);
                            }
                            $newRoute->addResult($result);
                        }
                    }
                }
                $p_version->attachRoute($newRoute);
            }
        }
        return $p_version;
    }

    /**
     * Chargement
     *
     * @throws \Exception
     */
    public function load()
    {
        $config = self::getDIConfig();
        $middle = $config->get('app:middlewares:micro', array());
        $this
            ->setName($config->get('app:camel'))
            ->setDescription($config->get('app:title'))
        ;
        $contacts = $config->get('app:contacts');
        if (is_array($contacts)) {
            foreach ($contacts as $type => $oneContact) {
                $ctx = \FreeFW\Development\Api\Contact::getFromConfig($oneContact);
                if ($type == 'main') {
                    $ctx->setType(\FreeFW\Development\Api\Contact::TYPE_MAIN);
                }
                $this->addContact($ctx);
            }
        }
        $licence = $config->get('app:licence');
        if (is_array($licence)) {
            $this->setLicence(\FreeFW\Development\Api\Licence::getFromConfig($licence));
        }
        $servers = $config->get('app:servers');
        if (is_array($servers)) {
            foreach ($servers as $env => $infos) {
                $server = \FreeFW\Development\Api\Server::getFromConfig($infos);
                $server->setDescription($env);
                $this->addServer($server);
            }
        }
        $appModules = self::getDIModules();
        $versions   = $config->get('app:versions');
        foreach ($versions as $versName => $versModules) {
            $version = new \FreeFW\Development\Api\Version();
            $version
                ->setName($versName)
                ->setDate(\FreeFW\Tools\Date::mysqlToDatetime($versModules['date']))
            ;
            foreach ($versModules['modules'] as $moduleName => $moduleVersion) {
                foreach ($appModules as $idxM => $oneModule) {
                    $oneModule['packages'] = [];
                    $oneModule['apiVers']  = $moduleVersion;
                    $genApi                = false;
                    if (strtoupper($oneModule['ns']) == strtoupper($moduleName)) {
                        $genApi  = true;
                    }
                    $apiBPath = rtrim($oneModule['path'], '/') . '/' . rtrim($oneModule['short'], '/');
                    $apiBPath = $apiBPath . '/src/' . str_replace('.', '/', $oneModule['ns']);
                    $apiPath  = $apiBPath . '/Resources/Routes/';
                    $apiDoc   = $apiBPath . '/Resources/schema.csv';
                    $apiFile  = $apiPath . 'api.php';
                    if (is_file($apiFile)) {
                        $api = include($apiFile);
                        if (array_key_exists('packages', $api)) {
                            $this->addPackages($api['packages']);
                            $oneModule['packages'] = $api['packages'];
                        }
                        if (array_key_exists('versions', $api)) {
                            foreach ($api['versions'] as $idxVV => $apiVers) {
                                $oneModule['apiVers'] = $idxVV;
                                break;
                            }
                        }
                        if ($genApi) {
                            // Add routes...
                            $routeFile = $apiPath . strtoupper($moduleVersion) . '/micro.php';
                            $this->addRoutes($version, $routeFile, $middle);
                            $this->addDoc($apiDoc);
                        }
                    } else {
                        if ($genApi) {
                            throw new \Exception(sprintf('%s has not api.php file !', $moduleName));
                        }
                    }
                    $oneModule['basePath'] = $apiBPath;
                    $oneModule['generate'] = $genApi;
                    $version->addModule($oneModule);
                }
            }
            // Je ne traite que la première version du tableau, censée être la dernière
            $this->addVersion($version);
            break;
        }
    }

    /**
     * Affectation du nom
     *
     * @param string $p_name
     *
     * @return \FreeFW\Development\Api\Api
     */
    public function setName($p_name)
    {
        $this->name = $p_name;
        return $this;
    }

    /**
     * Retourne le nom
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Affectation du répertoire de base
     *
     * @param string $p_base
     *
     * @return \FreeFW\Development\Api\Api
     */
    public function setBase($p_base)
    {
        $this->base = $p_base;
        return $this;
    }

    /**
     * Retourne le répertoire de base
     *
     * @return string
     */
    public function getBase()
    {
        return $this->base;
    }

    /**
     * Affectation de la description
     *
     * @param string $p_desc
     *
     * @return \FreeFW\Development\Api\Api
     */
    public function setDescription($p_desc)
    {
        $this->description = $p_desc;
        return $this;
    }

    /**
     * Récupération de la description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Ajout d'une version
     *
     * @param \FreeFW\Development\Api\Version $p_version
     *
     * @return \FreeFW\Development\Api\Api
     */
    public function addVersion($p_version)
    {
        $this->versions[] = $p_version;
        return $this;
    }

    /**
     * Ajout d'un fichier de documentation
     *
     * @param string $p_filename
     *
     * @return \FreeFW\Development\Api\Api
     */
    public function addDoc ($p_filename)
    {
        if (is_file($p_filename)) {
            $h = fopen($p_filename, 'r');
            if ($h) {
                while ($line = fgetcsv($h, null, ';')) {
                    $key = strtolower($line[0] . '.' . $line[1]);
                    $val = $line[6];
                    $this->doc[$key] = $val;
                }
                fclose($h);
            }
        }
        return $this;
    }

    /**
     * Retourne l'aide d'un champ
     *
     * @param string $p_key
     *
     * @return string|false
     */
    public function getDoc ($p_key)
    {
        if (array_key_exists($p_key, $this->doc)) {
            return $this->doc[$p_key];
        }
        return false;
    }

    /**
     * Retourne les versions
     *
     * @return array
     */
    public function getVersions()
    {
        return $this->versions;
    }

    /**
     * Affectation des packages
     *
     * @param array $p_packages
     *
     * @return \FreeFW\Development\Api\Api
     */
    public function setPackages($p_packages)
    {
        $this->packages = $p_packages;
        return $this;
    }

    /**
     * Ajout e packages
     *
     * @param array $p_packages
     *
     * @return \FreeFW\Development\Api\Api
     */
    public function addPackages($p_packages)
    {
        $this->packages = array_merge($this->packages, $p_packages);
        return $this;
    }

    /**
     * Retourne les packages
     *
     * @return array
     */
    public function getPackages()
    {
        return $this->packages;
    }

    /**
     * Retourne un package
     *
     * @return array
     */
    public function getPackage($p_name)
    {
        foreach ($this->packages as $name => $props) {
            if (strtoupper($name) == strtoupper($p_name)) {
                return $props;
            }
        }
        return false;
    }

    /**
     * Ajout d'un contact
     *
     * @param \FreeFW\Development\Api\Contact $p_contact
     *
     * @return \FreeFW\Development\Api\Api
     */
    public function addContact($p_contact)
    {
        $this->contacts[] = $p_contact;
        return $this;
    }

    /**
     * Retourne la liste des contacts
     *
     * @return array
     */
    public function getContacts()
    {
        return $this->contacts;
    }

    /**
     * Affectation de la licence
     *
     * @param \FreeFW\Development\Api\Licence $p_licence
     *
     * @return \FreeFW\Development\Api\Api
     */
    public function setLicence($p_licence)
    {
        $this->licence = $p_licence;
        return $this;
    }

    /**
     * Retourne la licence
     *
     * @return \FreeFW\Development\Api\Licence
     */
    public function getLicence()
    {
        return $this->licence;
    }

    /**
     * Purge des serveurs
     *
     * @return \FreeFW\Development\Api\Api
     */
    public function flushServers()
    {
        $this->servers = array();
        return $this;
    }

    /**
     * Retourne la liste des serveurs
     *
     * @return array
     */
    public function getServers()
    {
        return $this->servers;
    }

    /**
     * Ajout d'un serveur
     *
     * @param \FreeFW\Development\Api\Server $p_server
     *
     * @return \FreeFW\Development\Api\Api
     */
    public function addServer($p_server)
    {
        $this->servers[] = $p_server;
        return $this;
    }

    /**
     * Retourne une route à partir pour un rôle
     *
     * @param string $p_version
     * @param string $p_object
     * @param string $p_role
     *
     * @return \FreeFW\Router\Route
     */
    public function findByRole($p_version, $p_object, $p_role)
    {
        foreach ($this->getVersions() as $idxV => $oneVersion) {
            foreach ($oneVersion->getAll() as $idxR => $oneRoute) {
                if ($oneRoute->getPackage() == $p_object) {
                    if ($oneRoute->getRole() == $p_role) {
                        return $oneRoute;
                    }
                }
            }
        }
        return false;
    }

    /**
     * Retourne le nom de la dernière version
     *
     * @return string
     */
    public function getLastVersionName()
    {
        foreach ($this->getVersions() as $idxV => $oneVersion) {
            return $oneVersion->getName();
        }
        return '';
    }

    /**
     * Retourne le nom de la dernière version
     *
     * @return \FreeFW\Development\Api\Version
     */
    public function getLastVersion()
    {
        foreach ($this->getVersions() as $idxV => $oneVersion) {
            return $oneVersion;
        }
        return false;
    }

    /**
     * Retourne la liste des routes de la version triées par url
     *
     * @param string $p_version
     *
     * @return array
     */
    public function getAllRoutes($p_version)
    {
        $routes = [];
        foreach ($this->getVersions() as $idxV => $oneVersion) {
            if (strtoupper($oneVersion->getName()) == strtoupper($p_version)) {
                $routes = $oneVersion->getAll();
                usort($routes, "\\FreeFW\\Core\\Router\\Route::compareTo");
                break;
            }
        }
        return $routes;
    }

    /**
     * Retourne la liste des types utilisés
     *
     * @param string $p_version
     *
     * @return array
     */
    public function getDistinctTypes($p_version)
    {
        return $this->getPackages();
        die;
        foreach ($this->getVersions() as $idxV => $oneVersion) {
            if (strtoupper($oneVersion->getName()) == strtoupper($p_version)) {
                //echo $oneVersion->getName();
                foreach ($oneVersion->getAll() as $idxR => $oneRoute) {
                    //echo $oneRoute->getName();
                    foreach ($oneRoute->getResults() as $idxT => $oneResult) {
                        if ($oneResult->getHttp() == '200') {
                            if ($oneResult->getType() == \FreeFW\Router\Result::TYPE_OBJECT) {
                                if (!array_key_exists($oneResult->getObject(), $types)) {
                                    $types[$oneResult->getObject()] = [];
                                }
                            }
                            if ($oneResult->getType() == \FreeFW\Router\Result::TYPE_ARRAY) {
                                if (!array_key_exists($oneResult->getObject(), $types)) {
                                    $types[$oneResult->getObject()] = [];
                                }
                            }
                        }
                    }
                }
                break;
            }
        }
        var_dump($types);die;
        return $types;
    }

    /**
     * Retourne l'url forméttée
     *
     * @param string $p_url
     *
     * @return mixed
     */
    protected function getStandardUrl($p_url)
    {
        $path = preg_replace_callback(
            "/\/(:\w+)/",
            function ($matches) {
                return '/{' . str_replace(':', '', $matches[1]) . '}';
            },
            $p_url
        );
        return $path;
    }
}
