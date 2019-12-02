<?php
namespace FreeFW\Model;

use \FreeFW\Constants as FFCST;

/**
 * Country
 *
 * @author jeromeklam
 */
class Country extends \FreeFW\Model\Base\Country implements
    \FreeFW\Interfaces\ApiResponseInterface
{

    /**
     *
     * {@inheritDoc}
     * @see \FreeFW\Core\Model::init()
     */
    public function init()
    {
        $this->cnty_id = 0;
        return $this;
    }
}
