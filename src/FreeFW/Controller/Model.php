<?php
namespace FreeFW\Controller;

/**
 * Model controller
 *
 * @author jeromeklam
 */
class Model extends \FreeFW\Core\ApiController
{

    public function createModel(\Psr\Http\Message\ServerRequestInterface $p_request)
    {
        $this->logger->debug('FreeFW.Controller.Model.createModel.start');
        $apiParams = $p_request->getAttribute('api_params', false);
        $data      = null;
        //
        if ($apiParams->hasData()) {
            /**
             * @var \FreeFW\Core\StorageModel $data
             */
            $data = $apiParams->getData();
            if (!$data->isValid()) {
                return $this->createResponse(409, $data);
            }
            $modelService = \FreeFW\DI\DI::get('FreeFW::Service::Model');
            if (!$modelService->generateModel($data)) {
                return $this->createResponse(409, $data);
            }
            if ($data->hasErrors()) {
                return $this->createResponse(409, $data);
            }
            $this->logger->debug('FreeFW.Controller.Model.createModel.end');
            return $this->createResponse(201, $data);
        }
        $this->logger->debug('FreeFW.Controller.Model.createModel.end');
        return $this->createResponse(409, 'No data !');
    }
}
