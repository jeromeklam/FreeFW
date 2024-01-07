<?php
namespace FreeFW\OpenData;

/**
 * API Interface
 *
 * @author jeromeklam
 */
interface ObjectInterface
{

    /**
     * BasePath
     * 
     * @return String
     */
    public static function getBasePath();

    /**
     * DataSet
     * 
     * @return String
     */
    public static function getDataSet();
}