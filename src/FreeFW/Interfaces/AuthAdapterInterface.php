<?php
namespace FreeFW\Interfaces;

use \Psr\Http\Message\ServerRequestInterface;
use \FreeFW\Middleware\Auth\AuthorizationHeader;

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
     * @param \Psr\Http\Message\ServerRequestInterface    $p_request
     * @param \FreeFW\Middleware\Auth\AuthorizationHeader $p_header
     *
     * @return string
     */
    public function getAuthorizationHeader(ServerRequestInterface $p_request, AuthorizationHeader $p_header);

    /**
     * Verify Auth header and log user in
     *
     * @param \Psr\Http\Message\ServerRequestInterface    $p_request
     * @param \FreeFW\Middleware\Auth\AuthorizationHeader $p_header
     *
     * @return boolean
     */
    public function verifyAuthorizationHeader(ServerRequestInterface $p_request, AuthorizationHeader $p_header);
}
