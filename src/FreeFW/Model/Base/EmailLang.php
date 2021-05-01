<?php
namespace FreeFW\Model\Base;

/**
 * EmailLang
 *
 * @author jeromeklam
 */
abstract class EmailLang extends \FreeFW\Model\StorageModel\EmailLang
{

    /**
     * emaill_id
     * @var int
     */
    protected $emaill_id = null;

    /**
     * email_id
     * @var int
     */
    protected $email_id = null;

    /**
     * lang_id
     * @var int
     */
    protected $lang_id = null;

    /**
     * emaill_subject
     * @var string
     */
    protected $emaill_subject = null;

    /**
     * emaill_body
     * @var mixed
     */
    protected $emaill_body = null;

    /**
     * Set emaill_id
     *
     * @param int $p_value
     *
     * @return \FreeFW\Model\EmailLang
     */
    public function setEmaillId($p_value)
    {
        $this->emaill_id = $p_value;
        return $this;
    }

    /**
     * Get emaill_id
     *
     * @return int
     */
    public function getEmaillId()
    {
        return $this->emaill_id;
    }

    /**
     * Set email_id
     *
     * @param int $p_value
     *
     * @return \FreeFW\Model\EmailLang
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
     * Set lang_id
     *
     * @param int $p_value
     *
     * @return \FreeFW\Model\EmailLang
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
     * Set emaill_subject
     *
     * @param string $p_value
     *
     * @return \FreeFW\Model\EmailLang
     */
    public function setEmaillSubject($p_value)
    {
        $this->emaill_subject = $p_value;
        return $this;
    }

    /**
     * Get emaill_subject
     *
     * @return string
     */
    public function getEmaillSubject()
    {
        return $this->emaill_subject;
    }

    /**
     * Set emaill_body
     *
     * @param mixed $p_value
     *
     * @return \FreeFW\Model\EmailLang
     */
    public function setEmaillBody($p_value)
    {
        $this->emaill_body = $p_value;
        return $this;
    }

    /**
     * Get emaill_body
     *
     * @return mixed
     */
    public function getEmaillBody()
    {
        return $this->emaill_body;
    }
}
