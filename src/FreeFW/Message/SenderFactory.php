<?php 
namespace FreeFW\Message;

/**
 * 
 * @author jeromeklam
 *
 */
class SenderFactory
{

    /**
     * @return \FreeFW\Interfaces\MessageSenderInterface
     */
    public static function getDefaultEmailSender()
    {
        $config  = \FreeFW\Application\Config::getInstance();
        $mailCfg = $config->get('email');
        $mailer  = null;
        if (is_array($mailCfg)) {
            $mailer = new \FreeFW\Mail\PHPMailer($mailCfg);
        }
        return $mailer;
    }
}
