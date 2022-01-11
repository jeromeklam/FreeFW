<?php
namespace FreeFW\Tools\NumbersWords;

/**
 * Converti un nombre en chiffres en nombre en toutes lettres
 *
 * @author jeromeklam
 */
class Number
{

    /**
     * Genres
     * @var number
     */
    const GENDER_MASCULINE = 0;
    const GENDER_FEMININE  = 1;
    const GENDER_NEUTER    = 2;
    const GENDER_ABSTRACT  = 3;

    /**
     * Language par défaut
     * @var string
     */
    protected $p_locale = 'en_US';

    /**
     * Séparateur des décimales
     * @var string
     */
    protected $p_dec = '.';

    /**
     * Converti un nombre dans sa représentation en texte
     *
     * @param integer $p_num
     * @param string  $p_locale
     * @param array   $p_options
     *
     * @return string
     */
    public function toWords($p_num, $p_locale = '', $p_options = array())
    {
        if (empty($p_locale) && isset($this) && $this instanceof Number) {
            $p_locale = $this->locale;
        }
        if (empty($p_locale)) {
            $p_locale = 'en_US';
        }
        $classname = self::loadLocale($p_locale, '_toWords');
        $obj       = new $classname;
        if (!is_int($p_num)) {
            $p_num = $obj->normalizeNumber($p_num);
            $p_num = preg_replace('/(.*?)('.preg_quote($obj->decimalPoint).'.*?)?$/', '$1', $p_num);
        }
        if (empty($p_options)) {
            return trim($obj->toWords($p_num));
        }

        return trim($obj->toWords($p_num, $p_options));
    }

    /**
     * Converti un nombre dans sa réprésentation en toute lettre
     *
     * @param float  $p_num    A float/integer/string number representing currency value
     * @param string $p_locale Language name abbreviation. Optional. Defaults to en_US.
     * @param string $p_curr  'EUR', 'USD', 'PLN'
     * @param string $p_dec   '.', ','
     *
     * @return string
     */
    public function toCurrency($p_num, $p_locale, $p_curr, $p_dec)
    {
        $ret       = $p_num;
        $classname = self::loadLocale($p_locale, 'toCurrencyWords');
        $obj       = new $classname;
        if (is_null($p_dec)) {
            $p_dec = $obj->getDecimalpoint();
        }
        if (is_float($p_num)) {
            $p_num = round($p_num, 2);
        }
        $p_num = $obj->normalizeNumber($p_num, $p_dec);
        if (strpos($p_num, $p_dec) === false) {
            return trim($obj->toCurrencyWords($p_curr, $p_num));
        }
        $currency = explode($p_dec, $p_num, 2);
        $len      = strlen($currency[1]);
        if ($len == 1) {
            $currency[1] .= '0';
        } else {
            if ($len > 2) {
                $round_digit = substr($currency[1], 2, 1);
                $currency[1] = substr($currency[1], 0, 2);
                if ($round_digit >= 5) {
                    $int         = new \FreeFW\Tools\BigInteger(join($currency));
                    $int         = $int->add(new \FreeFW\Tools\BigInteger(1));
                    $int_str     = $int->toString();
                    $currency[0] = substr($int_str, 0, -2);
                    $currency[1] = substr($int_str, -2);
                    if ($currency[1] == '00') {
                        $currency[1] = false;
                    }
                }
            }
        }

        return trim($obj->toCurrencyWords($p_curr, $currency[0], $currency[1]));
    }

    /**
     * Retourne la classe à utiliser en fonction de la locale
     *
     * @param string $p_locale
     * @param string $p_requiredMethod
     *
     * @return string
     */
    public static function loadLocale($p_locale, $p_requiredMethod)
    {
        $clName    = \FreeFW\Tools\PBXString::toCamelCase(strtolower($p_locale), true);
        $classname = '\\FreeFW\\Tools\\NumbersWords\\Locale\\' . $clName;
        if (!class_exists($classname)) {
            throw new \FreeFW\Tools\NumbersWords\NumbersWordsException(
                'Unable to load locale class ' . $classname
            );
        }
        $methods = get_class_methods($classname);
        if (!in_array($p_requiredMethod, $methods)) {
            throw new \FreeFW\Tools\NumbersWords\NumbersWordsException(
                "Unable to find method '$p_requiredMethod' in class '$classname'"
            );
        }

        return $classname;
    }
}
