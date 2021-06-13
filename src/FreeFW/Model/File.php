<?php
namespace FreeFW\Model;

use \FreeFW\Constants as FFCST;

/**
 * Model File
 *
 * @author jeromeklam
 */
class File extends \FreeFW\Model\Base\File
{

    /**
     * Behaviour
     */
    use \FreeSSO\Model\Behaviour\User;
    use \FreeSSO\Model\Behaviour\Broker;
    use \FreeSSO\Model\Behaviour\Group;
}
