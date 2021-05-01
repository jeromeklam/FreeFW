<?php
namespace FreeFW\Model;

use \FreeFW\Constants as FFCST;

/**
 * Model EditionLang
 *
 * @author jeromeklam
 */
class EditionLang extends \FreeFW\Model\Base\EditionLang
{

    /**
     * Behaviour
     */
    use \FreeFW\Model\Behaviour\Edition;
    use \FreeFW\Model\Behaviour\Lang;
}
