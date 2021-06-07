<?php
namespace FreeFW\Model\Base;

/**
 * Automate
 *
 * @author jeromeklam
 */
abstract class Automate extends \FreeFW\Model\StorageModel\Automate
{

    /**
     * auto_id
     * @var int
     */
    protected $auto_id = null;

    /**
     * auto_name
     * @var string
     */
    protected $auto_name = null;

    /**
     * auto_desc
     * @var mixed
     */
    protected $auto_desc = null;

    /**
     * auto_object_name
     * @var string
     */
    protected $auto_object_name = null;

    /**
     * grp_id
     * @var int
     */
    protected $grp_id = null;

    /**
     * auto_service
     * @var string
     */
    protected $auto_service = null;

    /**
     * auto_method
     * @var string
     */
    protected $auto_method = null;

    /**
     * auto_params
     * @var mixed
     */
    protected $auto_params = null;

    /**
     * Events
     * @var string
     */
    protected $auto_events = null;

    /**
     * Set auto_id
     *
     * @param int $p_value
     *
     * @return \FreeFW\Model\Automate
     */
    public function setAutoId($p_value)
    {
        $this->auto_id = $p_value;
        return $this;
    }

    /**
     * Get auto_id
     *
     * @return int
     */
    public function getAutoId()
    {
        return $this->auto_id;
    }

    /**
     * Set auto_name
     *
     * @param string $p_value
     *
     * @return \FreeFW\Model\Automate
     */
    public function setAutoName($p_value)
    {
        $this->auto_name = $p_value;
        return $this;
    }

    /**
     * Get auto_name
     *
     * @return string
     */
    public function getAutoName()
    {
        return $this->auto_name;
    }

    /**
     * Set auto_desc
     *
     * @param mixed $p_value
     *
     * @return \FreeFW\Model\Automate
     */
    public function setAutoDesc($p_value)
    {
        $this->auto_desc = $p_value;
        return $this;
    }

    /**
     * Get auto_desc
     *
     * @return mixed
     */
    public function getAutoDesc()
    {
        return $this->auto_desc;
    }

    /**
     * Set auto_object_name
     *
     * @param string $p_value
     *
     * @return \FreeFW\Model\Automate
     */
    public function setAutoObjectName($p_value)
    {
        $this->auto_object_name = $p_value;
        return $this;
    }

    /**
     * Get auto_object_name
     *
     * @return string
     */
    public function getAutoObjectName()
    {
        return $this->auto_object_name;
    }

    /**
     * Set grp_id
     *
     * @param int $p_value
     *
     * @return \FreeFW\Model\Automate
     */
    public function setGrpId($p_value)
    {
        $this->grp_id = $p_value;
        return $this;
    }

    /**
     * Get grp_id
     *
     * @return int
     */
    public function getGrpId()
    {
        return $this->grp_id;
    }

    /**
     * Set auto_service
     *
     * @param string $p_value
     *
     * @return \FreeFW\Model\Automate
     */
    public function setAutoService($p_value)
    {
        $this->auto_service = $p_value;
        return $this;
    }

    /**
     * Get auto_service
     *
     * @return string
     */
    public function getAutoService()
    {
        return $this->auto_service;
    }

    /**
     * Set auto_method
     *
     * @param string $p_value
     *
     * @return \FreeFW\Model\Automate
     */
    public function setAutoMethod($p_value)
    {
        $this->auto_method = $p_value;
        return $this;
    }

    /**
     * Get auto_method
     *
     * @return string
     */
    public function getAutoMethod()
    {
        return $this->auto_method;
    }

    /**
     * Set auto_params
     *
     * @param mixed $p_value
     *
     * @return \FreeFW\Model\Automate
     */
    public function setAutoParams($p_value)
    {
        $this->auto_params = $p_value;
        return $this;
    }

    /**
     * Get auto_params
     *
     * @return mixed
     */
    public function getAutoParams()
    {
        return $this->auto_params;
    }

    /**
     * Set auto_events
     *
     * @param string $p_value
     *
     * @return \FreeFW\Model\Base\Automate
     */
    public function setAutoEvents($p_value)
    {
        $this->auto_events = $p_value;
        return $this;
    }

    /**
     * Get auto_events
     *
     * @return string
     */
    public function getAutoEvents()
    {
        return $this->auto_events;
    }
}
