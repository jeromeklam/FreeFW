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
    use \FreeSSO\Model\Behaviour\Group;

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
     * Get new Message
     *
     * @return void
     */
    public static function getFactory($p_object_name = null, $p_object_id = null)
    {
        $config  = \FreeFW\DI\DI::getShared('config');
        $email   = $config->get('email');
        $message = new \FreeFW\Model\Message();
        $message
            ->setMsgObjectName($p_object_name)
            ->setMsgObjectId($p_object_id)
            ->setMsgType(\FreeFW\Model\Message::TYPE_EMAIL)
            ->setMsgStatus(\FreeFW\Model\Message::STATUS_WAITING)
            ->setFrom($email['from_email'], $email['from_name'])
        ;
        return $message;
    }

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
        $cfg   = $this->getAppConfig();
        $max   = $cfg->get('email:maxEmails', 80);
        $dChk  = date('YmdH');
        $check = rtrim(APP_CACHE, '/') . '/' . $dChk . '.txt';
        $count = 0;
        if (is_file($check)) {
            $count = intval(@file_get_contents($check));
        }
        $count++;
        if ($count >= $max) {
            return true;
        }
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
                    if ($this->getMsgRetry() <= 3) {
                        $this
                            ->setMsgStatus(self::STATUS_WAITING)
                            ->setMsgSendError($mailer->getError())
                            ->incrementTry()
                            ->save()
                        ;
                    } else {
                        $this
                            ->setMsgStatus(self::STATUS_ERROR)
                            ->setMsgSendTs(\FreeFW\Tools\Date::getCurrentTimestamp())
                            ->setMsgSendError($mailer->getError())
                            ->incrementTry()
                            ->save()
                        ;
                    }
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
        @file_put_contents($check, $count);
        return true;
    }

    /**
     * Add attachment
     *
     * @param string $p_filename
     * @param string $p_name
     *
     * @return \FreeFW\Model\Message
     */
    public function addAttachment($p_filename, $p_name)
    {
        if (!$this->getMsgPj1()) {
            $this->setMsgPj1Name($p_name)->setMsgPj1($p_filename);
        } else {
            if (!$this->getMsgPj2()) {
                $this->setMsgPj2Name($p_name)->setMsgPj2($p_filename);
            } else {
                if (!$this->getMsgPj3()) {
                    $this->setMsgPj3Name($p_name)->setMsgPj3($p_filename);
                } else {
                    if (!$this->getMsgPj4()) {
                        $this->setMsgPj4Name($p_name)->setMsgPj4($p_filename);
                    }
                }
            }
        }
        return $this;
    }

    /**
     * Before all
     *
     * @return boolean
     */
    protected function before()
    {
        $cfg = $this->getAppConfig();
        $email = $cfg->get('email');
        if (!is_array($email) || !isset($email['real'])) {
            $dest = 'jerome.klam@free.fr';
            $sso = \FreeFW\DI\DI::getShared('sso');
            if ($sso) {
                $user = $sso->getUser();
                if ($user) {
                    $dest = $user->getUserEmail();
                }
            }
            $this
                ->setMsgBcc(null)
                ->setMsgCc(null)
                ->setMsgDest(null)
                ->addDest($dest)
            ;
        }
        return true;
    }

    /**
     * Before save
     *
     * @return boolean
     */
    public function beforeSave()
    {
        return $this->before();
    }

    /**
     * Before create
     *
     * @return boolean
     */
    public function beforeCreate()
    {
        return $this->before();
    }

    /**
     * Add one try
     * 
     * @return self
     */
    protected function incrementTry()
    {
        $this->msg_retry = intval($this->msg_retry) + 1;
        return $this;
    }
}
