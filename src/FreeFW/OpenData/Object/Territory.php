<?php

namespace FreeFW\OpenData\Object;

/**
 * Model Territory
 *
 * @author jeromeklam
 */
class Territory implements \FreeFW\OpenData\ObjectInterface
{

    public $cog = null;
    public $actual = null;
    public $capay = null;
    public $crpay = null;
    public $ani = null;
    public $libcog = null;
    public $libenr = null;
    public $ancnom = null;
    public $codeiso2 = null;
    public $codeiso3 = null;
    public $codenum3 = null;

    /**
     * BasePath
     * 
     * @return String
     */
    public static function getBasePath()
    {
        return 'https://data.gouv.nc/api/explore/v2.1/catalog/datasets/';
    }

    /**
     * DataSet
     * 
     * @return String
     */
    public static function getDataSet()
    {
        return 'liste-des-pays-et-territoires-etrangers';
    }
}