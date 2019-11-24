<?php
/**
 * Router en mode console
 *
 * @author jeromeklam
 * @package Routing
 * @category Console
 */
namespace FreeFW\Console;

/**
 * Router console
 * @author jeromeklam
 */
class Router
{

    /**
     * Behaviour
     */
    use \Psr\Log\LoggerAwareTrait;

    /**
     * Instance
     *
     * @var Router
     */
    protected static $instance = null;

    /**
     * Commands
     * @var \FreeFW\Console\CommandCollection
     */
    protected $commands = null;

    /**
     * 
     */
    public function __construct()
    {
        $this->commands = new \FreeFW\Console\CommandCollection();
    }

    /**
     * Reprise des commandes depuis un fichier de config
     * array['commands'] = array(
     *     'test' => array('test', '\Yzynet\Controller\Client'
     *     ),
     *     ...
     * );
     *
     * @param array $p_config
     *
     * @return \FreeFW\Console\Router
     */
    protected static function getInstance(array $p_config = null)
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        
        return self::$instance;
    }
    
    /**
     * Add routes
     *
     * @param \FreeFW\Router\RouteCollection $p_collection
     *
     * @return \FreeFW\Http\Router
     */
    public function addCommands(\FreeFW\Console\CommandCollection $p_collection)
    {
        $this->commands->addCommands($p_collection);
        return $this;
    }

    /**
     * Fina a command
     * 
     * @param \FreeFW\Console\Input\Input $p_input
     * 
     * @return boolean
     */
    public function findCommand(\FreeFW\Console\Input\Input $p_input)
    {
        $command = false;
        foreach ($this->commands->getCommands() as $oneCommand) {
            if (strtolower($oneCommand->getName()) == strtolower($p_input->getCommand())) {
                $command = $oneCommand;
                break;
            }
        }
        return $command;
    }
}
