<?php
/**
 * Gestion des traduction avec techno ICU
 *
 * @author jeromeklam
 * @package I18n
 * @category Tools
 */
namespace FreeFW\Tools;

/**
 * Gestion des traduction avec techno ICU
 * @author jeromeklam
 */
class Icu
{

    /**
     * Parse des ressources
     *
     * @param mixed  $p_rb
     * @param string $p_prefix
     *
     * @return array
     */
    protected static function parseResourceBundle($p_rb, $p_prefix = '')
    {
        $p_prefix = rtrim($p_prefix, '.');
        $values   = array();
        if ($p_rb instanceof \ResourceBundle) {
            foreach ($p_rb as $k => $v) {
                if (is_object($v)) {
                    $temp   = self::parseResourceBundle($v, ($p_prefix == '' ? '' : $p_prefix . '.') .
                              print_r($k, true) . '.');
                    $values = array_merge($values, $temp);
                } else {
                    $values[$p_prefix . '.' . $k] = $v;
                }
            }
        }
        
        return $values;
    }

    /**
     * Retourne les traductions sous forme de tableau
     *
     * @param string $p_name
     * @param string $p_path
     * @param string $p_prefix
     *
     * @return array
     */
    public static function getAsArray($p_name, $p_path, $p_prefix = '')
    {
        $ressource = new \ResourceBundle($p_name, $p_path);
        $arr       = self::parseResourceBundle($ressource, $p_prefix);
        
        return $arr;
    }
}
