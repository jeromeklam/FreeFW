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
     * Behaviour
     */
    use \FreeSSO\Model\Behaviour\User;
    use \FreeSSO\Model\Behaviour\Group;

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
}
