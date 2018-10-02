<?php
namespace FreeFW\Core;

use Psr\Http\Message\ResponseInterface;

/**
 * Base controller
 *
 * @author jeromeklam
 */
class Controller implements
    \Psr\Log\LoggerAwareInterface,
    \FreeFW\Interfaces\ConfigAwareTraitInterface,
    \Psr\Http\Message\ResponseFactoryInterface
{

    /**
     * comportements
     */
    use \Psr\Log\LoggerAwareTrait;
    use \FreeFW\Behaviour\EventManagerAwareTrait;
    use \FreeFW\Behaviour\ConfigAwareTrait;

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
