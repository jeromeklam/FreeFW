<?php
namespace FreeFW\Middleware\Auth;

use \Psr\Http\Message\ServerRequestInterface;
use \FreeFW\Middleware\Auth\AuthorizationHeader;

/**
 * Bearer Auth
 *
 * @author jeromeklam
 */
class BearerAuth implements
    \Psr\Log\LoggerAwareInterface,
    \FreeFW\Interfaces\ConfigAwareTraitInterface,
    \FreeFW\Interfaces\AuthAdapterInterface
{

    /**
     * Behaviour
     */
    use \Psr\Log\LoggerAwareTrait;
    use \FreeFW\Behaviour\EventManagerAwareTrait;
    use \FreeFW\Behaviour\ConfigAwareTrait;

    /**
     *
     * {@inheritDoc}
     * @see \FreeFW\Interfaces\AuthAdapterInterface::getAuthorizationHeader()
     */
    public function getAuthorizationHeader(ServerRequestInterface $p_request, AuthorizationHeader $p_header)
    {
        return '';
    }

    /**
     *
     * {@inheritDoc}
     * @see \FreeFW\Interfaces\AuthAdapterInterface::verifyAuthorizationHeader()
     */
    public function verifyAuthorizationHeader(ServerRequestInterface $p_request, AuthorizationHeader $p_header)
    {
        $user = false;
        return $user;
    }
}
