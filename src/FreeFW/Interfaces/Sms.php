<?php
namespace FreeFW\Interfaces;

/**
 *
 * @author jeromeklam
 *
 */
interface Sms
{
    /**
     * Liste des services
     *
     * @param boolean $p_withDetails
     *
     * @return array
     */
    public function getAccounts($p_withDetails = true);

    /**
     * Envoi d'un message
     *
     * @param \FreeFW\Sms\Model\Message $p_message
     *
     * @return boolean
     */
    public function send($p_message);
}
