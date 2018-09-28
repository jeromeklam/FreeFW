<?php
/**
 * Classe de standardisation des loaders
 *
 * @author jeromeklam
 * @package Loader
 * @category Tools
 */
namespace FreeFW\Tools\Loader;

/**
 * Cmasse Loader
 * @author jeromeklam
 */
class Loader
{

    /**
     * Instane de la classe
     * @var \Static
     */
    protected static $instance = null;

    /**
     * Loader
     * @var \FreeFW\Tools\Loader\AbstractLoader
     */
    protected $loader = null;

    /**
     * Constructeur
     *
     * @param string $p_file
     */
    protected function __construct($p_file)
    {
        if (!is_file($p_file)) {
            // @todo
        }
        $parts = pathinfo($p_file);
        switch (strtolower($parts['extension'])) {
            default:
                $this->loader = \FreeFW\Tools\Loader\Php::getInstance($p_file);
                break;
        }
    }
    
    /**
     * Retourne une instance
     *
     * @param string $p_file
     *
     * @retuen \Static
     */
    public static function getInstance($p_file)
    {
        if (self::$instance === null) {
            self::$instance = new self($p_file);
        }
        
        return self::$instance;
    }

    /**
     * Retourne les données
     *
     * @return array
     */
    public function getDatas()
    {
        if ($this->loader !== null) {
            return $this->loader->getDatas();
        }
        
        return array();
    }
}
