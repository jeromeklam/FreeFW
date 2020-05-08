<?php
namespace FreeFW\OpenApi\V3;

/**
 * OpenApi v3 specifications
 *
 * @author jeromeklam
 */
class OpenApi extends \FreeFW\OpenApi\V3\Base
{

    /**
     * Version
     * @var string
     */
    protected $openapi = '3.0.0';

    /**
     * Config
     * @var array
     */
    protected $config = null;

    /**
     * Info Object
     * @var \FreeFW\OpenApi\V3\Info
     */
    protected $info = null;

    /**
     * Servers
     * @var [\FreeFW\OpenApi\V3\Server]
     */
    protected $servers = null;

    /**
     * Paths
     * @var \FreeFW\OpenApi\V3\Paths
     */
    protected $paths = null;

    /**
     * Components
     * @var \FreeFW\OpenApi\V3\Components
     */
    protected $components = null;

    /**
     * Initialisation à partir de la configuration, commun à tous les appels.
     */
    public function init()
    {
        $config = $this->getConfig();
        /**
         * La partie information
         */
        $info = \FreeFW\DI\DI::get('\FreeFW\OpenApi\V3\Info');
        // Titre
        $info->setTitle($config->get('name', null));
        // Version
        $info->setVersion($config->get('version', null));
        // Contact
        $authors = $config->get('authors', []);
        if (is_array($authors) && count($authors) > 0) {
            $contact = null;
            foreach ($authors as $oneAuthor) {
                $contact = \FreeFW\DI\DI::get('\FreeFW\OpenApi\V3\Contact');
                $contact
                    ->setName($oneAuthor['name'])
                    ->setEmail($oneAuthor['email'])
                ;
                break;
            }
            $info->setContact($contact);
        }
        // License
        $licenseObj = $config->get('license', false);
        if ($licenseObj) {
            $license = \FreeFW\DI\DI::get('\FreeFW\OpenApi\V3\License');
            $license
                ->setName($licenseObj['name'])
                ->setUrl($licenseObj['url'])
            ;
            $info->setLicense($license);
        }
        $this->info = $info;
        /**
         * Servers
         */
        $serversObj = $config->get('servers', []);
        if (is_array($serversObj) && count($serversObj) > 0) {
            $servers = [];
            foreach ($serversObj as $oneServer) {
                $server = \FreeFW\DI\DI::get('\FreeFW\OpenApi\V3\Server');
                $server
                    ->setUrl($oneServer['url'])
                    ->setDescription($oneServer['description'])
                ;
                if (array_key_exists('vars', $oneServer)) {
                    $vars = [];
                    foreach ($oneServer['vars'] as $varName => $varProps) {
                        $serverVariable = \FreeFW\DI\DI::get('\FreeFW\OpenApi\V3\ServerVariable');
                        $serverVariable->setDefault($varProps['default']);
                        if (array_key_exists('description', $varProps)) {
                            $serverVariable->setDescription($varProps['description']);
                        }
                        if (array_key_exists('enum', $varProps)) {
                            $serverVariable->setEnum($varProps['enum']);
                        }
                        $vars[$varName] = $serverVariable;
                    }
                    $server->setVariables($vars);
                }
                $servers[] = $server;
            }
            $this->servers = $servers;
        }
    }

    /**
     * Add one path
     *
     * @param string                       $p_url
     * @param \FreeFW\OpenApi\V3\Schema    $p_path
     * @param string                       $p_method
     * @param \FreeFW\OpenApi\V3\Operation $p_operation
     *
     * @return \FreeFW\OpenApi\V3\OpenApi
     */
    public function addPathsPathOrOperation($p_url, $p_path, $p_method = null, $p_operation = null)
    {
        if (!is_array($this->paths)) {
            $this->paths = [];
        }
        if (!array_key_exists($p_url, $this->paths)) {
            $this->paths[$p_url] = $p_path;
        }
        $this->paths[$p_url]->addOperation($p_method, $p_operation);
        return $this;
    }

    /**
     * Add one schema to components
     *
     * @param string                    $p_name
     * @param \FreeFW\OpenApi\V3\Schema $p_schema
     *
     * @return \FreeFW\OpenApi\V3\OpenApi
     */
    public function addComponentsSchema($p_name, $p_schema)
    {
        if (!$this->components instanceof \FreeFW\OpenApi\V3\Components) {
            $this->components = \FreeFW\DI\DI::get('\FreeFW\OpenApi\V3\Components');
        }
        $this->components->add($p_name, $p_schema);
        return $this;
    }
}
