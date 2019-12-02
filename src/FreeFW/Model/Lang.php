<?php
namespace FreeFW\Model;

use \FreeFW\Constants as FFCST;

/**
 * Lang
 *
 * @author jeromeklam
 */
class Lang extends \FreeFW\Model\Base\Lang implements
    \FreeFW\Interfaces\ApiResponseInterface
{

    /**
     *
     * {@inheritDoc}
     * @see \FreeFW\Core\Model::init()
     */
    public function init()
    {
        $this->lang_id = 0;
        return $this;
    }
}
