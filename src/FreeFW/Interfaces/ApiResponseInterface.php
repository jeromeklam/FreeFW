<?php
namespace FreeFW\Interfaces;

/**
 * Standard Api Response Interface
 *
 * @author jeromeklam
 */
interface ApiResponseInterface
{

    /**
     * Return id
     *
     * @return string
     */
    public function getApiId() : string;

    /**
     * Set Id
     * 
     * @param mixed $p_id
     */
    public function setApiId($p_id);

    /**
     * Return type
     *
     * @return string
     */
    public function getApiType() : string;

    /**
     * Get attributes as Array
     *
     * @return array
     */
    public function getApiAttributes() : array;

    /**
     * Get relations as Array
     * 
     * @return array
     */
    public function getApiRelationShips() : array;

    /**
     * Has errors
     *
     * @return bool
     */
    public function hasErrors() : bool;

    /**
     * Get errors
     *
     * @return array[\FreeFW\Core\Error]
     */
    public function getErrors() : array;

    /**
     * Is just one element
     * 
     * @return bool
     */
    public function isSingleElement() : bool;

    /**
     * Is an array of elements
     * 
     * @return bool
     */
    public function isArrayElement() : bool;
}
