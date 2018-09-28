<?php
namespace FreeFW\Session;

/**
 * Gestion des sessions
 *
 * @author jeromeklam
 * @package Web\Session
 */
class File implements \FreeFW\Interfaces\Session
{

    /**
     * Constructeur
     *
     * @param boolean $p_start
     */
    public function __construct($p_start = false)
    {
        if ($p_start) {
            $this->start();
        }
    }

    /**
     * Démarre une session
     *
     * @param string $p_sessId
     *
     * @return boolean
     */
    public function start($p_sessId = null)
    {
        if ($p_sessId !== null && !$this->isStarted()) {
            session_id($p_sessId);
        }
        
        return session_start();
    }

    /**
     * Session démarrée ?
     *
     * @return boolean
     */
    public function isStarted()
    {
        if (session_id() == '') {
            return false;
        }
        
        return true;
    }

    /**
     * Détruit la session
     *
     * @return boolean
     */
    public function destroy()
    {
        return session_destroy();
    }

    /**
     * Retourne l'identifiant de session
     *
     * @return string
     */
    public function getId()
    {
        return session_id();
    }

    /**
     * Vérifie l'existance d'une clef
     *
     * @param string $p_key
     *
     * @return boolean
     */
    public function has($p_key)
    {
        if (!isset($_SESSION[$p_key])) {
            return false;
        }
        
        return true;
    }

    /**
     * Retourne une clef de la session
     *
     * @param string $p_key
     *
     * @return mixed
     */
    public function get($p_key)
    {
        if (!isset($_SESSION[$p_key])) {
            return false;
        }
        
        return $_SESSION[$p_key];
    }

    /**
     * Affectation d'une valeur
     *
     * @param string $p_key
     * @param mixed  $p_value
     *
     * @return this
     */
    public function set($p_key, $p_value)
    {
        $_SESSION[$p_key] = $p_value;
        
        return $this;
    }

    /**
     * Suppression d'une clef
     *
     * @param string $p_key
     *
     * @return boolean
     */
    public function remove($p_key)
    {
        if (isset($_SESSION[$p_key])) {
            unset($_SESSION[$p_key]);
        }
        
        return true;
    }
}
