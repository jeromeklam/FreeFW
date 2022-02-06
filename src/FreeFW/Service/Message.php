<?php
namespace FreeFW\Service;

/**
 * Message
 *
 * @author jeromeklam
 */
class Message extends \FreeFW\Core\Service
{

    /**
     * 
     */
    public function sendEmails()
    {
        /**
         * @var \FreeAsso\Model\Sponsorship $sponsorship
         */
        $message = \FreeFW\DI\DI::get('FreeFW::Model::Message');
        /**
         * @var \FreeFW\Model\Query $query
         */
        $query = $message->getQuery();
        $query->addFromFilters(
            [
                'msg_status' => \FreeFW\Model\Message::STATUS_WAITING
            ]
        );
        if ($query->execute()) {
            /**
             * @var \FreeFW\Model\ResultSet $results
             */
            $results = $query->getResult();
            if ($results->count() > 0) {
                foreach ($results as $message) {
                    $message->send();
                }
            }
        }
    }

    /**
     * Send waiting emails
     * 
     * @param array $p_params
     * 
     * @return boolean
     */
    public function sendMessage($p_params = [])
    {
        $this->logger->debug('Message.sendMessage.START');
        $this->sendEmails();
        $this->logger->debug('Message.sendMessage.END');
        return $p_params;
    }

    /**
     * Send administrator email
     *
     * @param string $p_subject
     * @param string $p_body
     * 
     * @return bool
     */
    public function sendAdminMessage($p_subject, $p_body)
    {
        $config  = $this->getAppConfig('email');
        $fEmail  = 'administration@kalaweit.org';
        $fName   = 'Administration Kalaweit';
        if (is_array($config)) {
            if (isset($config["from_name"])) {
                $fName = $config->get('from_name');
            }
            if (isset($config["from_email"])) {
                $fEmail = $config->get('from_email');
            }
        }
        $message = new \FreeFW\Model\Message();
        $message
            ->addDest('jeromeklam@free.fr')
            ->setMsgSubject($p_subject)
            ->setMsgBody($p_body)
            ->setMsgType(\FreeFW\Model\Message::TYPE_EMAIL)
            ->setMsgStatus(\FreeFW\Model\Message::STATUS_WAITING)
            ->setFrom($fEmail, $fName)
        ;
        if ($message->create()) {
            return $message->send();
        }
        return false;
    }
}
