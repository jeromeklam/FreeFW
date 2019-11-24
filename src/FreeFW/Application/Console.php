<?php
namespace FreeFW\Application;

/**
 * Application application
 *
 * @author jeromeklam
 */
class Console extends \FreeFW\Core\Console
{

    /**
     * Application instance
     * @var \FreeFW\Application\Application
     */
    protected static $instance = null;

    /**
     * Constructor
     *
     * @param \FreeFW\Application\Config $p_config
     */
    protected function __construct(
        \FreeFW\Application\Config $p_config,
        \Psr\Log\LoggerInterface $p_logger
    ) {
        parent::__construct($p_config, $p_logger);
    }

    /**
     * Get Application instance
     *
     * @param \FreeFW\Application\Config $p_config
     *
     * @return \FreeFW\Application\Application
     */
    public static function getInstance(
        \FreeFW\Application\Config $p_config,
        \Psr\Log\LoggerInterface $p_logger
    ) {
        if (self::$instance === null) {
            self::$instance = new static($p_config, $p_logger);
        }
        return self::$instance;
    }

    /**
     * Handle request
     */
    public function handle()
    {
        $this->logger->debug('Application.handle.start');
        try {
            $input   = \FreeFW\Console\Input\Input::getFromGlobals();
            
            $sso     = new \FreeFW\Console\SsoMock('4');
            \FreeFW\DI\DI::setShared('sso', $sso);
            
            
            $output  = new \FreeFW\Console\Output\ConsoleOutput();
            $command = $this->router->findCommand($input);
            if ($command) {
                $object = \FreeFW\DI\DI::get($command->getController());
                call_user_func_array([$object, $command->getFunction()], [$input, $output]);
            } else {
                $this->fireEvent(\FreeFW\Constants::EVENT_ROUTE_NOT_FOUND);
            }
            $this->afterRender();
        } catch (\Exception $ex) {
            // @todo : handle 500 response
            var_export($ex);
        }
        $this->logger->debug('Application.handle.end');
    }
}
