<?php
namespace FreeFW\Development\Api;

/**
 *
 */
class Version extends \FreeFW\Router\RouteCollection
{

    /**
     * Nom de la version
     * @var string
     */
    protected $name = null;

    /**
     * Date
     * @var string
     */
    protected $date = null;

    /**
     * Modules à traiter
     * @var array
     */
    protected $modules = [];

    /**
     * Affectation du nom
     *
     * @param string $p_name
     *
     * @return \FreeFW\Development\Api\Version
     */
    public function setName($p_name)
    {
        $this->name = $p_name;
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
     * Affectation de la date
     *
     * @param string $p_date
     *
     * @return \FreeFW\Development\Api\Version
     */
    public function setDate($p_date)
    {
        $this->date = $p_date;
        return $this;
    }

    /**
     * Récupération de la date
     *
     * @return string
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Purge des modules
     *
     * @return \FreeFW\Development\Api\Version
     */
    public function flushModules()
    {
        $this->modules = [];
        return $this;
    }

    /**
     * Ajout d'un module
     *
     * @param array $p_module
     *
     * @return \FreeFW\Development\Api\Version
     */
    public function addModule($p_module)
    {
        $this->modules[$p_module['ns']] = $p_module;
        return $this;
    }

    /**
     * Retourne la liste des modules
     *
     * @return array
     */
    public function getModules()
    {
        return $this->modules;
    }
}
