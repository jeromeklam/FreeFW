<?php
namespace FreeFW\Model;

use \FreeFW\Constants as FFCST;

/**
 * Model Inbox
 *
 * @author jeromeklam
 */
class Inbox extends \FreeFW\Model\Base\Inbox
{

    /**
     * Comportements
     */
    use \FreeSSO\Model\Behaviour\User;
    use \FreeSSO\Model\Behaviour\Group;

    /**
     * Prevent from saving history
     * @var bool
     */
    protected $no_history = true;
}
