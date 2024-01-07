<?php

namespace FreeFW\OpenData\Object;

/**
 * Model Country
 *
 * @author jeromeklam
 */
class Country implements \FreeFW\OpenData\ObjectInterface
{
    public $iso3 = null;
    public $name_en = null;
    public $name_fr = null;
    public $name_native = null;
    public $iso2 = null;
    public $borders = null;
    public $flag = null;
    public $latlng = null;
    public $wikidata = null;
    public $bologne = null;
    public $embassy = null;
    public $arab_world = null;
    public $central_europe_and_the_baltics = null;
    public $east_asia_pacific = null;
    public $euro_area = null;
    public $europe_central_asia = null;
    public $european_union = null;
    public $high_income = null;
    public $latin_america_caribbean = null;
    public $low_income = null;
    public $lower_middle_income = null;
    public $middle_east_north_africa = null;
    public $north_america = null;
    public $oecd_members = null;
    public $sub_saharan_africa = null;
    public $upper_middle_income = null;
    public $world = null;
    public $link = null;
    public $website = null;
    public $south_america = null;
    public $central_america_caraibes = null;
    public $cocac = null;
    public $continental_europe = null;
    public $asia_oceania = null;
    public $cf_mobility = null;
    public $idpaysage = null;
    public $curiexplore = null;
    public $idh_group = null;
    public $idh_group_countries = null;
    public $ue27 = null;
    public $g7 = null;
    public $g20 = null;
    public $geometry = null;

    /**
     * BasePath
     * 
     * @return String
     */
    public static function getBasePath()
    {
        return 'https://data.enseignementsup-recherche.gouv.fr/api/explore/v2.1/catalog/datasets/';
    }

    /**
     * DataSet
     * 
     * @return String
     */
    public static function getDataSet()
    {
        return 'curiexplore-pays';
    }
}
