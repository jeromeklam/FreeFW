<?php
/**
 * Gestion de la réponse pour une console
 *
 * @author jeromeklam
 * @package Command
 */
namespace FreeFW\Console;

/**
 * Response classique console
 * @author jeromeklam
 */
class Response implements \FreeFW\Interfaces\Response
{

    /**
     * Status
     *
     * @var number
     */
    protected $status = 0;

    /**
     * Message
     *
     * @var string
     */
    protected $message = 'ok';

    /**
     * Content
     *
     * @var mixed
     */
    protected $content = null;

    /**
     * Affectation du status
     *
     * @var number $pstatus
     * @var string $pmessage
     *
     * @return \FreeFW\Http\Response
     */
    public function setStatus($p_status, $p_message = null)
    {
        $this->status = $p_status;
        if ($p_message !== null) {
            $this->message = $p_message;
        }
        
        return $this;
    }

    /**
     * Retourne le status
     *
     * @return number
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Affectation du message
     *
     * @return \FreeFW\Http\Response
     */
    public function setMessage($p_message = null)
    {
        $this->message = $p_message;
        
        return $this;
    }

    /**
     * Retourne le message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Afectation du contenu
     *
     * @var mixed $p_content
     *
     * @return \FreeFW\Http\Response
     */
    public function setContent($p_content = null)
    {
        $this->content = $p_content;
        
        return $this;
    }

    /**
     * Retourne le contenu
     *
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Génération du contenu
     * Ici on ne fait qu'envoyer le code retour pour l'instant
     */
    public function render()
    {
        exit($this->status);
    }
}
