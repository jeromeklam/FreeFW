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
}
