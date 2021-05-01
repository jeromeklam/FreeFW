<?php
namespace FreeFW\Mail;

/**
 *
 * @author Utilisateur
 *
 */
class Email
{

    /**
     * Piorities
     * @var integer
     */
    const PRIORITY_NORMAL = 1;

    /**
     * Subject
     * @var string
     */
    protected $subject = null;

    /**
     * Html body
     * @var string
     */
    protected $html_body = null;

    /**
     * Text body
     * @var string
     */
    protected $text_body = null;

    /**
     * Attachments
     * @var array
     */
    protected $attachments = [];

    /**
     * to
     * @var array
     */
    protected $tos = [];

    /**
     * cc
     * @var array
     */
    protected $ccs = [];

    /**
     * cci
     * @var array
     */
    protected $ccis = [];

    /**
     * from
     * @var string
     */
    protected $from = null;

    /**
     * reply to
     * @var string
     */
    protected $reply_to = null;

    /**
     * Prirority
     * @var integer
     */
    protected $priority = self::PRIORITY_NORMAL;

    /**
     * Set subject
     *
     * @param string $p_subject
     *
     * @return \FreeFW\Mail\Email
     */
    public function setSubject($p_subject)
    {
        $this->subject = $p_subject;
        return $this;
    }

    /**
     * Get subject
     *
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Set html body
     *
     * @param string $p_body
     *
     * @return \FreeFW\Mail\Email
     */
    public function setHtmlBody($p_body)
    {
        $this->html_body = $p_body;
        return $this;
    }

    /**
     * Get html body
     *
     * @return string
     */
    public function getHtmlBody()
    {
        return $this->html_body;
    }

    /**
     * Set text body
     *
     * @param string $p_text
     *
     * @return \FreeFW\Mail\Email
     */
    public function setTextBody($p_text)
    {
        $this->text_body = $p_text;
        return $this;
    }

    /**
     * Get text body
     *
     * @return string
     */
    public function getTextBody()
    {
        return $this->text_body;
    }

    public function setAttachments(array $p_attachments)
    {
        $this->attachments = $p_attachments;
        return $this;
    }

    /**
     * Flush attachments
     *
     * @return \FreeFW\Mail\Email
     */
    public function flushAttachments()
    {
        return $this->setAttachments([]);
    }

    /**
     * Get all attachments
     *
     * @return array
     */
    public function getAttachments()
    {
        return $this->attachments;
    }

    /**
     * Add new attachment
     *
     * @param string $p_file
     *
     * @return \FreeFW\Mail\Email
     */
    public function addAttachment($p_file)
    {
        if (is_file($p_file)) {
            $this->attachments[] = $p_file;
        }
        return $this;
    }

    /**
     * Set to
     *
     * @param array $p_tos
     *
     * @return \FreeFW\Mail\Email
     */
    public function setTos(array $p_tos)
    {
        $this->tos = $p_tos;
        return $this;
    }

    /**
     * Flush to
     *
     * @return \FreeFW\Mail\Email
     */
    public function flushTos()
    {
        return $this->setTos([]);
    }

    /**
     * Get to
     *
     * @return array
     */
    public function getTos()
    {
        return $this->tos;
    }

    /**
     * Add to
     *
     * @param string $p_email
     * @param string $p_name
     *
     * @return \FreeFW\Mail\Email
     */
    public function addTo($p_email, $p_name = null)
    {
        $this->tos[] = (object)['email' => $p_email, 'name' => $p_name];
        return $this;
    }

    /**
     * Set ccs
     *
     * @param array $p_ccs
     *
     * @return \FreeFW\Mail\Email
     */
    public function setCcs(array $p_ccs)
    {
        $this->ccs = $p_ccs;
        return $this;
    }

    /**
     * Flush ccs
     *
     * @return \FreeFW\Mail\Email
     */
    public function flushCcs()
    {
        return $this->setCcs([]);
    }

    /**
     * Get ccs
     *
     * @return array
     */
    public function getCcs()
    {
        return $this->ccs;
    }

    /**
     * Add cc
     *
     * @param string $p_email
     * @param string $p_name
     *
     * @return \FreeFW\Mail\Email
     */
    public function addCc($p_email, $p_name = null)
    {
        $this->ccs[] = (object)['email' => $p_email, 'name' => $p_name];
        return $this;
    }

    /**
     * Set CCis
     *
     * @param array $p_ccis
     *
     * @return \FreeFW\Mail\Email
     */
    public function setCcis(array $p_ccis)
    {
        $this->ccis = $p_ccis;
        return $this;
    }

    /**
     * Flush ccis
     *
     * @return \FreeFW\Mail\Email
     */
    public function flushCcis()
    {
        return $this->setCcis([]);
    }

    /**
     * Get ccis
     *
     * @return array
     */
    public function getCcis()
    {
        return $this->ccis;
    }

    /**
     * Add cci
     *
     * @param string $p_email
     * @param string $p_name
     *
     * @return \FreeFW\Mail\Email
     */
    public function addCci($p_email, $p_name = null)
    {
        $this->ccis[] = (object)['email' => $p_email, 'name' => $p_name];
        return $this;
    }

    /**
     * Set from
     *
     * @param string $p_email
     * @param string $p_name
     *
     * @return \FreeFW\Mail\Email
     */
    public function setFrom($p_email, $p_name = null)
    {
        $this->from = (object)['email' => $p_email, 'name' => $p_name];
        return $this;
    }

    /**
     * Get from
     *
     * @return object | null
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * Get from email
     *
     * @return string|NULL
     */
    public function getFromEmail()
    {
        if ($this->from !== null && isset($this->from->email)) {
            return $this->from->email;
        }
        return null;
    }

    /**
     * Get from name
     *
     * @return string|NULL
     */
    public function getFromName()
    {
        if ($this->from !== null && isset($this->from->name)) {
            return $this->from->name;
        }
        return null;
    }

    /**
     * Set reply to
     *
     * @param string $p_email
     * @param string $p_name
     *
     * @return \FreeFW\Mail\Email
     */
    public function setReplyTo($p_email, $p_name = null)
    {
        $this->reply_to = (object)['email' => $p_email, 'name' => $p_name];
        return $this;
    }

    /**
     * Get reply to
     *
     * @return object | null
     */
    public function getReplyTo()
    {
        return $this->reply_to;
    }

    /**
     * Get from email
     *
     * @return string|NULL
     */
    public function getReplyToEmail()
    {
        if ($this->reply_to !== null && isset($this->reply_to->email)) {
            return $this->reply_to->email;
        }
        return null;
    }

    /**
     * Get from name
     *
     * @return string|NULL
     */
    public function getReplyToName()
    {
        if ($this->reply_to !== null && isset($this->reply_to->name)) {
            return $this->reply_to->name;
        }
        return null;
    }

    /**
     * Merge datas in fields
     *
     * @param array $p_datas
     *
     * @return \FreeFW\Mail\Email
     */
    public function mergeDatas($p_datas)
    {
        $this->subject   = \FreeFW\Tools\PBXString::parse($this->subject, $p_datas);
        $this->html_body = \FreeFW\Tools\PBXString::parse($this->html_body, $p_datas);
        $this->text_body = \FreeFW\Tools\PBXString::parse($this->text_body, $p_datas);
        return $this;
    }
}
