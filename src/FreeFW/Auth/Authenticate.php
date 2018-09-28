<?php
namespace FreeFW\Auth;

/**
 *
 * @author jeromejklam
 */
abstract class Authenticate
{

    /**
     * Comportements
     */
    use \FreeFW\Behaviour\DI;
    use \FreeFW\Behaviour\LoggerAwareTrait;

    /**
     * Message Hmac
     * @var object
     */
    protected $msg;

    /**
     * Identifier
     * @var string
     */
    protected $identifier;

    /**
     * Clef privée
     * @var string
     */
    protected $privateKey;

    /**
     * Délai maximum autorisé
     * @var int
     */
    protected $maxRequestDelay = 60; // 1 minute

    /**
     * Constructeur
     *
     * @param mixed  $p_message
     * @param string $p_privateKey
     */
    public function __construct($p_message, $p_identifier, $p_privateKey = null)
    {
        $this->msg        = $p_message;
        $this->identifier = $p_identifier;
        $this->privateKey = $p_privateKey;
    }

    /**
     * Vérification de la requête
     *
     * @param boolean $p_authenticated
     * @param boolean $p_allowed
     *
     * @return boolean
     */
    abstract public function checkRequest($p_authenticated = true, $p_allowed = false);
}
