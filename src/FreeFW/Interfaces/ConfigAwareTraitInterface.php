<?php
namespace FreeFW\Interfaces;

/**
 * ConfigManager interface
 *
 * @author jeromeklam
 */
interface ConfigAwareTraitInterface
{

    /**
     * Set config
     *
     * @param \FreeFW\Application\Config $p_config
     *
     * @return \FreeFW\Interfaces\ConfigAwareTraitInterface
     */
    public function setAppConfig(\FreeFW\Application\Config $p_config);

    /**
     * Get config
     *
     * @return \FreeFW\Application\Config
     */
    public function getAppConfig();
}
