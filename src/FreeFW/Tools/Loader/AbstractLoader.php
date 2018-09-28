<?php
/**
 * Gestion de la partie abstraite d'un loader
 *
 * @author jeromeklam
 * @package Tools
 * @category Loader
 */
namespace FreeFW\Tools\Loader;

/**
 * Loader
 * @author jeromeklam
 */
abstract class AbstractLoader
{

    /**
     * Instane de la classe
     * @var \Static
     */
    protected static $instance = null;

    /**
     * Fichier
     * @var string
     */
    protected $file = null;

    /**
     * Les données
     * @var mixed $datas
     */
    protected $datas = null;

    /**
     * Constructeur
     *
     * @param string $p_file
     */
    protected function __construct($p_file)
    {
        $this->file = $p_file;
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
            self::$instance = new static($p_file);
        }
        
        return self::$instance;
    }

    /**
     * Récupération des données
     *
     * @return array
     */
    public function getDatas()
    {
        if ($this->datas === null) {
            $this->datas = $this->readDatas();
        }
        
        return $this->datas;
    }

    /**
     * Lecture des données depuis le fichier
     *
     * @return array
     */
    abstract protected function readDatas();
}
