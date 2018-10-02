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
    protected $config = null;

    /**
     * Set config
     *
     * @param \FreeFW\Application\Config $p_config
     *
     * @return \FreeFW\Behaviour\ConfigAwareTrait
     */
    public function setConfig(\FreeFW\Application\Config $p_config)
    {
        $this->config = $p_config;
        return $this;
    }

    /**
     * Get config
     *
     * @return \FreeFW\Application\Config
     */
    public function getConfig()
    {
        return $this->config;
    }
}
