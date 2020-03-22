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

    /**
     * 
     * @param array $array
     * 
     * @return array
     */
    public static function reduceKeys(array $array, $add = '')
    {
        $ret = [];
        foreach ($array as $key => $value) {
            $newKey = $key;
            if ($add != '') {
                $newKey = $add . '.' . $newKey;
            }
            if (is_array($value)) {
                $ret2 = self::reduceKeys($value, $newKey);
                $ret = array_merge($ret, $ret2);
            } else {
                $ret[$newKey] = $value;
            }
        }
        return $ret;
    }
}
