<?php
namespace FreeFW\Tools;

/**
 *
 * @author jeromeklam
 *
 */
class PBXArray
{

    /**
     * Makes an array of parameters become a querystring like string.
     *
     * @param  array $array
     *
     * @return string
     */
    public static function stringify(array $array)
    {
        $result = array();
        foreach ($array as $key => $value) {
            $result[] = sprintf('%s=%s', $key, $value);
        }
        return implode('&', $result);
    }
}
