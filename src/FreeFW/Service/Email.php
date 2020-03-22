<?php
namespace FreeFW\Service;

/**
 * Model
 *
 * @author jeromeklam
 */
class Email extends \FreeFW\Core\Service
{

    /**
     * Find email by code and lang
     *
     * @param string $p_code
     * @param number $p_lang_id
     *
     * @return \FreeFW\Core\StorageModel
     */
    public function getEmail($p_code, $p_lang_id = null)
    {
        $email = false;
        $email = \FreeFW\Model\Email::findFirst(
            [
                'email_code' => $p_code,
                'lang_id'    => $p_lang_id,
            ]
        );
        // @todo : en by default...
        return $email;
    }
}
