<?php
namespace FreeFW\Session;

/**
 * Stockage de la session
 *
 * @author jeromeklam
 * @package Storage
 */
abstract class Storage
{

    /**
     *
     */
    protected static $session = null;

    /**
     * Liaison
     *
     * @param \FreeFW\Interfaces\Session $p_session
     */
    public function link($p_session)
    {
        self::$session = $p_session;
        if (self::$session !== null) {
            $this->restoreFromSession(self::$session);
        }
    }

    /**
     * Destructeur
     */
    public function __destruct()
    {
        if (self::$session !== null) {
            $this->storeToSession(self::$session);
        }
    }

    /**
     * Sauvegarde en session
     *
     * @param \FreeFW\Interfaces\Session $p_session
     */
    abstract protected function storeToSession(\FreeFW\Interfaces\Session $p_session);

    /**
     * Récupération de la session
     *
     * @param \FreeFW\Interfaces\Session $p_session
     */
    abstract protected function restoreFromSession(\FreeFW\Interfaces\Session $p_session);
}
