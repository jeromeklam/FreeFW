<?php
namespace FreeFW\Model\Base;

/**
 * Email
 *
 * @author jeromeklam
 */
abstract class Email extends \FreeFW\Model\StorageModel\Email
{

    /**
     * email_id
     * @var int
     */
    protected $email_id = null;

    /**
     * brk_id
     * @var int
     */
    protected $brk_id = null;

    /**
     * lang_id
     * @var int
     */
    protected $lang_id = null;

    /**
     * email_code
     * @var string
     */
    protected $email_code = null;

    /**
     * email_subject
     * @var string
     */
    protected $email_subject = null;

    /**
     * email_body
     * @var mixed
     */
    protected $email_body = null;

    /**
     * email_from
     * @var string
     */
    protected $email_from = null;

    /**
     * email_from_name
     * @var string
     */
    protected $email_from_name = null;

    /**
     * email_reply_to
     * @var string
     */
    protected $email_reply_to = null;

    /**
     * email_edi1_id
     * @var int
     */
    protected $email_edi1_id = null;

    /**
     * email_edi1_object
     * @var string
     */
    protected $email_edi1_object = null;

    /**
     * email_edi2_id
     * @var int
     */
    protected $email_edi2_id = null;

    /**
     * email_edi2_object
     * @var string
     */
    protected $email_edi2_object = null;

    /**
     * email_object_name
     * @var string
     */
    protected $email_object_name = null;

    /**
     * email_bcc
     * @var string
     */
    protected $email_bcc = null;

    /**
     * Set email_id
     *
     * @param int $p_value
     *
     * @return \FreeFW\Model\Email
     */
    public function setEmailId($p_value)
    {
        $this->email_id = $p_value;
        return $this;
    }

    /**
     * Get email_id
     *
     * @return int
     */
    public function getEmailId()
    {
        return $this->email_id;
    }

    /**
     * Set brk_id
     *
     * @param int $p_value
     *
     * @return \FreeFW\Model\Email
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
     * Set lang_id
     *
     * @param int $p_value
     *
     * @return \FreeFW\Model\Email
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
     * Set email_code
     *
     * @param string $p_value
     *
     * @return \FreeFW\Model\Email
     */
    public function setEmailCode($p_value)
    {
        $this->email_code = $p_value;
        return $this;
    }

    /**
     * Get email_code
     *
     * @return string
     */
    public function getEmailCode()
    {
        return $this->email_code;
    }

    /**
     * Set email_subject
     *
     * @param string $p_value
     *
     * @return \FreeFW\Model\Email
     */
    public function setEmailSubject($p_value)
    {
        $this->email_subject = $p_value;
        return $this;
    }

    /**
     * Get email_subject
     *
     * @return string
     */
    public function getEmailSubject()
    {
        return $this->email_subject;
    }

    /**
     * Set email_body
     *
     * @param mixed $p_value
     *
     * @return \FreeFW\Model\Email
     */
    public function setEmailBody($p_value)
    {
        $this->email_body = $p_value;
        return $this;
    }

    /**
     * Get email_body
     *
     * @return mixed
     */
    public function getEmailBody()
    {
        return $this->email_body;
    }

    /**
     * Set email_from
     *
     * @param string $p_value
     *
     * @return \FreeFW\Model\Email
     */
    public function setEmailFrom($p_value)
    {
        $this->email_from = $p_value;
        return $this;
    }

    /**
     * Get email_from
     *
     * @return string
     */
    public function getEmailFrom()
    {
        return $this->email_from;
    }

    /**
     * Set email_from_name
     *
     * @param string $p_value
     *
     * @return \FreeFW\Model\Email
     */
    public function setEmailFromName($p_value)
    {
        $this->email_from_name = $p_value;
        return $this;
    }

    /**
     * Get email_from_name
     *
     * @return string
     */
    public function getEmailFromName()
    {
        return $this->email_from_name;
    }

    /**
     * Set email_reply_to
     *
     * @param string $p_value
     *
     * @return \FreeFW\Model\Email
     */
    public function setEmailReplyTo($p_value)
    {
        $this->email_reply_to = $p_value;
        return $this;
    }

    /**
     * Get email_reply_to
     *
     * @return string
     */
    public function getEmailReplyTo()
    {
        return $this->email_reply_to;
    }

    /**
     * Get email_edi1_id
     *
     * @param int $p_value
     *
     * @return \FreeFW\Model\Base\Email
     */
    public function setEmailEdi1Id($p_value)
    {
        $this->email_edi1_id = $p_value;
        return $this;
    }

    /**
     * Get email_edi1_id
     *
     * @return int
     */
    public function getEmailEdi1Id()
    {
        return $this->email_edi1_id;
    }

    /**
     * Set email_edi1_object
     *
     * @param string $p_value
     *
     * @return \FreeFW\Model\Base\Email
     */
    public function setEmailEdi1Object($p_value)
    {
        $this->email_edi1_object = $p_value;
        return $this;
    }

    /**
     * Get email_edi1_object
     *
     * @return string
     */
    public function getEmailEdi1Object()
    {
        return $this->email_edi1_object;
    }

    /**
     * Get email_edi2_id
     *
     * @param int $p_value
     *
     * @return \FreeFW\Model\Base\Email
     */
    public function setEmailEdi12d($p_value)
    {
        $this->email_edi2_id = $p_value;
        return $this;
    }

    /**
     * Get email_edi2_id
     *
     * @return int
     */
    public function getEmailEdi2Id()
    {
        return $this->email_edi2_id;
    }

    /**
     * Set email_edi2_object
     *
     * @param string $p_value
     *
     * @return \FreeFW\Model\Base\Email
     */
    public function setEmailEdi2Object($p_value)
    {
        $this->email_edi2_object = $p_value;
        return $this;
    }

    /**
     * Get email_edi2_object
     *
     * @return string
     */
    public function getEmailEdi2Object()
    {
        return $this->email_edi2_object;
    }

    /**
     * Set email_object_name
     *
     * @param string $p_value
     *
     * @return \FreeFW\Model\Base\Email
     */
    public function setEmailObjectName($p_value)
    {
        $this->email_object_name = $p_value;
        return $this;
    }

    /**
     * Get email_object_name
     *
     * @return string
     */
    public function getEmailObjectName()
    {
        return $this->email_object_name;
    }

    /**
     * Set email_bcc
     *
     * @param string $p_value
     *
     * @return \FreeFW\Model\Base\Email
     */
    public function setEmailBcc($p_value)
    {
        $this->email_bcc = $p_value;
        return $this;
    }

    /**
     * Get email_bcc
     *
     * @return string
     */
    public function getEmailBcc()
    {
        return $this->email_bcc;
    }
}
