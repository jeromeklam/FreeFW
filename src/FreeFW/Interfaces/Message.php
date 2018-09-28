<?php
/**
 * Interface de description d'un message
 *
 * @author jeromeklam
 * @package Message
 */
namespace FreeFW\Interfaces;

/**
 * Message
 * @author jeromeklam
 */
interface Message
{

    /**
     * Types de message
     * @var string
     */
    const TYPE_SUCCESS = 'SUCCESS';
    const TYPE_WARNING = 'WARNING';
    const TYPE_DANGER  = 'DANGER';
    const TYPE_ERROR   = 'DANGER';
    const TYPE_INFO    = 'INFO';

    /**
     * Affectation d'un message
     *
     * @param mixed $p_message
     *
     * @return \static
     */
    public function setMessage($p_message);

    /**
     * Retourne le message
     *
     * @return mixed
     */
    public function getMessage();

    /**
     * Affectation du type
     *
     * @param string $p_type
     *
     * @return \static
     */
    public function setType($p_type);

    /**
     * Retourne le type
     *
     * @erturn string
     */
    public function getType();
}
