<?php
namespace FreeFW\Model;

use \FreeFW\Constants as FFCST;

/**
 * Model EmailLang
 *
 * @author jeromeklam
 */
class EmailLang extends \FreeFW\Model\Base\EmailLang
{

    /**
     * Behaviour
     */
    use \FreeFW\Model\Behaviour\Email;
    use \FreeFW\Model\Behaviour\Lang;
}
