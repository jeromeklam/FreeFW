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
        $forceEmail   = false;
        if ($this->mailer !== null) {
            $bcc = false;
            if (array_key_exists('bcc', $this->config) && $this->config['bcc'] != '') {
                $bcc = $this->config['bcc'];
            }
            $replyEmail = false;
            if (array_key_exists('replyEmail', $this->config) && $this->config['replyEmail'] != '') {
                $replyEmail = $this->config['replyEmail'];
            }
            $replyName = '';
            if (array_key_exists('replyName', $this->config) && $this->config['replyName'] != '') {
                $replyName = $this->config['replyName'];
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
                        $forceEmail               = $this->config['username'];
                    }
                    self::debug(print_r($this->config, true));
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
            if ($replyEmail !== false) {
                $this->mailer->addReplyTo($replyEmail, $replyName);
            }
            // Emetteur, en authentifié on utilise forcément le username...
            if ($forceEmail !== false) {
                $this->mailer->setFrom($forceEmail, $p_message->getMailFromName());
            } else {
                $this->mailer->setFrom($p_message->getMailFromEmail(), $p_message->getMailFromName());
            }
            // Destinataires
            foreach ($p_message->getMailToAsArray() as $email => $name) {
                $this->mailer->addAddress($email);
            }
            foreach ($p_message->getMailCcAsArray() as $email => $name) {
                $this->mailer->addCC($email);
            }
            foreach ($p_message->getMailBccAsArray() as $email => $name) {
                $this->mailer->addBCC($email);
            }
            if ($bcc !== false) {
                if (is_array($bcc)) {
                    foreach ($bcc as $email => $name) {
                        $this->mailer->addBCC($email);
                    }
                } else {
                    $this->mailer->addBCC($bcc);
                }
            }
            foreach ($p_message->getMailAttachmentsAsArray() as $idx => $file) {
                $this->mailer->addAttachment($file);
            }
            $this->mailer->isHTML(true);
            $this->mailer->CharSet = 'UTF-8';
            $this->mailer->Subject = $p_message->getMailSubject();
            $htmlBody = $p_message->getMailBodyHtml();
            if ($htmlBody === null || $htmlBody == '') {
                $this->mailer->Body = nl2br($p_message->getMailBodyText());
            } else {
                $this->mailer->Body = '<html><body>' . $htmlBody . '</body></html>';
            }
            $this->mailer->Text = $p_message->getMailBodyText();
            // Petite pause avant l'envoi...
            sleep(1);
            $result = $this->mailer->send();
            if ($result === false || $this->mailer->isError()) {
                self::error(print_r($this->mailer->ErrorInfo, true));
                return $this->mailer->ErrorInfo;
            }
            return $result;
        }
        return false;
    }
}
