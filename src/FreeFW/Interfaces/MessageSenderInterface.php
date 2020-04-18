<?php 
namespace FreeFW\Interfaces;

/**
 * 
 * @author jeromeklam
 *
 */
interface MessageSenderInterface
{

    /**
     * Send message
     * 
     * @param \FreeFW\Model\Message $p_message
     * 
     * @return bool
     */
    public function send(\FreeFW\Model\Message $p_message) : bool;
}
