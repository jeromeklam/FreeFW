<?php
namespace FreeFW\Behaviour;

/**
 * Config manager
 *
 * @author jeromeklam
 */
trait ConfigAwareTrait
{

    /**
     * Config
     * @var \FreeFW\Application\Config
     */
    protected $app_config = null;

    /**
     * Set config
     *
     * @param \FreeFW\Application\Config $p_config
     *
     * @return \FreeFW\Behaviour\ConfigAwareTrait
     */
    public function setAppConfig(\FreeFW\Application\Config $p_config)
    {
        $this->app_config = $p_config;
        return $this;
    }

    /**
     * Get config
     *
     * @return \FreeFW\Application\Config
     */
    public function getAppConfig()
    {
        return $this->app_config;
    }
}
