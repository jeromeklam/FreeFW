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

    public function getLangIsoNumberWords()
    {
        $iso = str_replace('-', '_', strtoupper($this->getLangIso()));
        return $iso;
    }
}
