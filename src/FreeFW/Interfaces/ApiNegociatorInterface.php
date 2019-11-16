<?php
namespace FreeFW\Interfaces;

use \Psr\Http\Message\ServerRequestInterface;
use \Psr\Http\Message\ResponseInterface;

/**
 * Standard API Adapter interface
 *
 * @author jeromeklam
 */
interface ApiNegociatorInterface
{

    /**
     * Can overrie ?
     *
     * @return boolean
     */
    public function canOverride() : bool;

    /**
     * Check method and ContentType
     *
     * @param ServerRequestInterface $p_request
     *
     * @return boolean
     */
    public function checkRequest(ServerRequestInterface $p_request) : bool;

    /**
     * Return a standard 415 response
     *
     * @return ResponseInterface
     */
    public function createUnsupportedRequestResponse() : ResponseInterface;

    /**
     * Return a standard 200 error response
     *
     * @param \Exception $p_ex
     *
     * @return ResponseInterface
     */
    public function createErrorResponse(\Exception $p_ex) : ResponseInterface;
}
