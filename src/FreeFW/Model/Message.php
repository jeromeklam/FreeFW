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
     * Behaviours
     */
    use \FreeFW\Model\Behaviour\Lang;

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
     * Prevent from saving history
     * @var bool
     */
    protected $no_history = true;

    /**
     * Add new dest
     *
     * @param string $p_address
     * @param string $p_name
     *
     * @return \FreeFW\Model\Message
     */
    public function addDest($p_address, $p_name = null)
    {
        $dest = json_decode($this->msg_dest);
        if (!is_array($dest)) {
            $dest = [];
        }
        $stdObj = new \stdClass();
        $stdObj->address = $p_address;
        if ($p_name != '') {
            $stdObj->name = $p_name;
        }
        $dest[] = $stdObj;
        $this->msg_dest = json_encode($dest);
        return $this;
    }

    /**
     * Get dest
     *
     * @return array
     */
    public function getDest()
    {
        $dest = json_decode($this->msg_dest);
        if (!is_array($dest)) {
            $dest = [];
        }
        return $dest;
    }

    /**
     * Add new cc
     *
     * @param string $p_address
     * @param string $p_name
     *
     * @return \FreeFW\Model\Message
     */
    public function addCC($p_address, $p_name = null)
    {
        $dest = json_decode($this->msg_cc);
        if (!is_array($dest)) {
            $dest = [];
        }
        $stdObj = new \stdClass();
        $stdObj->address = $p_address;
        if ($p_name != '') {
            $stdObj->name = $p_name;
        }
        $dest[] = $stdObj;
        $this->msg_cc = json_encode($dest);
        return $this;
    }

    /**
     * Get cc
     *
     * @return array
     */
    public function getCC()
    {
        $cc = json_decode($this->msg_cc);
        if (!is_array($cc)) {
            $cc = [];
        }
        return $cc;
    }

    /**
     * Add new bcc
     *
     * @param string $p_address
     * @param string $p_name
     *
     * @return \FreeFW\Model\Message
     */
    public function addBCC($p_address, $p_name = null)
    {
        $dest = json_decode($this->msg_bcc);
        if (!is_array($dest)) {
            $dest = [];
        }
        $stdObj = new \stdClass();
        $stdObj->address = $p_address;
        if ($p_name != '') {
            $stdObj->name = $p_name;
        }
        $dest[] = $stdObj;
        $this->msg_bcc = json_encode($dest);
        return $this;
    }

    /**
     * Get bcc
     *
     * @return array
     */
    public function getBCC()
    {
        $bcc = json_decode($this->msg_bcc);
        if (!is_array($bcc)) {
            $bcc = [];
        }
        return $bcc;
    }

    /**
     * Set from
     *
     * @param string $p_address
     * @param string $p_name
     *
     * @return \FreeFW\Model\Message
     */
    public function setFrom($p_address, $p_name = null)
    {
        $stdObj = new \stdClass();
        $stdObj->address = $p_address;
        if ($p_name != '') {
            $stdObj->name = $p_name;
        }
        $this->msg_from = json_encode($stdObj);
        return $this;
    }

    /**
     * Get from
     *
     * @return string
     */
    public function getFrom()
    {
        return json_decode($this->msg_from);
    }

    /**
     * Set reply to
     *
     * @param string $p_address
     * @param string $p_name
     *
     * @return \FreeFW\Model\Message
     */
    public function setReplyTo($p_address, $p_name = null)
    {
        $stdObj = new \stdClass();
        $stdObj->address = $p_address;
        if ($p_name != '') {
            $stdObj->name = $p_name;
        }
        $this->msg_reply_to = json_encode($stdObj);
        return $this;
    }

    /**
     * Get reply to
     *
     * @return string
     */
    public function getReplyTo()
    {
        return json_decode($this->msg_reply_to);
    }

    /**
     * Get all pjs as attachment
     *
     * @return string[]
     */
    public function getMailAttachmentsAsArray()
    {
        $files = [];
        $file  = $this->getMsgPj1();
        if ($file && $file != '' && is_file($file)) {
            $name = $this->getMsgPj1Name();
            if ($name == '') {
                $name = basename($file);
            }
            $files[$name] = $file;
        }
        $file  = $this->getMsgPj2();
        if ($file && $file != '' && is_file($file)) {
            $name = $this->getMsgPj2Name();
            if ($name == '') {
                $name = basename($file);
            }
            $files[$name] = $file;
        }
        $file  = $this->getMsgPj3();
        if ($file && $file != '' && is_file($file)) {
            $name = $this->getMsgPj3Name();
            if ($name == '') {
                $name = basename($file);
            }
            $files[$name] = $file;
        }
        $file  = $this->getMsgPj4();
        if ($file && $file != '' && is_file($file)) {
            $name = $this->getMsgPj4Name();
            if ($name == '') {
                $name = basename($file);
            }
            $files[$name] = $file;
        }
        return $files;
    }

    /**
     * Try to send message
     */
    public function send()
    {
        /**
         * @var \FreeFW\Interfaces\MessageSenderInterface $mailer
         */
        $mailer = false;
        try {
            if ($this->msg_type === self::TYPE_EMAIL) {
                $mailer = \FreeFW\DI\DI::get('emailMailer');
            }
            if ($mailer) {
                $this
                    ->setMsgStatus(self::STATUS_PENDING)
                    ->save()
                ;
                if ($mailer->send($this)) {
                    $this
                        ->setMsgStatus(self::STATUS_OK)
                        ->setMsgSendTs(\FreeFW\Tools\Date::getCurrentTimestamp())
                        ->save()
                    ;
                } else {
                    $this
                        ->setMsgStatus(self::STATUS_ERROR)
                        ->setMsgSendTs(\FreeFW\Tools\Date::getCurrentTimestamp())
                        ->setMsgSendError($mailer->getError())
                        ->save()
                    ;
                }
            }
        } catch (\Exception $ex) {
            $this
                ->setMsgStatus(self::STATUS_ERROR)
                ->setMsgSendTs(\FreeFW\Tools\Date::getCurrentTimestamp())
                ->setMsgSendError($ex->getMessage())
                ->save()
            ;
        }
    }
}
