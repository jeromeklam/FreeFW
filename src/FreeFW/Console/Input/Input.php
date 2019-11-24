<?php
/**
 * Classe de gestion d'une requête console
 *
 * @author jeromeklam
 * @package Routing
 * @category Console
 */
namespace FreeFW\Console\Input;

/**
 * Classe de gestion d'une requête console
 * @author jeromeklam
 */
class Input extends \FreeFW\Console\Input\AbstractInput
{

    /**
     * Liste des paramètres
     *
     * @var array
     */
    protected static $params = array();

    /**
     * La commande, paramètre 0
     *
     * @var string
     */
    protected static $command = null;

    /**
     * Instance
     * @var \FreeFW\Console\Input\Input
     */
    protected static $instance = null;

    /**
     * Retourne tous les parametres
     *
     * @return array
     */
    protected function __construct()
    {
        global $argv;
        global $argc;
        self::$params = array();
        $i            = 1;
        while ($i < $argc) {
            $param = $argv[$i];
            if ($i == 1) {
                self::$command = $param;
            } else {
                if (strlen($param) > 3 && strpos($param, "=") > 0 && substr($param, 0, 2) == '--') {
                    $tmp               = substr($param, 2);
                    $parts             = explode('=', $tmp);
                    self::$params[$parts[0]] = $parts[1];
                } else {
                    if (strlen($param) > 3 && substr($param, 0, 2) == '--') {
                        $tmp                = substr($param, 2);
                        self::$params[$tmp] = true;
                    } else {
                        self::$params[] = $param;
                    }
                }
            }
            $i++;
        }
    }

    /**
     * Get instance
     * 
     * @return \FreeFW\Console\Input\Input
     */
    public static function getFromGlobals()
    {
        if (self::$instance === null) {
            self::$instance = new static();
        }
        return self::$instance;
    }

    /**
     * Retourne la commande
     *
     * @return string
     */
    public function getCommand()
    {
        return self::$command;
    }

    /**
     * Retourne les paramètres
     *
     * @return array
     */
    public function getAttributes()
    {
        return self::$params;
    }

    /**
     * Merge de paramètres
     *
     * @param array $p_params
     *
     * @return \FreeFW\Console\Input\Input
     */
    public function mergeParams($p_params)
    {
        $this->getAttributes(); // Si ça n'a pas été fait...
        if (self::$params === null) {
            self::$params = $p_params;
        } else {
            self::$params = array_merge(self::$params, $p_params);
        }
        return $this;
    }

    /**
     * Retour le parametres souhaité
     *
     * @param string $p_id
     *
     * @return mixed
     */
    public function getAttribute($id)
    {
        $params = $this->getAttributes();
        if (array_key_exists($id, $params)) {
            return $params[$id];
        }
        return false;
    }

    /**
     * Vérifie l'existence d'un paramètre
     *
     * @param string $id
     *
     * @return boolean
     */
    public function hasAttribute($id)
    {
        $params = $this->getAttributes();
        if (array_key_exists($id, $params)) {
            return true;
        }
        return false;
    }

    /**
     * Retourne l'adresse
     *
     * @return string
     */
    public function getAddr()
    {
        return getHostByName(getHostName());
    }

    /**
     * Retourne la méthode utilisée
     *
     * @return string
     */
    public function getMethod()
    {
        return 'CMD';
    }

    /**
     * Retourne la requête sous forme de chaine
     *
     * @return string
     */
    public function __toString()
    {
        if (self::$command !== null) {
            return self::$command;
        }
        return '';
    }

    /**
     * Retourne le nom du programme appelant
     *
     * @return string
     */
    public function getCaller()
    {
        global $argv;
        $prg = 'console';
        if (is_array($argv) && count($argv) > 0) {
            $parts = pathinfo($argv[0]);
            $prg   = $parts['filename'];
        }
        return $prg;
    }

    /**
     * Ip du client
     *
     * @return string
     */
    public function getClientIp()
    {
        return '127.0.0.1';
    }

    public function read($p_name, $p_default)
    {}
}
