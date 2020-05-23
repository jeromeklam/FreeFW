<?php
namespace FreeFW\Model;

use \FreeFW\Constants as FFCST;

/**
 * Model Rate
 *
 * @author jeromeklam
 */
class Rate extends \FreeFW\Model\Base\Rate
{

    /**
     * Prevent from saving history
     * @var bool
     */
    protected $no_history = true;
}
