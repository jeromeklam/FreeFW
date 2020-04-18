<?php
namespace FreeFW\Tools\NumbersWords\Locale;

use \FreeFW\Tools\NumbersWords\NumbersWordsException;

/**
 * Classe abstraite de définition d'une locale
 * @author jeromeklam
 */
abstract class AbstractNumbersWordsLocale
{

    /**
     * Locale
     * @var string
     */
    protected $locale = null;

    /**
     * Séparateur de décimal par défaut
     * @var string
     */
    protected $decimal_point = null;

    /**
     * Affectation de la monnaie
     * @var string
     */
    protected $default_currency = null;

    /**
     * Tableau des monnaies
     * @var array
     */
    protected $currency_names = array();

    /**
     * Langue
     * @var string
     */
    protected $lang = null;

    /**
     * Langue native
     * @var string
     */
    protected $native_lang = null;

    /**
     * Commment écrit-on le signe moins ?
     * @var string
     */
    protected $minus = null;

    /**
     * Et
     * @var string
     */
    protected $and = null;

    /**
     * Tiret
     * @var string
     */
    protected $dash = null;

    /**
     * Séparateur de mot
     * @var string
     */
    protected $separator = null;

    /**
     * Traductions des chiffres
     * @var array
     */
    protected $digits = array();

    /**
     * Traduction des "exposants"
     * @var array
     */
    protected $exponent = array();

    /**
     * Nombres spéciaux
     * @var array
     */
    protected $misc_numbers = array();

    /**
     * Pluriel
     * @var string
     */
    protected $plural = null;

    /**
     * Affectation de la locale
     *
     * @param string $p_val
     *
     * @return static
     */
    protected function setLocale($p_val)
    {
        $this->locale = $p_val;

        return $this;
    }

    /**
     * Retourne la locale
     *
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * Affectation du séparateur de décimal
     *
     * @param string $p_val
     *
     * @return static
     */
    protected function setDecimalPoint($p_val)
    {
        $this->decimal_point = $p_val;

        return $this;
    }

    /**
     * Retourne le séparateur de décimales
     *
     * @return string
     */
    public function getDecimalPoint()
    {
        return $this->decimal_point;
    }

    /**
     * Affectation du nom de la langue
     *
     * @param string $p_val
     *
     * @return static
     */
    protected function setLang($p_val)
    {
        $this->lang = $p_val;

        return $this;
    }

    /**
     * Retourne la langue
     *
     * @return string
     */
    public function getLang()
    {
        return $this->lang;
    }

    /**
     * Affectation du nom de la langue
     *
     * @param string $p_val
     *
     * @return static
     */
    protected function setNativeLang($p_val)
    {
        $this->native_lang = $p_val;

        return $this;
    }

    /**
     * Retourne la langue
     *
     * @return string
     */
    public function getNativeLang()
    {
        return $this->native_lang;
    }

    /**
     * Affectation du mmoins
     *
     * @param string $p_val
     *
     * @return static
     */
    protected function setMinus($p_val)
    {
        $this->minus = $p_val;

        return $this;
    }

    /**
     * Retourne le signe moins
     *
     * @return string
     */
    public function getMinus()
    {
        return $this->minus;
    }

    /**
     * Affectation du et
     *
     * @param string $p_val
     *
     * @return static
     */
    protected function setAnd($p_val)
    {
        $this->and = $p_val;

        return $this;
    }

    /**
     * Retourne la partie Et
     *
     * @return string
     */
    public function getAnd()
    {
        return $this->and;
    }

    /**
     * Affectation du pluriel
     *
     * @param string $p_val
     *
     * @return static
     */
    protected function setPlural($p_val)
    {
        $this->plural = $p_val;

        return $this;
    }

    /**
     * Retourne le pluriel
     *
     * @return string
     */
    public function getPlural()
    {
        return $this->plural;
    }

    /**
     * Affectation du tiret
     *
     * @param string $p_val
     *
     * @return static
     */
    protected function setDash($p_val)
    {
        $this->dash = $p_val;

        return $this;
    }

    /**
     * Récupération du tiret
     *
     * @return string
     */
    public function getDash()
    {
        return $this->dash;
    }

    /**
     * Affectation du séparateur de millier
     *
     * @param string $p_val
     *
     * @return /static
     */
    protected function setSeparator($p_val)
    {
        $this->separator = $p_val;

        return $this;
    }

    /**
     * Retourne le séparateur de miller
     *
     * @return string
     */
    public function getSeparator()
    {
        return $this->separator;
    }

