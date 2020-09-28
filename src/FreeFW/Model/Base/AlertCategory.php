<?php
namespace FreeFW\Model\Base;

/**
 * AlertCategory
 *
 * @author jeromeklam
 */
abstract class AlertCategory extends \FreeFW\Model\StorageModel\AlertCategory
{

    /**
     * alerc_id
     * @var int
     */
    protected $alerc_id = null;

    /**
     * brk_id
     * @var int
     */
    protected $brk_id = null;

    /**
     * alerc_name
     * @var string
     */
    protected $alerc_name = null;

    /**
     * alerc_bg_color
     * @var string
     */
    protected $alerc_bg_color = null;

    /**
     * alerc_fg_color
     * @var string
     */
    protected $alerc_fg_color = null;

    /**
     * Set alerc_id
     *
     * @param int $p_value
     *
     * @return \FreeFW\Model\AlertCategory
     */
    public function setAlercId($p_value)
    {
        $this->alerc_id = $p_value;
        return $this;
    }

    /**
     * Get alerc_id
     *
     * @return int
     */
    public function getAlercId()
    {
        return $this->alerc_id;
    }

    /**
     * Set brk_id
     *
     * @param int $p_value
     *
     * @return \FreeFW\Model\AlertCategory
     */
    public function setBrkId($p_value)
    {
        $this->brk_id = $p_value;
        return $this;
    }

    /**
     * Get brk_id
     *
     * @return int
     */
    public function getBrkId()
    {
        return $this->brk_id;
    }

    /**
     * Set alerc_name
     *
     * @param string $p_value
     *
     * @return \FreeFW\Model\AlertCategory
     */
    public function setAlercName($p_value)
    {
        $this->alerc_name = $p_value;
        return $this;
    }

    /**
     * Get alerc_name
     *
     * @return string
     */
    public function getAlercName()
    {
        return $this->alerc_name;
    }

    /**
     * Set alerc_bg_color
     *
     * @param string $p_value
     *
     * @return \FreeFW\Model\AlertCategory
     */
    public function setAlercBgColor($p_value)
    {
        $this->alerc_bg_color = $p_value;
        return $this;
    }

    /**
     * Get alerc_bg_color
     *
     * @return string
     */
    public function getAlercBgColor()
    {
        return $this->alerc_bg_color;
    }

    /**
     * Set alerc_fg_color
     *
     * @param string $p_value
     *
     * @return \FreeFW\Model\AlertCategory
     */
    public function setAlercFgColor($p_value)
    {
        $this->alerc_fg_color = $p_value;
        return $this;
    }

    /**
     * Get alerc_fg_color
     *
     * @return string
     */
    public function getAlercFgColor()
    {
        return $this->alerc_fg_color;
    }
}
