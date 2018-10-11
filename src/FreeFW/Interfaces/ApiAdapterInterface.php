<?php
namespace FreeFW\Interfaces;

use \Psr\Http\Message\ServerRequestInterface;
use \Psr\Http\Message\ResponseInterface;

/**
 * Standard API Adapter interface
 *
 * @author jeromeklam
 */
interface ApiAdapterInterface
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

    /**
     * Decode the request
     *
     * @param ServerRequestInterface $p_request
     *
     * @return ServerRequestInterface
     */
    public function decodeRequest(ServerRequestInterface $p_request) : ServerRequestInterface;

    /**
     * Encode the response
     *
     * @param ResponseInterface $p_response
     *
     * @return ResponseInterface
     */
    public function encodeResponse(ResponseInterface $p_response) : ResponseInterface;
}
