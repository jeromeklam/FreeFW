<?php
namespace FreeFW\Model;

use \FreeFW\Constants as FFCST;

/**
 * Model Edition
 *
 * @author jeromeklam
 */
class Edition extends \FreeFW\Model\Base\Edition
{
    /**
     * Behaviours
     */
    use \FreeSSO\Model\Behaviour\Broker;
}
