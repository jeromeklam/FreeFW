<?php
namespace FreeFW\Tools;

/**
 *
 * @author jeromeklam
 *
 */
class Token {

    /**
     * Get basic token
     *
     * @return string
     */
    public static function getBasic ($p_prefix = 'FreeFW')
    {
        $token = uniqid($p_prefix, true);
        return sha1($token);
    }
}
