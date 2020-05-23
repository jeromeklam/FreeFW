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
     * brk_id
     * @var int
     */
    protected $brk_id = null;

    /**
     * user_id
     * @var int
     */
    protected $user_id = null;

    /**
     * hist_ts
     * @var mixed
     */
    protected $hist_ts = null;

    /**
     * hist_method
     * @var string
     */
    protected $hist_method = null;

    /**
     * hist_object_name
     * @var string
     */
    protected $hist_object_name = null;

    /**
     * hist_object_id
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
     * Set brk_id
     *
     * @param int $p_value
     *
     * @return \FreeFW\Model\History
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
     * Set user_id
     *
     * @param int $p_value
     *
     * @return \FreeFW\Model\History
     */
    public function setUserId($p_value)
    {
        $this->user_id = $p_value;
        return $this;
    }

    /**
     * Get user_id
     *
     * @return int
     */
    public function getUserId()
    {
        return $this->user_id;
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
     * Set hist_method
     *
     * @param string $p_value
     *
     * @return \FreeFW\Model\History
     */
    public function setHistMethod($p_value)
    {
        $this->hist_method = $p_value;
        return $this;
    }

    /**
     * Get hist_method
     *
     * @return string
     */
    public function getHistMethod()
    {
        return $this->hist_method;
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
     * Set hist_object_id
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
     * Get hist_object_id
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
