<?php
namespace FreeFW\Interfaces;

use \Psr\Http\Message\ServerRequestInterface;

/**
 * AuthAdapterInterface
 *
 * @author jeromeklam
 */
interface AuthAdapterInterface
{

    /**
     * Get Authorization header
     * 
     * @param \Psr\Http\Message\ServerRequestInterface $p_request
     *
     * @return string
     */
    public function getAuthorizationHeader(ServerRequestInterface $p_request);

    /**
     * Verify Auth header and log user in
     * 
     * @param \Psr\Http\Message\ServerRequestInterface $p_request
     * 
     * @return boolean
     */
    public function verifyAuthorizationHeader(ServerRequestInterface $p_request);
}
