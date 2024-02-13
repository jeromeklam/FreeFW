<?php
namespace FreeFW\Model;

use \FreeFW\Constants as FFCST;

/**
 * Country
 *
 * @author jeromeklam
 */
class Country extends \FreeFW\Model\Base\Country implements
    \FreeFW\Interfaces\ApiResponseInterface
{

    /**
     * Get id byg cog
     * 
     * @param Array $p_cogs
     * 
     * @return Array
     */
    public static function getIdsByCog(array $p_cogs)
    {
        $ids    = [];
        $result = \FreeFW\Model\Country::find(['cnty_cog' => [\FreeFW\Storage\Storage::COND_IN => $p_cogs]]);
        foreach ($result as $oneCountry) {
            $ids[] = $oneCountry->getCntyId();
        }
        return $ids;
    }
}
