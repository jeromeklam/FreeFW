<?php
namespace FreeFW\Controller;

/**
 * Model controller
 *
 * @author jeromeklam
 */
class Application extends \FreeFW\Core\ApiController
{

    /**
     * génération de la documentation complète à partir des routes et des modèles en mémoire
     *
     * @param \Psr\Http\Message\ServerRequestInterface $p_request
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function documentAll(\Psr\Http\Message\ServerRequestInterface $p_request)
    {
        $doc = '';
        $this->logger->debug('FreeFW.Controller.Application.documentAll.start');
        $appService = \FreeFW\DI\DI::get('FreeFW::Service::Application');
        try {
            $doc = $appService->generateDocumentation();
            echo json_encode($doc);die;
        } catch (\Exception $ex) {
            return $this->createResponse(409);
        }
        $this->logger->debug('FreeFW.Controller.Application.documentAll.end');
        return $this->createResponse(201, $doc);
    }
}
