<?php
namespace FreeFW\Model\Base;

/**
 * Message
 *
 * @author jeromeklam
 */
abstract class Message extends \FreeFW\Model\StorageModel\Message
{

    /**
     * msg_id
     * @var int
     */
    protected $msg_id = null;

    /**
     * brk_id
     * @var int
     */
    protected $brk_id = null;

    /**
     * msg_object_name
     * @var string
     */
    protected $msg_object_name = null;

    /**
     * msg_object_id
     * @var int
     */
    protected $msg_object_id = null;

    /**
     * lang_id
     * @var int
     */
    protected $lang_id = null;

    /**
     * msg_ts
     * @var string
     */
    protected $msg_ts = null;

    /**
     * msg_status
     * @var string
     */
    protected $msg_status = null;

    /**
     * msg_type
     * @var string
     */
    protected $msg_type = null;

    /**
     * msg_dest
     * @var mixed
     */
    protected $msg_dest = null;

    /**
     * msg_cc
     * @var mixed
     */
    protected $msg_cc = null;

    /**
     * msg_bcc
     * @var mixed
     */
    protected $msg_bcc = null;

    /**
     * msg_subject
     * @var mixed
     */
    protected $msg_subject = null;

    /**
     * msg_body
     * @var mixed
     */
    protected $msg_body = null;

    /**
     * msg_pj1
     * @var string
     */
    protected $msg_pj1 = null;

    /**
     * msg_pj2
     * @var string
     */
    protected $msg_pj2 = null;

    /**
     * msg_pj3
     * @var string
     */
    protected $msg_pj3 = null;

    /**
     * msg_pj4
     * @var string
     */
    protected $msg_pj4 = null;

    /**
     * msg_send_ts
     * @var string
     */
    protected $msg_send_ts = null;

    /**
     * msg_send_error
     * @var mixed
     */
    protected $msg_send_error = null;

    /**
     * msg_reply_to
     * @var mixed
     */
    protected $msg_reply_to = null;

    /**
     * Set msg_id
     *
     * @param int $p_value
     *
     * @return \FreeFW\Model\Message
     */
    public function setMsgId($p_value)
    {
        $this->msg_id = $p_value;
        return $this;
    }

    /**
     * Get msg_id
     *
     * @return int
     */
    public function getMsgId()
    {
        return $this->msg_id;
    }

    /**
     * Set brk_id
     *
     * @param int $p_value
     *
     * @return \FreeFW\Model\Message
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
     * Set msg_object_name
     *
     * @param string $p_value
     *
     * @return \FreeFW\Model\Message
     */
    public function setMsgObjectName($p_value)
    {
        $this->msg_object_name = $p_value;
        return $this;
    }

    /**
     * Get msg_object_name
     *
     * @return string
     */
    public function getMsgObjectName()
    {
        return $this->msg_object_name;
    }

    /**
     * Set msg_object_id
     *
     * @param int $p_value
     *
     * @return \FreeFW\Model\Message
     */
    public function setMsgObjectId($p_value)
    {
        $this->msg_object_id = $p_value;
        return $this;
    }

    /**
     * Get msg_object_id
     *
     * @return int
     */
    public function getMsgObjectId()
    {
        return $this->msg_object_id;
    }

    /**
     * Set lang_id
     *
     * @param int $p_value
     *
     * @return \FreeFW\Model\Message
     */
    public function setLangId($p_value)
    {
        $this->lang_id = $p_value;
        return $this;
    }

    /**
     * Get lang_id
     *
     * @return int
     */
    public function getLangId()
    {
        return $this->lang_id;
    }

    /**
     * Set msg_ts
     *
     * @param string $p_value
     *
     * @return \FreeFW\Model\Message
     */
    public function setMsgTs($p_value)
    {
        $this->msg_ts = $p_value;
        return $this;
    }

    /**
     * Get msg_ts
     *
     * @return string
     */
    public function getMsgTs()
    {
        return $this->msg_ts;
    }

    /**
     * Set msg_status
     *
     * @param string $p_value
     *
     * @return \FreeFW\Model\Message
     */
    public function setMsgStatus($p_value)
    {
        $this->msg_status = $p_value;
        return $this;
    }

    /**
     * Get msg_status
     *
     * @return string
     */
    public function getMsgStatus()
    {
        return $this->msg_status;
    }

    /**
     * Set msg_type
     *
     * @param string $p_value
     *
     * @return \FreeFW\Model\Message
     */
    public function setMsgType($p_value)
    {
        $this->msg_type = $p_value;
        return $this;
    }

    /**
     * Get msg_type
     *
     * @return string
     */
    public function getMsgType()
    {
        return $this->msg_type;
    }

