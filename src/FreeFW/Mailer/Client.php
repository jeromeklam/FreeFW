<?php
namespace FreeFW\Mailer;

/**
 * Client mail permettant d'abstraire le serveur utilisé
 *
 * @author jeromeklam
 * @package Mail
 */
class Client
{

    /**
     * La configuration
     * @var array
     */
    protected $config = null;

    /**
     * Instance de classe
     * @var \FreeFW\Mailer\Client
     */
    protected static $instance = null;

    /**
     * Mailer
     * @var unknown
     */
    protected $mailer = null;

    /**
     * Constructeur
     * @param array $p_config
     */
    protected function __construct($p_config = null)
    {
        $this->config = $p_config;
    }

    /**
     * Retourne une instance
     *
     * @param array $p_config
     *
     * @return \FreeFW\Mailer\Client
     */
    public static function getInstance($p_config = null)
    {
        if (self::$instance === null) {
            self::$instance = new self($p_config);
        }
        return self::$instance;
    }

    /**
     * Envoi d'un email
     *
     * @param \FreeFW\Interfaces\Email $p_email
     *
     * @return boolean
     */
    public function sendEmail(\FreeFW\Interfaces\Email $p_email)
    {
        $this->mailer = new \PHPMailer();
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
            switch ($this->config['mode']) {
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
                $this->mailer->setFrom($forceEmail, $p_email->getMailFromName());
            } else {
                $this->mailer->setFrom($p_email->getMailFromEmail(), $p_email->getMailFromName());
            }
            // Destinataires
            foreach ($p_email->getMailToAsArray() as $email => $name) {
                $this->mailer->addAddress($email);
            }
            foreach ($p_email->getMailCcAsArray() as $email => $name) {
                $this->mailer->addCC($email);
            }
            foreach ($p_email->getMailBccAsArray() as $email => $name) {
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
            foreach ($p_email->getMailAttachmentsAsArray() as $idx => $file) {
                $this->mailer->addAttachment($file);
            }
            $this->mailer->isHTML(true);
            $this->mailer->CharSet = 'UTF-8';
            $this->mailer->Subject = $p_email->getMailSubject();
            $htmlBody = $p_email->getMailBodyHtml();
            if ($htmlBody === null || $htmlBody == '') {
                $this->mailer->Body = nl2br($p_email->getMailBodyText());
            } else {
                $this->mailer->Body = '<html><body>' . $htmlBody . '</body></html>';
            }
            $this->mailer->Text = $p_email->getMailBodyText();
            // Petite pause avant l'envoi...
            sleep(5);
            $result = $this->mailer->send();
            if ($result === false || $this->mailer->isError()) {
                return $this->mailer->ErrorInfo;
            }

            return $result;
        }

        return false;
    }
}
