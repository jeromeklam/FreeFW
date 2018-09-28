<?php
namespace FreeFW\Tools;

/**
 *
 * @author jeromeklam
 *
 */
class ISO8601
{

    /**
     * Date as ISO8601
     *
     * @return string
     */
    public static function getCurrentDateTime()
    {
        return date('c');
    }
}
