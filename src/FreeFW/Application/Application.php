<?php
namespace FreeFW\Application;

use GuzzleHttp;

/**
 * Application application
 *
 * @author jeromeklam
 */
class Application extends \FreeFW\Core\Application
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
        $request = \GuzzleHttp\Psr7\ServerRequest::fromGlobals();
        $route   = $this->router->findRoute($request);
        if ($route) {
            $route->setLogger($this->logger);
            $route->setConfig($this->config);
            $route->render($request);
        } else {
            $this->fireEvent(\FreeFW\Constants::EVENT_ROUTE_NOT_FOUND);
        }
        $this->afterRender();
        $this->logger->debug('Application.handle.end');
    }
}
