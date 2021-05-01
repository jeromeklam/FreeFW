<?php
namespace FreeFW\Model\Behaviour;

/**
 *
 * @author jeromeklam
 *
 */
trait Email
{

    /**
     * Email
     * @var \FreeFW\Model\Email
     */
    protected $email = null;

    /**
     * EdiId
     * @var number
     */
    protected $email_id = null;

    /**
     * Set email
     *
     * @param \FreeFW\Model\Email $p_email
     *
     * @return \FreeFW\Core\Model
     */
    public function setEmail($p_email)
    {
        $this->email = $p_email;
        if ($this->email instanceof \FreeFW\Model\Email) {
            $this->setEmailId($this->email->getEmailId());
        } else {
            $this->setEmailId(null);
        }
        return $this;
    }

    /**
     * Get email
     *
     * @return \FreeFW\Model\Email
     */
    public function getEmail()
    {
        if ($this->email === null) {
            if ($this->email_id > 0) {
                $this->email = \FreeFW\Model\Email::findFirst(['email_id' => $this->email_id]);
            }
        }
        return $this->email;
    }

    /**
     * Set email id
     *
     * @param number $p_id
     *
     * @return \FreeFW\Model\Behaviour\Email
     */
    public function setEmailId($p_id)
    {
        $this->email_id = $p_id;
        if ($this->email !== null) {
            if ($this->email_id != $this->email->getEmailId()) {
                $this->email = null;
            }
        }
        return $this;
    }

    /**
     * Get email id
     *
     * @return number
     */
    public function getEmailId()
    {
        return $this->email_id;
    }
}
