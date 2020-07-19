<?php
namespace FreeFW\Model;

/**
 * Model History
 *
 * @author jeromeklam
 */
class History extends \FreeFW\Model\Base\History
{

    /**
     * Behaviour
     */
    use \FreeSSO\Model\Behaviour\User;

    /**
     * Prevent from saving history
     * @var bool
     */
    protected $no_history = true;
}
