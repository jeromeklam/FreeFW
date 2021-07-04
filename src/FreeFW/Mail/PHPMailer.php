<?php
namespace FreeFW\Mail;

/**
 *
 * @author jeromeklam
 *
 */
class PHPMailer implements
    \Psr\Log\LoggerAwareInterface,
    \FreeFW\Interfaces\ConfigAwareTraitInterface,
    \FreeFW\Interfaces\MessageSenderInterface
{

    /**
     * comportements
     */
    use \Psr\Log\LoggerAwareTrait;
    use \FreeFW\Behaviour\EventManagerAwareTrait;
    use \FreeFW\Behaviour\ConfigAwareTrait;

    /**
     * Config
     * @var array
     */
    protected $config = null;

    /**
     * Mailer
     * @var mixed
     */
    protected $mailer = null;

    /**
     * Error
     * @var string
     */
    protected $error = false;

    /**
     *
     * @param array $p_config
     */
    public function __construct($p_config)
    {
        $this->config = $p_config;
        $this->mailer = new \PHPMailer\PHPMailer\PHPMailer();
    }

    /**
     * Send message
     *
     * @param \FreeFW\Model\Message $p_message
     *
     * @return bool
     */
    public function send(\FreeFW\Model\Message $p_message) : bool
    {
        $this->error = false;
        if ($this->mailer !== null) {
            $bcc = [];
            if (array_key_exists('bcc', $this->config) && $this->config['bcc'] != '') {
                $bcc = $this->config['bcc'];
            }
            // From
            $fromEmail = '';
            $fromName  = '';
            $from      = $p_message->getFrom();
            if (!$from) {
                if (array_key_exists('fromName', $this->config) && $this->config['fromEmail'] != '') {
                    $fromEmail = $this->config['fromEmail'];
                }
                if (array_key_exists('fromName', $this->config) && $this->config['fromName'] != '') {
                    $fromName = $this->config['fromName'];
                }
            } else {
                if ($from->address) {
                    $fromEmail = $from->address;
                    if ($from->name) {
                        $fromName = $from->name;
                    }
                }
            }
            if ($fromEmail == '') {
                return false;
            }
            // ReplyTo
            $replyEmail = '';
            $replyName  = '';
            $replyTo    = $p_message->getReplyTo();
            if (!$replyTo) {
                if (array_key_exists('replyEmail', $this->config) && $this->config['replyEmail'] != '') {
                    $replyEmail = $this->config['replyEmail'];
                }
                if (array_key_exists('replyName', $this->config) && $this->config['replyName'] != '') {
                    $replyName = $this->config['replyName'];
                }
            } else {
                if (isset($replyTo->address)) {
                    $replyEmail = $replyTo->address;
                    if (isset($replyTo->name)) {
                        $replyName = $replyTo->name;
                    }
                }
            }
            if ($replyEmail == '') {
                return false;
            }
            switch (strtoupper($this->config['mode'])) {
                case 'SMTP':
                    $this->mailer->isSMTP();
                    $this->mailer->Host     = $this->config['server'];
                    $this->mailer->Port     = $this->config['port'];
                    $this->mailer->SMTPAuth = false;
                    if (array_key_exists('secure', $this->config) && $this->config['secure'] != '') {
                        $this->mailer->SMTPAuth   = true;
                        $this->mailer->Username   = $this->config['username'];
                        $this->mailer->Password   = $this->config['password'];
                        $this->mailer->SMTPSecure = $this->config['secure'];
                    }
                    break;
                case 'MAIL':
                    $this->mailer->isMail();
                    break;
                case 'SENDMAIL':
                case 'MAILHOG':
                    $this->mailer->isSendmail();
                    break;
                case 'MOCK':
                    return true;
            }
            // Nettoyage
            $this->mailer->clearAddresses();
            $this->mailer->clearAttachments();
            $this->mailer->clearAllRecipients();
            $this->mailer->clearCustomHeaders();
            $this->mailer->ClearReplyTos();
            // ReplyTo
            $this->mailer->addReplyTo($replyEmail, $replyName);
            // Emetteur, en authentifié on utilise forcément le username...
            $this->mailer->setFrom($fromEmail, $fromName);
            // Destinataires
            if (isset($this->config['real'])) {
                foreach ($p_message->getDest() as $dest) {
                    $this->mailer->addAddress($dest->address);
                }
                foreach ($p_message->getCC() as $cc) {
                    $this->mailer->addCC($cc->address);
                }
                foreach ($p_message->getBCC() as $bcc) {
                    $this->mailer->addBCC($bcc->address);
                }
            } else {
                $dest = 'jeromeklam@free.fr';
                $this->mailer->addAddress($dest);
            }
            if ($bcc !== false) {
                if (is_array($bcc)) {
                    foreach ($bcc as $bcc) {
                        $this->mailer->addBCC($bcc->address);
                    }
                }
            }
            foreach ($p_message->getMailAttachmentsAsArray() as $name => $file) {
                $this->mailer->addAttachment($file, $name);
            }
            $this->mailer->isHTML(true);
            $this->mailer->CharSet = 'UTF-8';
            $this->mailer->Subject = $p_message->getMsgSubject();
            $htmlBody = $p_message->getMsgBody();
            $this->mailer->Body = '<html><body>' . $htmlBody . '</body></html>';
            $this->mailer->AltBody = strip_tags(str_replace("<br />", "\n", $htmlBody));
            // Petite pause avant l'envoi...
            sleep(1);
            $result = $this->mailer->send();
            if ($result === false || $this->mailer->isError()) {
                //$this->logger->debug(print_r($this->mailer->ErrorInfo, true));
                $this->error = $this->mailer->ErrorInfo;
                return false;
            }
            return $result;
        }
        return false;
    }

    /**
     * Get error
     *
     * @return string
     */
    public function getError() : string
    {
        return sprintf($this->error, true);
    }
}