    /**
     * Set msg_dest
     *
     * @param mixed $p_value
     *
     * @return \FreeFW\Model\Message
     */
    public function setMsgDest($p_value)
    {
        $this->msg_dest = $p_value;
        return $this;
    }

    /**
     * Get msg_dest
     *
     * @return mixed
     */
    public function getMsgDest()
    {
        return $this->msg_dest;
    }

    /**
     * Set msg_cc
     *
     * @param mixed $p_value
     *
     * @return \FreeFW\Model\Message
     */
    public function setMsgCc($p_value)
    {
        $this->msg_cc = $p_value;
        return $this;
    }

    /**
     * Get msg_cc
     *
     * @return mixed
     */
    public function getMsgCc()
    {
        return $this->msg_cc;
    }

    /**
     * Set msg_bcc
     *
     * @param mixed $p_value
     *
     * @return \FreeFW\Model\Message
     */
    public function setMsgBcc($p_value)
    {
        $this->msg_bcc = $p_value;
        return $this;
    }

    /**
     * Get msg_bcc
     *
     * @return mixed
     */
    public function getMsgBcc()
    {
        return $this->msg_bcc;
    }

    /**
     * Set msg_subject
     *
     * @param mixed $p_value
     *
     * @return \FreeFW\Model\Message
     */
    public function setMsgSubject($p_value)
    {
        $this->msg_subject = $p_value;
        return $this;
    }

    /**
     * Get msg_subject
     *
     * @return mixed
     */
    public function getMsgSubject()
    {
        return $this->msg_subject;
    }

    /**
     * Set msg_body
     *
     * @param mixed $p_value
     *
     * @return \FreeFW\Model\Message
     */
    public function setMsgBody($p_value)
    {
        $this->msg_body = $p_value;
        return $this;
    }

    /**
     * Get msg_body
     *
     * @return mixed
     */
    public function getMsgBody()
    {
        return $this->msg_body;
    }

    /**
     * Set msg_pj1
     *
     * @param string $p_value
     *
     * @return \FreeFW\Model\Message
     */
    public function setMsgPj1($p_value)
    {
        $this->msg_pj1 = $p_value;
        return $this;
    }

    /**
     * Get msg_pj1
     *
     * @return string
     */
    public function getMsgPj1()
    {
        return $this->msg_pj1;
    }

    /**
     * Set msg_pj2
     *
     * @param string $p_value
     *
     * @return \FreeFW\Model\Message
     */
    public function setMsgPj2($p_value)
    {
        $this->msg_pj2 = $p_value;
        return $this;
    }

    /**
     * Get msg_pj2
     *
     * @return string
     */
    public function getMsgPj2()
    {
        return $this->msg_pj2;
    }

    /**
     * Set msg_pj3
     *
     * @param string $p_value
     *
     * @return \FreeFW\Model\Message
     */
    public function setMsgPj3($p_value)
    {
        $this->msg_pj3 = $p_value;
        return $this;
    }

    /**
     * Get msg_pj3
     *
     * @return string
     */
    public function getMsgPj3()
    {
        return $this->msg_pj3;
    }

    /**
     * Set msg_pj4
     *
     * @param string $p_value
     *
     * @return \FreeFW\Model\Message
     */
    public function setMsgPj4($p_value)
    {
        $this->msg_pj4 = $p_value;
        return $this;
    }

    /**
     * Get msg_pj4
     *
     * @return string
     */
    public function getMsgPj4()
    {
        return $this->msg_pj4;
    }

    /**
     * Set msg_send_ts
     *
     * @param string $p_value
     *
     * @return \FreeFW\Model\Message
     */
    public function setMsgSendTs($p_value)
    {
        $this->msg_send_ts = $p_value;
        return $this;
    }

    /**
     * Get msg_send_ts
     *
     * @return string
     */
    public function getMsgSendTs()
    {
        return $this->msg_send_ts;
    }

    /**
     * Set msg_send_error
     *
     * @param mixed $p_value
     *
     * @return \FreeFW\Model\Message
     */
    public function setMsgSendError($p_value)
    {
        $this->msg_send_error = $p_value;
        return $this;
    }

    /**
     * Get msg_send_error
     *
     * @return mixed
     */
    public function getMsgSendError()
    {
        return $this->msg_send_error;
    }

    /**
     * Set msg_reply_to
     *
     * @param mixed $p_value
     *
     * @return \FreeFW\Model\Message
     */
    public function setMsgReplyTo($p_value)
    {
        $this->msg_reply_to = $p_value;
        return $this;
    }

    /**
     * Get msg_reply_to
     *
     * @return mixed
     */
    public function getMsgReplyTo()
    {
        return $this->msg_reply_to;
    }
}
