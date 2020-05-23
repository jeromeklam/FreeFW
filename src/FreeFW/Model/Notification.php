<?php
namespace FreeFW\Model;

use \FreeFW\Constants as FFCST;

/**
 * Notification
 *
 * @author jeromeklam
 */
class Notification extends \FreeFW\Model\Base\Notification
{

    /**
     * Type
     * @var string
     */
    const TYPE_ERROR       = 'ERROR';
    const TYPE_WARNING     = 'WARNING';
    const TYPE_INFORMATION = 'INFORMATION';
    const TYPE_MANUAL      = 'MANUAL';
    const TYPE_OTHER       = 'OTHER';

    /**
     * Prevent from saving history
     * @var bool
     */
    protected $no_history = true;

    /**
     * Read user
     * @var \FreeSSO\Model\User
     */
    protected $user = null;

    /**
     *
     * {@inheritDoc}
     * @see \FreeFW\Core\Model::init()
     */
    public function init()
    {
        $this->notif_id   = 0;
        $this->notif_type = self::TYPE_OTHER;
        $this->user_id    = null;
        $this->notif_read = 0;
        return $this;
    }

    /**
     * Set user
     *
     * @param \FreeSSO\Model\User $p_user
     *
     * @return \FreeFW\Model\Notification
     */
    public function setUser($p_user)
    {
        $this->user = $p_user;
        return $this;
    }

    /**
     * Get user
     *
     * @return \FreeSSO\Model\User
     */
    public function getUser()
    {
        return $this->user;
    }
}
