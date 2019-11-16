<?php
namespace FreeFW\Interfaces;

/**
 * AuthAdapterInterface
 *
 * @author jeromeklam
 */
interface AuthNegociatorInterface
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

    /**
     * Force identity generation
     *
     * @param bool $p_identity
     */
    public function setIdentityGeneration(bool $p_identity = true);

    /**
     * Get identity generation
     *
     * @return bool
     */
    public function getIndentityGeneration() : bool;
}
