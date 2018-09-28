<?php
/**
 * Interface de case d'une réponse
 *
 * @author jeromeklam
 * @package Response
 * @category Response
 */
namespace FreeFW\Interfaces;

/**
 * Réponse classique (interface)
 * @auhor jeromeklam
 */
interface Response
{

    /**
     * Modes d'affichage
     *
     * @var string
     */
    const MODE_DOWNLOAD = 'DOWNLOAD';
    const MODE_SHOW     = 'SHOW';

    /**
     * Modes de redirection
     * @var string
     */
    const REDIRECT_NONE      = 'NONE';
    const REDIRECT_IMMEDIATE = 'IMMEDIATE';
    const REDIRECT_STANDARD  = 'STANDARD';

    /**
     * Types de réponse
     * @var string
     */
    const TYPE_OTHER = 'OTHER';
    const TYPE_JSON  = 'JSON';

    /**
     * Affectation du status
     *
     * @var number $p_status
     * @var string $p_message
     *
     * @return \FreeFW\Http\Response
     */
    public function setStatus($p_status, $p_message = null);

    /**
     * Retourne le status
     *
     * @return number
     */
    public function getStatus();

    /**
     * Affectation du message
     *
     * @return \FreeFW\Http\Response
     */
    public function setMessage($p_message = null);

    /**
     * Retourne le message
     *
     * @return string
     */
    public function getMessage();

    /**
     * Afectation du contenu
     *
     * @var mixed $p_content
     *
     * @return \FreeFW\Http\Response
     */
    public function setContent($p_content = null);

    /**
     * Retourne le contenu
     *
     * @return mixed
     */
    public function getContent();

    /**
     * Génération de la réponse
     *
     * @return void;
     */
    public function render();
}
