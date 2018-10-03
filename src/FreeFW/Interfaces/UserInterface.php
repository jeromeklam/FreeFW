<?php
namespace FreeFW\Interfaces;

/**
 * User interface
 *
 * @author jerome.klam
 */
interface UserInterface
{

    /**
     * Return user id
     *
     * @return number
     */
    public function getUserId();

    /**
     * Return user login
     *
     * @return string
     */
    public function getUserLogin();
}
