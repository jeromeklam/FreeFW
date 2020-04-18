<?php
/**
 * Gestion des entrées de type paramètre
 *
 * @author jeromeklam
 * @package Console\Input
 */
namespace FreeFW\Console\Input;

/**
 * Classe des paramètres d'entrée de la console
 * @author jeromeklam
 */
class ParameterInput extends \FreeFW\Console\Input\AbstractInput
{

    /**
     * Paramètres
     * @var array
     */
    protected $params = array();

    /**
     * Constructeur
     *
     * @param array $p_params
     */
    public function __construct($p_params = array())
    {
        $this->setParams($p_params);
    }

    /**
     * Affectation des paramètres
     *
     * @param array $p_params
     *
     * @return static
     */
    public function setParams($p_params = array())
    {
        $this->params = $p_params;
        
        return $this;
    }

    /**
     * Retourne les paramètres
     *
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * Lecture d'un paramètre
     *
     * @param string $p_name
     * @param mixed  $p_default
     *
     * @return mixed
     */
    public function read($p_name, $p_default = null)
    {
        if (array_key_exists($p_name, $this->params)) {
            return $this->params[$p_name];
        }
        
        return $p_default;
    }
}
