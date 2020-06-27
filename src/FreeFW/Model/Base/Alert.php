<?php
namespace FreeFW\Model\Base;

/**
 * Alert
 *
 * @author jeromeklam
 */
abstract class Alert extends \FreeFW\Model\StorageModel\Alert
{

    /**
     * alert_id
     * @var int
     */
    protected $alert_id = null;

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
     * alert_object_name
     * @var string
     */
    protected $alert_object_name = null;

    /**
     * alert_object_id
     * @var int
     */
    protected $alert_object_id = null;

    /**
     * alert_from
     * @var mixed
     */
    protected $alert_from = null;

    /**
     * alert_to
     * @var mixed
     */
    protected $alert_to = null;

    /**
     * alert_ts
     * @var mixed
     */
    protected $alert_ts = null;

    /**
     * alert_done_ts
     * @var mixed
     */
    protected $alert_done_ts = null;

    /**
     * alert_done_action
     * @var string
     */
    protected $alert_done_action = null;

    /**
     * alert_done_user_id
     * @var int
     */
    protected $alert_done_user_id = null;

    /**
     * alert_code
     * @var string
     */
    protected $alert_code = null;

    /**
     * alert_text
     * @var mixed
     */
    protected $alert_text = null;

    /**
     * alert_activ
     * @var bool
     */
    protected $alert_activ = null;

    /**
     * alert_priority
     * @var string
     */
    protected $alert_priority = null;

    /**
     * Set alert_id
     *
     * @param int $p_value
     *
     * @return \FreeFW\Model\Alert
     */
    public function setAlertId($p_value)
    {
        $this->alert_id = $p_value;
        return $this;
    }

    /**
     * Get alert_id
     *
     * @return int
     */
    public function getAlertId()
    {
        return $this->alert_id;
    }

    /**
     * Set brk_id
     *
     * @param int $p_value
     *
     * @return \FreeFW\Model\Alert
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
     * @return \FreeFW\Model\Alert
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
     * Set alert_object_name
     *
     * @param string $p_value
     *
     * @return \FreeFW\Model\Alert
     */
    public function setAlertObjectName($p_value)
    {
        $this->alert_object_name = $p_value;
        return $this;
    }

    /**
     * Get alert_object_name
     *
     * @return string
     */
    public function getAlertObjectName()
    {
        return $this->alert_object_name;
    }

    /**
     * Set alert_object_id
     *
     * @param int $p_value
     *
     * @return \FreeFW\Model\Alert
     */
    public function setAlertObjectId($p_value)
    {
        $this->alert_object_id = $p_value;
        return $this;
    }

    /**
     * Get alert_object_id
     *
     * @return int
     */
    public function getAlertObjectId()
    {
        return $this->alert_object_id;
    }

    /**
     * Set alert_from
     *
     * @param mixed $p_value
     *
     * @return \FreeFW\Model\Alert
     */
    public function setAlertFrom($p_value)
    {
        $this->alert_from = $p_value;
        return $this;
    }

    /**
     * Get alert_from
     *
     * @return mixed
     */
    public function getAlertFrom()
    {
        return $this->alert_from;
    }

    /**
     * Set alert_to
     *
     * @param mixed $p_value
     *
     * @return \FreeFW\Model\Alert
     */
    public function setAlertTo($p_value)
    {
        $this->alert_to = $p_value;
        return $this;
    }

    /**
     * Get alert_to
     *
     * @return mixed
     */
    public function getAlertTo()
    {
        return $this->alert_to;
    }

    /**
     * Set alert_ts
     *
     * @param mixed $p_value
     *
     * @return \FreeFW\Model\Alert
     */
    public function setAlertTs($p_value)
    {
        $this->alert_ts = $p_value;
        return $this;
    }

    /**
     * Get alert_ts
     *
     * @return mixed
     */
    public function getAlertTs()
    {
        return $this->alert_ts;
    }

    /**
     * Set alert_done_ts
     *
     * @param mixed $p_value
     *
     * @return \FreeFW\Model\Alert
     */
    public function setAlertDoneTs($p_value)
    {
        $this->alert_done_ts = $p_value;
        return $this;
    }

    /**
     * Get alert_done_ts
     *
     * @return mixed
     */
    public function getAlertDoneTs()
    {
        return $this->alert_done_ts;
    }

    /**
     * Set alert_done_action
     *
     * @param string $p_value
     *
     * @return \FreeFW\Model\Alert
     */
    public function setAlertDoneAction($p_value)
    {
        $this->alert_done_action = $p_value;
        return $this;
    }

    /**
     * Get alert_done_action
     *
     * @return string
     */
    public function getAlertDoneAction()
    {
        return $this->alert_done_action;
    }

    /**
     * Set alert_done_user_id
     *
     * @param int $p_value
     *
     * @return \FreeFW\Model\Alert
     */
    public function setAlertDoneUserId($p_value)
    {
        $this->alert_done_user_id = $p_value;
        return $this;
    }

    /**
     * Get alert_done_user_id
     *
     * @return int
     */
    public function getAlertDoneUserId()
    {
        return $this->alert_done_user_id;
    }

    /**
     * Set alert_code
     *
     * @param string $p_value
     *
     * @return \FreeFW\Model\Alert
     */
    public function setAlertCode($p_value)
    {
        $this->alert_code = $p_value;
        return $this;
    }

    /**
     * Get alert_code
     *
     * @return string
     */
    public function getAlertCode()
    {
        return $this->alert_code;
    }

    /**
     * Set alert_text
     *
     * @param mixed $p_value
     *
     * @return \FreeFW\Model\Alert
     */
    public function setAlertText($p_value)
    {
        $this->alert_text = $p_value;
        return $this;
    }

    /**
     * Get alert_text
     *
     * @return mixed
     */
    public function getAlertText()
    {
        return $this->alert_text;
    }

    /**
     * Set alert_activ
     *
     * @param bool $p_value
     *
     * @return \FreeFW\Model\Alert
     */
    public function setAlertActiv($p_value)
    {
        $this->alert_activ = $p_value;
        return $this;
    }

    /**
     * Get alert_activ
     *
     * @return bool
     */
    public function getAlertActiv()
    {
        return $this->alert_activ;
    }

    /**
     * Set alert_priority
     *
     * @param string $p_value
     *
     * @return \FreeFW\Model\Alert
     */
    public function setAlertPriority($p_value)
    {
        $this->alert_priority = $p_value;
        return $this;
    }

    /**
     * Get alert_priority
     *
     * @return string
     */
    public function getAlertPriority()
    {
        return $this->alert_priority;
    }
}
