<?php
namespace FreeAsso\Model\Behaviour;

/**
 * cache
 *
 * @author jeromeklam
 *
 */
trait Cache
{

   /**
     * Id
     * @var number
     */
    protected $tab_name = null;

    /**
     * Cache
     * @var \FreeAsso\Model\Cache
     */
    protected $cache = null;

    /**
     * Set id : cache
     *
     * @param number $p_id
     *
     * @return \FreeAsso\Model\Behaviour\Cache
     */
    public function setTabName($p_id)
    {
        $this->tab_name = $p_id;
        if ($this->cache) {
            if ($this->cache->getTabName() != $this->tab_name) {
                $this->cache = null;
            }
        }
        return $this;
    }

    /**
     * Get id : cache
     *
     * @return number
     */
    public function getTabName()
    {
        return $this->tab_name;
    }

    /**
     * Set cache
     *
     * @param \FreeAsso\Model\Cache $p_model
     *
     * @return \FreeFW\Core\Model
     */
    public function setCache($p_model)
    {
        $this->cache = $p_model;
        if ($p_model) {
            $this->tab_name = $p_model->getTabName();
        }
        return $this;
   }

   /**
     * Get cache
     *
     * @param boolean $p_force
     *
     * @return \FreeAsso\Model\Cache
     */
    public function getCache($p_force = false)
    {
        if ($this->cache === null || $p_force) {
            if ($this->tab_name > 0) {
                $this->cache = \FreeAsso\Model\Cache::findFirst(
                    [
                        'tab_name' => $this->tab_name
                    ]
                );
            } else {
                $this->cache = null;
            }
        }
        return $this->cache;
    }
}