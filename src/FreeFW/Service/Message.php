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
                'msg_type'   => \FreeFW\Model\Message::TYPE_EMAIL,
                'msg_status' => \FreeFW\Model\Message::STATUS_WAITING
            ]
        );
        if ($query->execute()) {
            /**
             * @var \FreeFW\Interfaces\MessageSenderInterface $sender
             */
            $sender = \FreeFW\DI\DI::get('mailer');
            /**
             * @var \FreeFW\Model\ResultSet $results
             */
            $results = $query->getResult();
            if ($results->count() > 0) {
                foreach ($results as $message) {
                    
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
}
