<?php
/**
 * Classe de gestion d'un message
 *
 * @author jeromeklam
 * @package Message
 */
namespace FreeFW\Message;

/**
 * Message
 * @author jeromeklam
 */
abstract class AbstractMessage extends \FreeFW\Model\AbstractNoStorage implements \FreeFW\Interfaces\Message
{

    /**
     * Type de message
     *
     * @var string
     */
    protected $type = self::TYPE_INFO;

    /**
     * Message
     *
     * @var string
     */
    protected $message = null;

    /**
     * Message supprimé
     *
     * @var boolean
     */
    protected $removed = false;

    /**
     * Constructeur
     *
     * @param string $p_type
     * @param string $p_message
     */
    public function __construct($p_type = self::TYPE_INFO, $p_message = null)
    {
        $this
            ->setType($p_type)
            ->setMessage($p_message);
    }

    /**
     * Retourne un message ok
     *
     * @param string $p_message
     *
     * @return \FreeFW\Message\Flash
     */
    public static function getSuccessMessage($p_message)
    {
        return new static(self::TYPE_SUCCESS, $p_message);
    }

    /**
     * Retourne un message d'information
     *
     * @param string $p_message
     *
     * @return \FreeFW\Message\Flash
     */
    public static function getInfoMessage($p_message)
    {
        return new static(self::TYPE_INFO, $p_message);
    }

    /**
     * Retourne un message de warning
     *
     * @param string $p_message
     *
     * @return \FreeFW\Message\Flash
     */
    public static function getWarningMessage($p_message)
    {
        return new static(self::TYPE_WARNING, $p_message);
    }

    /**
     * Retourne un message de danger
     *
     * @param string $p_message
     *
     * @return \FreeFW\Message\Flash
     */
    public static function getDangerMessage($p_message)
    {
        return new static(self::TYPE_DANGER, $p_message);
    }

    /**
     * Supprimer le message
     *
     * @return string
     */
    public function remove()
    {
        $this->removed = true;
        
        return '';
    }

    /**
     * Message encore valide ??
     *
     * @return boolean
     */
    public function isValid()
    {
        return !$this->removed;
    }

    /**
     * Retourne le message sous forme de tableau
     *
     * @return array
     */
    public function __toArray()
    {
        return array(
            'type'    => $this->getType(),
            'message' => $this->getMessage()
        );
    }

    /**
     * Affectation d'un message
     *
     * @param mixed $p_message
     *
     * @return \static
     */
    public function setMessage($p_message)
    {
        $this->message = $p_message;
        
        return $this;
    }

    /**
     * Retourne le message
     *
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Affectation du type
     *
     * @param string $p_type
     *
     * @return \static
     */
    public function setType($p_type)
    {
        $this->type = $p_type;
        
        return $this;
    }

    /**
     * Retourne le type
     *
     * @erturn string
     */
    public function getType()
    {
        return $this->type;
    }
}
