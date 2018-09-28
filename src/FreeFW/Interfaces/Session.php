<?php
/**
 * Interface de base de gestion d'une session
 *
 * @author jeromeklam
 * @package Session
 * @category Tech
 */
namespace FreeFW\Interfaces;

/**
 * Interface session
 * @author klam
 */
interface Session
{

    /**
     * Démarre une session
     *
     * @param string $p_sessId
     *
     * @return boolean
     */
    public function start($p_sessId);

    /**
     * Session démarrée ?
     *
     * @return boolean
     */
    public function isStarted();

    /**
     * Détruit la session
     *
     * @return boolean
     */
    public function destroy();

    /**
     * Retourne l'identifiant de session
     *
     * @return string
     */
    public function getId();

    /**
     * Vérifie l'existance d'une clef
     *
     * @param string $p_key
     *
     * @return boolean
     */
    public function has($p_key);

    /**
     * Retourne une clef de la session
     *
     * @param string $p_key
     *
     * @return mixed
     */
    public function get($p_key);

    /**
     * Affectation d'une valeur
     *
     * @param string $p_key
     * @param mixed  $p_value
     *
     * @return this
     */
    public function set($p_key, $p_value);

    /**
     * Suppression d'une clef
     *
     * @param string $p_key
     *
     * @return boolean
     */
    public function remove($p_key);
}
