<?php
namespace FreeFW\Model;

use \FreeFW\Constants as FFCST;

/**
 * Model Version
 *
 * @author jeromeklam
 */
class Version extends \FreeFW\Model\Base\Version
{

    /**
     * Status
     * @var string
     */
    const STATUS_WAITING = 'WAITING';
    const STATUS_PENDING = 'PENDING';
    const STATUS_OK      = 'OK';
    const STATUS_ERROR   = 'ERROR';

    /**
     * Prevent from saving history
     * @var bool
     */
    protected $no_history = true;
}
