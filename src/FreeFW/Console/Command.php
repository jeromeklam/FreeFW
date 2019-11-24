<?php
/**
 * Classe de base des commandes
 *
 * @autgor jeromeklam
 * @package Command
 */
namespace FreeFW\Console;

/**
 * Classe de base d'une commande
 * @author jeromeklam
 */
class Command
{

    /**
     * Comportements
     */
    use \FreeFW\Behaviour\ConfigAwareTrait;

    /**
     * Command name
     * @var string
     */
    protected $name = null;
    
    /**
     * Command controller
     * @var string
     */
    protected $controller = null;
    
    /**
     * Function
     * @var string
     */
    protected $function = null;

    /**
     * Set name
     * 
     * @param string $p_name
     * 
     * @return \FreeFW\Console\Command
     */
    public function setName($p_name)
    {
        $this->name = $p_name;
        return $this;
    }

    /**
     * Get name
     * 
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set controller
     * 
     * @param string $p_controller
     * 
     * @return \FreeFW\Console\Command
     */
    public function setController($p_controller)
    {
        $this->controller = $p_controller;
        return $this;
    }

    /**
     * Get controller
     *
     * @return string
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * Set function
     * 
     * @param string $p_function
     * 
     * @return \FreeFW\Console\Command
     */
    public function setFunction($p_function)
    {
        $this->function = $p_function;
        return $this;
    }

    /**
     * Get function
     * 
     * @return string
     */
    public function getFunction()
    {
        return $this->function;
    }
}
