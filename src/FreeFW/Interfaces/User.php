<?php
namespace FreeFW\Interfaces;

/**
 *
 * @author jerome.klam
 *
 */
interface User
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
