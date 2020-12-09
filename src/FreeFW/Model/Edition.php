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
     * Types
     * @var string
     */
    const TYPE_WRITER  = 'WRITER';
    const TYPE_IMPRESS = 'CALC';
    const TYPE_CALC    = 'CALC';
    const TYPE_HTML    = 'HTML';

    /**
     * Behaviours
     */
    use \FreeSSO\Model\Behaviour\Broker;
}
