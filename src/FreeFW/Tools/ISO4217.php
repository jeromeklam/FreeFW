<?php
namespace FreeFW\Tools;

/**
 *
 * @author jeromeklam
 *
 */
class ISO4217
{

    /**
     * Liste des codes connus
     *
     * @return array
     */
    protected static function getAllCodes()
    {
        return [
            'EUR' => ['symbol' => '€', 'code' => 978, 'label' => 'euro'],
            'CAD' => ['symbol' => '$', 'code' => 124, 'label' => 'dollar.canadien']
        ];
    }

    /**
     * get ISO4217 code as numeric
     *
     * @param string $p_currency
     *
     * @return number
     */
    public static function getAsNumeric($p_currency)
    {
        $codes = self::getAllCodes();
        if (array_key_exists(strtoupper($p_currency), $codes)) {
            return $codes[strtoupper($p_currency)]['code'];
        }
        return false;
    }
}
