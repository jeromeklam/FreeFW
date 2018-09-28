<?php
namespace FreeFW\Http;

/**
 *
 * @author jeromeklam
 */
class Cookie
{

    /**
     * Instance
     *
     * @var \FreeFW\Http\Cookie
     */
    protected static $instance;

    /**
     * Cookies
     * @var array
     */
    protected $cookies = null;

    /**
     * Constructeur
     */
    protected function __construct()
    {
        $this->cookies = array_merge($_COOKIE, getallheaders());
    }

    /**
     * Retourne une instance
     *
     * @return \FreeFW\Http\Cookie
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new static();
        }
        return self::$instance;
    }

    /**
     * Vérifie l'existence
     *
     * @param string $p_name
     *
     * @return boolean
     */
    public function has($p_name)
    {
        if (array_key_exists($p_name, $this->cookies)) {
            return true;
        }
        return false;
    }

    /**
     * Retiurne la veleur d'un cookie
     *
     * @param string $p_name
     *
     * @return mixed
     */
    public function get($p_name)
    {
        if (array_key_exists($p_name, $this->cookies)) {
            return $this->cookies[$p_name];
        }
        return false;
    }

    /**
     * Affectation d'un cookie
     *
     * @param string $p_name
     * @param mixed  $p_value
     *
     * @return \FreeFW\Http\Cookie
     */
    public function set($p_name, $p_value, $p_expire = 0, $p_path = '/', $p_secure = false)
    {
        $this->cookies[$p_name] = $p_value;
        setcookie($p_name, $p_value, $p_expire, $p_path, $p_secure);
        return $this;
    }
}