    /**
     * Affectation de la monnaie
     *
     * @param string $p_val
     *
     * @return static
     */
    protected function setDefaultCurrency($p_val)
    {
        $this->default_currency = $p_val;

        return $this;
    }

    /**
     * Retourne la monnaie
     *
     * @return string
     */
    public function getDefaultCurrency()
    {
        return $this->default_currency;
    }

    /**
     * Ajout d'une monnaie
     *
     * @param string $p_code
     * @param string $p_monnaie
     * @param string $p_cent
     *
     * @retuen static
     */
    protected function addCurrency($p_code, $p_monnaie, $p_cent)
    {
        $this->currency_names[$p_code] = array(
            array($p_monnaie),
            array($p_cent)
        );

        return $this;
    }

    /**
     * Retourne la traduction pour une monnaie
     *
     * @param string $p_currency
     *
     * @return array
     */
    public function getCurrencyName($p_currency)
    {
        if (array_key_exists($p_currency, $this->currency_names)) {
            return $this->currency_names[$p_currency];
        }

        throw NumberWordsException(sprintf('Can\'t find %s currency !', $p_currency));
    }

    /**
     * Retourne toutes les monnaies
     *
     * @return array
     */
    public function getCurrencies()
    {
        return $this->currency_names;
    }

    /**
     * Affectarion des traductions des chiffres
     *
     * @param array $p_digits
     *
     * @return static
     */
    protected function setDigits($p_digits)
    {
        $this->digits = $p_digits;

        return $this;
    }

    /**
     * Retourne les traductions des chiffres
     *
     * @return array
     */
    public function getDigits()
    {
        return $this->digits;
    }

    /**
     * Retourne la traduction d'un chiffre
     *
     * @param mixed $p_digit
     *
     * @throws NumbersWordsException
     *
     * return string
     */
    public function getDigit($p_digit)
    {
        if (array_key_exists($p_digit, $this->digits)) {
            return $this->digits[$p_digit];
        }

        throw new NumbersWordsException(sprintf('Can\'t find digit %s !', $p_digit));
    }

    /**
     * Ajout d'un exposant
     *
     * @param number $p_exp
     * @param string $p_trad
     *
     * @return static
     */
    protected function addExponent($p_exp, $p_trad)
    {
        $this->exponent[$p_exp] = array($p_trad);

        return $this;
    }

    /**
     * Affectation des traductions des exposants
     *
     * @param array $p_arr
     *
     * @return static
     */
    protected function setExponents($p_arr)
    {
        $this->exponent = $p_arr;

        return $this;
    }

    /**
     * Retourne tous les "exposants"
     *
     * @return array
     */
    public function getExponents()
    {
        return $this->exponent;
    }

    /**
     * Retourne un exposant
     *
     * @param string $p_exp
     *
     * @throws NumbersWordsException
     *
     * @return string
     */
    public function getExponent($p_exp)
    {
        if (array_key_exists($p_exp, $this->exponent)) {
            return $this->exponent[$p_exp];
        }

        throw new NumbersWordsException(sprintf('Can\'t find %s exponent !', $p_exp));
    }

    /**
     * Exposant existe ?
     *
     * @param string $p_exp
     *
     * @return boolean
     */
    public function hasExponent($p_exp)
    {
        if (array_key_exists($p_exp, $this->exponent)) {
            return true;
        }

        return false;
    }

    /**
     * Supprime tout ce qui est bizarre
     *
     * @param string $p_num
     * @param string $p_dec
     *
     * @return string
     */
    public function normalizeNumber($p_num, $p_dec = null)
    {
        if (is_null($p_dec)) {
            $p_dec = $this->decimalPoint;
        }
        $normalized = preg_replace('/[^-'.preg_quote($p_dec).'0-9]/', '', $p_num);
        // Verify numbers are equal....
        $mn1 = floatval($p_num);
        $mn2 = floatval($normalized);
        if ($mn1 != $mn2) {
            throw new NumbersWordsException(sprintf('number conversion error : %s <> %s', $p_num, $mn2));
        }

        return $normalized;
    }

    /**
     * Affectation des numéros spéciaux
     *
     * @param array $p_numbers
     *
     * @return static
     */
    protected function setMiscNumbers($p_numbers)
    {
        $this->misc_numbers = $p_numbers;

        return $this;
    }

    /**
     * Retourne la traduction d'un numéro spécial
     *
     * @param mixed $p_num
     *
     * @return string
     */
    public function getMiscNumber($p_num)
    {
        if (array_key_exists($p_num, $this->misc_numbers)) {
            return $this->misc_numbers[$p_num];
        }

        throw new NumbersWordsException(sprintf('Can\'t find %s misc number !', $p_num));
    }
}
