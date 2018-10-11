<?php
namespace FreeFW\Interfaces;

/**
 * AuthAdapterInterface
 *
 * @author jeromeklam
 */
interface AuthAdapterInterface
{

    /**
     * Set secured
     *
     * @param bool $p_secured
     *
     * @return self
     */
    public function setSecured(bool $p_secured = true);

    /**
     * Return secured
     *
     * @return bool
     */
    public function isSecured() : bool;
}
