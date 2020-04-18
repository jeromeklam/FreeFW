<?php
namespace FreeFW\Model\Base;

/**
 * Notification
 *
 * @author jeromeklam
 */
abstract class Notification extends \FreeFW\Model\StorageModel\Notification
{

    /**
     * notif_id
     * @var int
     */
    protected $notif_id = null;

    /**
     * brk_id
     * @var int
     */
    protected $brk_id = null;

    /**
     * notif_text
     * @var mixed
     */
    protected $notif_text = null;

    /**
     * notif_subject
     * @var string
     */
    protected $notif_subject = null;

    /**
     * notif_object_name
     * @var string
     */
    protected $notif_object_name = null;

    /**
     * notif_object_id
     * @var int
     */
    protected $notif_object_id = null;

    /**
     * notif_code
     * @var string
     */
    protected $notif_code = null;

    /**
     * notif_ts
     * @var string
     */
    protected $notif_ts = null;

    /**
     * notif_type
     * @var string
     */
    protected $notif_type = null;

    /**
     * notif_read
     * @var string
     */
    protected $notif_read = null;

    /**
     * notif_read_ts
     * @var string
     */
    protected $notif_read_ts = null;

    /**
     * user_id
     * @var int
     */
    protected $user_id = null;

    /**
     * Set notif_id
     *
     * @param int $p_value
     *
     * @return \FreeFW\Model\Notification
     */
    public function setNotifId($p_value)
    {
        $this->notif_id = $p_value;
        return $this;
    }

    /**
     * Get notif_id
     *
     * @return int
     */
    public function getNotifId()
    {
        return $this->notif_id;
    }

    /**
     * Set brk_id
     *
     * @param int $p_value
     *
     * @return \FreeFW\Model\Notification
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
     * Set notif_text
     *
     * @param mixed $p_value
     *
     * @return \FreeFW\Model\Notification
     */
    public function setNotifText($p_value)
    {
        $this->notif_text = $p_value;
        return $this;
    }

    /**
     * Get notif_text
     *
     * @return mixed
     */
    public function getNotifText()
    {
        return $this->notif_text;
    }

    /**
     * Set notif_subject
     *
     * @param string $p_value
     *
     * @return \FreeFW\Model\Notification
     */
    public function setNotifSubject($p_value)
    {
        $this->notif_subject = $p_value;
        return $this;
    }

    /**
     * Get notif_subject
     *
     * @return string
     */
    public function getNotifSubject()
    {
        return $this->notif_subject;
    }

    /**
     * Set notif_object_name
     *
     * @param string $p_value
     *
     * @return \FreeFW\Model\Notification
     */
    public function setNotifObjectName($p_value)
    {
        $this->notif_object_name = $p_value;
        return $this;
    }

    /**
     * Get notif_object_name
     *
     * @return string
     */
    public function getNotifObjectName()
    {
        return $this->notif_object_name;
    }

    /**
     * Set notif_object_id
     *
     * @param int $p_value
     *
     * @return \FreeFW\Model\Notification
     */
    public function setNotifObjectId($p_value)
    {
        $this->notif_object_id = $p_value;
        return $this;
    }

    /**
     * Get notif_object_id
     *
     * @return int
     */
    public function getNotifObjectId()
    {
        return $this->notif_object_id;
    }

    /**
     * Set notif_code
     *
     * @param string $p_value
     *
     * @return \FreeFW\Model\Notification
     */
    public function setNotifCode($p_value)
    {
        $this->notif_code = $p_value;
        return $this;
    }

    /**
     * Get notif_code
     *
     * @return string
     */
    public function getNotifCode()
    {
        return $this->notif_code;
    }

    /**
     * Set notif_ts
     *
     * @param string $p_value
     *
     * @return \FreeFW\Model\Notification
     */
    public function setNotifTs($p_value)
    {
        $this->notif_ts = $p_value;
        return $this;
    }

    /**
     * Get notif_ts
     *
     * @return string
     */
    public function getNotifTs()
    {
        return $this->notif_ts;
    }

    /**
     * Set notif_type
     *
     * @param string $p_value
     *
     * @return \FreeFW\Model\Notification
     */
    public function setNotifType($p_value)
    {
        $this->notif_type = $p_value;
        return $this;
    }

    /**
     * Get notif_type
     *
     * @return string
     */
    public function getNotifType()
    {
        return $this->notif_type;
    }

    /**
     * Set notif_read
     *
     * @param string $p_value
     *
     * @return \FreeFW\Model\Notification
     */
    public function setNotifRead($p_value)
    {
        $this->notif_read = $p_value;
        return $this;
    }

    /**
     * Get notif_read
     *
     * @return string
     */
    public function getNotifRead()
    {
        return $this->notif_read;
    }

    /**
     * Set notif_read_ts
     *
     * @param string $p_value
     *
     * @return \FreeFW\Model\Notification
     */
    public function setNotifReadTs($p_value)
    {
        $this->notif_read_ts = $p_value;
        return $this;
    }

    /**
     * Get notif_read_ts
     *
     * @return string
     */
    public function getNotifReadTs()
    {
        return $this->notif_read_ts;
    }

    /**
     * Set user_id
     *
     * @param int $p_value
     *
     * @return \FreeFW\Model\Notification
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
}
