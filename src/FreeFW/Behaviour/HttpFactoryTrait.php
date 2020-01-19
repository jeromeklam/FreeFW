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

    /**
     *
     * {@inheritDoc}
     * @see \Psr\Http\Message\ResponseFactoryInterface::createResponse()
     */
    public function createMimeTypeResponse($p_filename,$p_content = null): ResponseInterface
    {
        $type    = \GuzzleHttp\Psr7\mimetype_from_filename($p_filename);
        if (!$type) {
            $type = 'application/octet-stream';
        }
        $content = $p_content;
        $headers = [
            'Content-Description' => 'File Transfer',
            'Content-Type' => $type,
            'Content-Disposition' => 'attachment; filename="' . $p_filename . '"',
            'Content-Transfer-Encoding' => 'binary',
            'Expires' => '0',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Pragma' => 'public',
        ];
        return new \FreeFW\Psr7\Response(200, $headers, $content);
    }
}
