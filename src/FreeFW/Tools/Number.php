<?php
/**
 * Outils sur les nombres
 *
 * @author jeromeklam
 * @package Number
 * @category Tools
 */
namespace FreeFW\Tools;

/**
 * Outils sur les nombres
 * @author jeromeklam
 */
class Number
{

    /**
     * Converti un nombre en toute lettre
     *
     * @param mixed  $p_num
     * @param string $p_locale    \FreeFW\Constants::LOCALE_*
     * @param string $p_currency  \FreeFW\Constants::CURRENCY_*
     * @param string $p_decimal   . ,
     *
     * @return string
     */
    public static function toWords($p_num, $p_locale, $p_currency, $p_decimal)
    {
        $nW = new \FreeFW\Tools\NumbersWords\Number();
        return $nW->toCurrency($p_num, $p_locale, $p_currency, $p_decimal);
    }
}
