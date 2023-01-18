<?php
namespace FreeFW\Model\Base;

/**
 * Cache
 *
 * @author jeromeklam
 */
abstract class Cache extends \FreeFW\Model\StorageModel\Cache
{

    /**
     * tab_name
     * @var string
     */
    protected $tab_name = null;

    /**
     * tab_last_update
     * @var mixed
     */
    protected $tab_last_update = null;

    /**
     * Set tab_name
     *
     * @param string $p_value
     *
     * @return \FreeFW\Model\Cache
     */
    public function setTabName($p_value)
    {
        $this->tab_name = $p_value;
        return $this;
    }

    /**
     * Get tab_name
     *
     * @return string
     */
    public function getTabName()
    {
        return $this->tab_name;
    }

    /**
     * Set tab_last_update
     *
     * @param mixed $p_value
     *
     * @return \FreeFW\Model\Cache
     */
    public function setTabLastUpdate($p_value)
    {
        $this->tab_last_update = $p_value;
        return $this;
    }

    /**
     * Get tab_last_update
     *
     * @return mixed
     */
    public function getTabLastUpdate()
    {
        return $this->tab_last_update;
    }
}
