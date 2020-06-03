<?php
namespace FreeFW\Model;

use \FreeFW\Constants as FFCST;

/**
 * Email
 *
 * @author jeromeklam
 */
class Email extends \FreeFW\Model\Base\Email implements
    \FreeFW\Interfaces\ApiResponseInterface
{

    /**
     * Behaviours
     */
    use \FreeFW\Model\Behaviour\Lang;

    /**
     * Merge datas in fields
     *
     * @param array $p_datas
     */
    public function mergeDatas($p_datas)
    {
        $this->email_subject = \FreeFW\Tools\PBXString::parse($this->email_subject, $p_datas);
        $this->email_body    = \FreeFW\Tools\PBXString::parse($this->email_body, $p_datas);
    }
}
