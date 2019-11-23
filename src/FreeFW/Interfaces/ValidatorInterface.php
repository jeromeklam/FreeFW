<?php
namespace FreeFW\Interfaces;

/**
 * Validator interface
 *
 * @author jeromeklam
 */
interface ValidatorInterface
{

    /**
     * Check if valid
     *
     * @return boolean
     */
    public function isValid() : bool;
}
