<?php
namespace FreeFW\Model\Base;

/**
 * History
 *
 * @author jeromeklam
 */
abstract class History extends \FreeFW\Model\StorageModel\History
{

    /**
     * hist_id
     * @var int
     */
    protected $hist_id = null;

    /**
     * hist_ts
     * @var mixed
     */
    protected $hist_ts = null;

    /**
     * hist_object_name
     * @var string
     */
    protected $hist_object_name = null;

    /**
     * hist_object_Id
     * @var int
     */
    protected $hist_object_id = null;

    /**
     * hist_object
     * @var mixed
     */
    protected $hist_object = null;

    /**
     * Set hist_id
     *
     * @param int $p_value
     *
     * @return \FreeFW\Model\History
     */
    public function setHistId($p_value)
    {
        $this->hist_id = $p_value;
        return $this;
    }

    /**
     * Get hist_id
     *
     * @return int
     */
    public function getHistId()
    {
        return $this->hist_id;
    }

    /**
     * Set hist_ts
     *
     * @param mixed $p_value
     *
     * @return \FreeFW\Model\History
     */
    public function setHistTs($p_value)
    {
        $this->hist_ts = $p_value;
        return $this;
    }

    /**
     * Get hist_ts
     *
     * @return mixed
     */
    public function getHistTs()
    {
        return $this->hist_ts;
    }

    /**
     * Set hist_object_name
     *
     * @param string $p_value
     *
     * @return \FreeFW\Model\History
     */
    public function setHistObjectName($p_value)
    {
        $this->hist_object_name = $p_value;
        return $this;
    }

    /**
     * Get hist_object_name
     *
     * @return string
     */
    public function getHistObjectName()
    {
        return $this->hist_object_name;
    }

    /**
     * Set hist_object_Id
     *
     * @param int $p_value
     *
     * @return \FreeFW\Model\History
     */
    public function setHistObjectId($p_value)
    {
        $this->hist_object_id = $p_value;
        return $this;
    }

    /**
     * Get hist_object_Id
     *
     * @return int
     */
    public function getHistObjectId()
    {
        return $this->hist_object_id;
    }

    /**
     * Set hist_object
     *
     * @param mixed $p_value
     *
     * @return \FreeFW\Model\History
     */
    public function setHistObject($p_value)
    {
        $this->hist_object = $p_value;
        return $this;
    }

    /**
     * Get hist_object
     *
     * @return mixed
     */
    public function getHistObject()
    {
        return $this->hist_object;
    }
}
