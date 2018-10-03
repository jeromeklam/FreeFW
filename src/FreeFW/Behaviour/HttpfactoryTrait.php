<?php
namespace FreeFW\Behaviour;

use Psr\Http\Message\ResponseInterface;

/**
 *
 * @author jeromeklam
 *
 */
trait HttpFactoryTrait
{

    /**
     *
     * {@inheritDoc}
     * @see \Psr\Http\Message\ResponseFactoryInterface::createResponse()
     */
    public function createResponse(int $code = 200, string $reasonPhrase = ''): ResponseInterface
    {
        return new \GuzzleHttp\Psr7\Response($code, [], $reasonPhrase);
    }
}
