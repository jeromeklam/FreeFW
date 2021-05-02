<?php
namespace FreeFW\Core;

/**
 * Base application
 *
 * @author jeromeklam
 */
class Console implements
    \Psr\Log\LoggerAwareInterface,
    \FreeFW\Interfaces\ConfigAwareTraitInterface
{

    /**
     * comportements
     */
    use \Psr\Log\LoggerAwareTrait;
    use \FreeFW\Behaviour\EventManagerAwareTrait;
    use \FreeFW\Behaviour\ConfigAwareTrait;
    use \FreeFW\Behaviour\StorageListenerTrait;

    /**
     * Router
     * @var \FreeFW\Console\Router
     */
    protected $router = null;

    /**
     * Constructor
     *
     * @param \FreeFW\Application\Config $p_config
     */
    protected function __construct(
        \FreeFW\Application\Config $p_config,
        \Psr\Log\LoggerInterface $p_logger
    ) {
        $this->setAppConfig($p_config);
        $this->setLogger($p_logger);
        $this->router = new \FreeFW\Console\Router();
        $this->router->setLogger($this->logger);
    }

    /**
     * Event de fin
     *
     * @return void
     */
    protected function afterRender()
    {
        $this->logger->debug('console.afterRender.start');
        $manager = $this->getEventManager();
        $manager->notify(\FreeFW\Constants::EVENT_AFTER_RENDER);
        $this->logger->debug('console.afterRender.end');
        return $this;
    }

    /**
     * Return logger
     *
     * @return \Psr\Log\LoggerInterface
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     *
     * @param \FreeFW\Console\CommandCollection $p_collection
     */
    public function addCommands(\FreeFW\Console\CommandCollection $p_collection)
    {
        $this->router->addCommands($p_collection);
        return $this;
    }
}
