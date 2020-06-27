<?php
namespace FreeFW\Model;

use \FreeFW\Constants as FFCST;

/**
 * Model Alert
 *
 * @author jeromeklam
 */
class Alert extends \FreeFW\Model\Base\Alert
{

    /**
     * Constants
     * @var string
     */
    const PRIORITY_NONE        = 'NONE';
    const PRIORITY_TODO        = 'TODO';
    const PRIORITY_IMPORTANT   = 'IMPORTANT';
    const PRIORITY_CRITICAL    = 'CRITICAL';
    const PRIORITY_INFORMATION = 'INFORMATION';

    /**
     * Set todo
     *
     * @return \FreeFW\Model\Alert
     */
    public function setTodoAlert()
    {
        $this->setAlertPriority(self::PRIORITY_TODO);
        return $this;
    }
}
