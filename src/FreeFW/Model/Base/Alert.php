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
     * alert_title
     * @var string
     */
    protected $alert_title = null;

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
     * alert_deadline
     * @var mixed
     */
    protected $alert_deadline = null;

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
     * alert_done_text
     * @var mixed
     */
    protected $alert_done_text = null;

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
     * alert_recur_type
     * @var string
     */
    protected $alert_recur_type = null;

    /**
     * alert_recur_number
     * @var int
     */
    protected $alert_recur_number = null;

    /**
     * alert_email_1
     * @var string
     */
    protected $alert_email_1 = null;

    /**
     * alert_email_2
     * @var string
     */
    protected $alert_email_2 = null;

    /**
     * alert_string_1
     * @var string
     */
    protected $alert_string_1 = null;

    /**
     * alert_string_2
     * @var string
     */
    protected $alert_string_2 = null;

    /**
     * alert_number_1
     * @var int
     */
    protected $alert_number_1 = null;

    /**
     * alert_numer_2
     * @var int
     */
    protected $alert_numer_2 = null;

    /**
     * alert_bool_1
     * @var int
     */
    protected $alert_bool_1 = null;

    /**
     * alert_bool_2
     * @var int
     */
    protected $alert_bool_2 = null;

    /**
     * alert_text_1
     * @var mixed
     */
    protected $alert_text_1 = null;

    /**
     * alert_text_2
     * @var mixed
     */
    protected $alert_text_2 = null;

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
     * Set alert_title
     *
     * @param string $p_value
     *
     * @return \FreeFW\Model\Alert
     */
    public function setAlertTitle($p_value)
    {
        $this->alert_title = $p_value;
        return $this;
    }

    /**
     * Get alert_title
     *
     * @return string
     */
    public function getAlertTitle()
    {
        return $this->alert_title;
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
     * Set alert_deadline
     *
     * @param mixed $p_value
     *
     * @return \FreeFW\Model\Alert
     */
    public function setAlertDeadline($p_value)
    {
        $this->alert_deadline = $p_value;
        return $this;
    }

    /**
     * Get alert_deadline
     *
     * @return mixed
     */
    public function getAlertDeadline()
    {
        return $this->alert_deadline;
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
     * Set alert_done_text
     *
     * @param mixed $p_value
     *
     * @return \FreeFW\Model\Alert
     */
    public function setAlertDoneText($p_value)
    {
        $this->alert_done_text = $p_value;
        return $this;
    }

    /**
     * Get alert_done_text
     *
     * @return mixed
     */
    public function getAlertDoneText()
    {
        return $this->alert_done_text;
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

    /**
     * Set alert_recur_type
     *
     * @param string $p_value
     *
     * @return \FreeFW\Model\Alert
     */
    public function setAlertRecurType($p_value)
    {
        $this->alert_recur_type = $p_value;
        return $this;
    }

    /**
     * Get alert_recur_type
     *
     * @return string
     */
    public function getAlertRecurType()
    {
        return $this->alert_recur_type;
    }

    /**
     * Set alert_recur_number
     *
     * @param int $p_value
     *
     * @return \FreeFW\Model\Alert
     */
    public function setAlertRecurNumber($p_value)
    {
        $this->alert_recur_number = $p_value;
        return $this;
    }

    /**
     * Get alert_recur_number
     *
     * @return int
     */
    public function getAlertRecurNumber()
    {
        return $this->alert_recur_number;
    }

    /**
     * Set alert_email_1
     *
     * @param string $p_value
     *
     * @return \FreeFW\Model\Alert
     */
    public function setAlertEmail_1($p_value)
    {
        $this->alert_email_1 = $p_value;
        return $this;
    }

    /**
     * Get alert_email_1
     *
     * @return string
     */
    public function getAlertEmail_1()
    {
        return $this->alert_email_1;
    }

    /**
     * Set alert_email_2
     *
     * @param string $p_value
     *
     * @return \FreeFW\Model\Alert
     */
    public function setAlertEmail_2($p_value)
    {
        $this->alert_email_2 = $p_value;
        return $this;
    }

    /**
     * Get alert_email_2
     *
     * @return string
     */
    public function getAlertEmail_2()
    {
        return $this->alert_email_2;
    }

    /**
     * Set alert_string_1
     *
     * @param string $p_value
     *
     * @return \FreeFW\Model\Alert
     */
    public function setAlertString_1($p_value)
    {
        $this->alert_string_1 = $p_value;
        return $this;
    }

    /**
     * Get alert_string_1
     *
     * @return string
     */
    public function getAlertString_1()
    {
        return $this->alert_string_1;
    }

    /**
     * Set alert_string_2
     *
     * @param string $p_value
     *
     * @return \FreeFW\Model\Alert
     */
    public function setAlertString_2($p_value)
    {
        $this->alert_string_2 = $p_value;
        return $this;
    }

    /**
     * Get alert_string_2
     *
     * @return string
     */
    public function getAlertString_2()
    {
        return $this->alert_string_2;
    }

    /**
     * Set alert_number_1
     *
     * @param int $p_value
     *
     * @return \FreeFW\Model\Alert
     */
    public function setAlertNumber_1($p_value)
    {
        $this->alert_number_1 = $p_value;
        return $this;
    }

    /**
     * Get alert_number_1
     *
     * @return int
     */
    public function getAlertNumber_1()
    {
        return $this->alert_number_1;
    }

    /**
     * Set alert_numer_2
     *
     * @param int $p_value
     *
     * @return \FreeFW\Model\Alert
     */
    public function setAlertNumer_2($p_value)
    {
        $this->alert_numer_2 = $p_value;
        return $this;
    }

    /**
     * Get alert_numer_2
     *
     * @return int
     */
    public function getAlertNumer_2()
    {
        return $this->alert_numer_2;
    }

    /**
     * Set alert_bool_1
     *
     * @param int $p_value
     *
     * @return \FreeFW\Model\Alert
     */
    public function setAlertBool_1($p_value)
    {
        $this->alert_bool_1 = $p_value;
        return $this;
    }

    /**
     * Get alert_bool_1
     *
     * @return int
     */
    public function getAlertBool_1()
    {
        return $this->alert_bool_1;
    }

    /**
     * Set alert_bool_2
     *
     * @param int $p_value
     *
     * @return \FreeFW\Model\Alert
     */
    public function setAlertBool_2($p_value)
    {
        $this->alert_bool_2 = $p_value;
        return $this;
    }

    /**
     * Get alert_bool_2
     *
     * @return int
     */
    public function getAlertBool_2()
    {
        return $this->alert_bool_2;
    }

    /**
     * Set alert_text_1
     *
     * @param mixed $p_value
     *
     * @return \FreeFW\Model\Alert
     */
    public function setAlertText_1($p_value)
    {
        $this->alert_text_1 = $p_value;
        return $this;
    }

    /**
     * Get alert_text_1
     *
     * @return mixed
     */
    public function getAlertText_1()
    {
        return $this->alert_text_1;
    }

    /**
     * Set alert_text_2
     *
     * @param mixed $p_value
     *
     * @return \FreeFW\Model\Alert
     */
    public function setAlertText_2($p_value)
    {
        $this->alert_text_2 = $p_value;
        return $this;
    }

    /**
     * Get alert_text_2
     *
     * @return mixed
     */
    public function getAlertText_2()
    {
        return $this->alert_text_2;
    }
}
