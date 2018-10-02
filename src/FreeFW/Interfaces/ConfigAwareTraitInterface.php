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
    public function setConfig(\FreeFW\Application\Config $p_config);
}
