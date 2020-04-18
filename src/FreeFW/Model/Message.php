<?php
namespace FreeFW\Model;

use \FreeFW\Constants as FFCST;

/**
 * Message
 *
 * @author jeromeklam
 */
class Message extends \FreeFW\Model\Base\Message
{

    /**
     * Types
     * @var string
     */
    const TYPE_EMAIL = 'EMAIL';
    const TYPE_SMS   = 'SMS';

    /**
     * Status
     * @var string
     */
    const STATUS_WAITING = 'WAITING';
    const STATUS_PENDING = 'PENDING';
    const STATUS_OK      = 'OK';
    const STATUS_ERROR   = 'ERROR';

    /**
     * Lang
     * @var \FreeFW\Model\Lang
     */
    protected $lang = null;

    /**
     *
     * {@inheritDoc}
     * @see \FreeFW\Core\Model::init()
     */
    public function init()
    {
        $this->msg_id     = 0;
        $this->brk_id     = 0;
        $this->lang_id    = 0;
        $this->msg_type   = self::TYPE_EMAIL;
        $this->msg_status = self::STATUS_WAITING;
        $this->msg_ts     = \FreeFW\Tools\Date::getCurrentTimestamp();
        return $this;
    }

    /**
     * Set lang
     *
     * @param \FreeFW\Model\Lang $p_lang
     *
     * @return \FreeFW\Model\Email
     */
    public function setLang($p_lang)
    {
        $this->lang = $p_lang;
        return $this;
    }

    /**
     * Get lang
     *
     * @return \FreeFW\Model\Lang
     */
    public function getLang()
    {
        return $this->lang;
    }
}
