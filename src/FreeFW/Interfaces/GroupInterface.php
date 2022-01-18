<?php
namespace FreeFW\Interfaces;

/**
 * User interface
 *
 * @author jerome.klam
 */
interface GroupInterface
{

    /**
     * Return group id
     *
     * @return number
     */
    public function getGrpId();

    /**
     * Return ugroup name
     *
     * @return string
     */
    public function getGrpName();
}
