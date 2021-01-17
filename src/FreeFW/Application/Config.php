<?php
/**
 * Classe de gestion de base pour la gestion des configurations
 *
 * @author jeromeklam
 * @package Config
 * @category Tech
 */
namespace FreeFW\Application;

/**
 * Gestion de la configuration
 * @author jeromeklam
 */
class Config
{

    /**
     *
     * @var static
     */
    protected static $factory = null;

    /**
     * Le fichier
     * @var string
     */
    protected $file = null;

    /**
     * Le loader
     * @var \FreeFW\Tools\Loader\Loader
     */
    protected $loader = null;

    /**
     * Chargé
     * @var boolean
     */
    protected $loaded = false;

    /**
     * La configuration
     * @var array
     */
    protected $config = array();

    /**
     * Multiton
     *
     * @param string $p_file
     *
     * @return void
     */
    private function __construct($p_file)
    {
        $this->file   = $p_file;
        $this->loaded = false;
        $this->config = array();
        $this->loader = \FreeFW\Tools\Loader\Loader::getInstance($this->file);
    }

    /**
     * Lecture de la configuration
     *
     * @return \FreeFW\Application\Config
     */
    protected function readConfig()
    {
        if ($this->loaded == false) {
            $this->config = $this->loader->getDatas();
            $this->loaded = true;
        }

        return $this;
    }

    /**
     * Retourne l'instance
     *
     * @var string $p_file
     *
     * @return \FreeFW\Application\Config
     */
    public static function getInstance($p_file = null)
    {
        return self::getFactory('FreeFW', $p_file);
    }

    /**
     * Retourne l'instance
     *
     * @var string $p_name
     * @var string $p_file
     *
     * @return \FreeFW\Application\Config
     */
    public static function getFactory($p_name, $p_file = null)
    {
        if (self::$factory === null) {
            self::$factory = array();
        }
        if (!isset(self::$factory[$p_name])) {
             self::$factory[$p_name] = new self($p_file);
        }

        return self::$factory[$p_name];
    }

    /**
     * Load config
     *
     * @var string $p_file
     *
     * @return \FreeFW\Application\Config
     */
    public static function load($p_file)
    {
        $config = self::getInstance($p_file);

        return $config;
    }

    /**
     * Retourne la tebleau de la config
     *
     * @return array
     */
    public function getAsArray()
    {
        if ($this->loaded === false) {
            $this->readConfig();
        }

        return $this->config;
    }

    /**
     * Retourne la valeur d'une clef
     *
     * @var string $p_key
     * @var array  $p_arr
     * @var mixed  $p_default
     *
     * @return mixed
     */
    protected function getValue($p_key, $p_arr, $p_default)
    {
        $parts = explode(':', $p_key);
        if (count($parts) > 1) {
            if (isset($p_arr[$parts[0]])) {
                $crt = $parts[0];
                array_shift($parts);
                $key = implode(':', $parts);
                return $this->getValue($key, $p_arr[$crt], $p_default);
            }
        } else {
            if (isset($p_arr[$p_key])) {
                return $p_arr[$p_key];
            }
        }
        return $p_default;
    }

    /**
     * Mise à jour de la valeur d'une clef
     *
     * @var string $p_key
     * @var mixed  $p_value
     *
     * @return mixed
     */
    protected function setValue($p_key, $p_value)
    {
        $parts = explode(':', $p_key);
        $parts = array_reverse($parts);
        $val   = $p_value;
        $arr   = [];
        foreach ($parts as $idx => $value) {
            $tmp         = [];
            $tmp[$value] = $val;
            $arr         = $tmp;
            $val         = $tmp;
        }
        $this->config = array_replace_recursive($this->config, $arr);
    }

    /**
     * Retourne la valeur d'une clef
     *
     * @var string $p_key
     * @var mixed  $p_default
     *
     * @return mixed
     */
    public function get($p_key, $p_default = false)
    {
        return $this->getValue($p_key, $this->getAsArray(), $p_default);
    }

    /**
     * Mise à jour d'une clef
     *
     * @param string $p_key
     * @param mixed  $p_default
     *
     * @return \FreeFW\Application\Config
     */
    public function set($p_key, $p_default)
    {
        $this->setValue($p_key, $p_default);
        return $this;
    }
}
