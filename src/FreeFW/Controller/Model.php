<?php
namespace FreeFW\Controller;

/**
 * Model controller
 *
 * @author jeromeklam
 */
class Model extends \FreeFW\Core\ApiController
{

    /**
     * @desc Génère une doc simplifiée au format markdown <br />
     *&emsp;- Modéle utilisé : POFW/Model/Model.php<br/>
     *
     * @param \Psr\Http\Message\ServerRequestInterface $p_request
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function reactjsModel(\Psr\Http\Message\ServerRequestInterface $p_request)
    {
        $this->logger->debug('FreeFW.Controller.Model.reactjsModel.start');
        $apiParams = $p_request->getAttribute('api_params', false);
        if ($apiParams->hasData()) {
            /**
             * @var \FreeFW\Model\Model $data
             */
            $model = $apiParams->getData();
            // Generate class name
            $generator = new \FreeFW\ReactJS\Generator($model);
            $generator->generateFeature();
            $this->logger->debug('FreeFW.Controller.Model.reactjsModel.end');
            return $this->createResponse(201, $model);
        }
        $this->logger->debug('FreeFW.Controller.Model.reactjsModel.end');
        return $this->createResponse(409, $doc);
    }

    /**
     *
     * @param \Psr\Http\Message\ServerRequestInterface $p_request
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function documentModel(\Psr\Http\Message\ServerRequestInterface $p_request)
    {
        $doc = '';
        $this->logger->debug('FreeFW.Controller.Model.documentModel.start');
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
            try {
                $doc = $modelService->generateDocumentation($data);
            } catch (\Exception $ex) {
                return $this->createResponse(409, $data);
            }
            return $this->createResponse(201, $doc);
        }
        $this->logger->debug('FreeFW.Controller.Model.documentModel.end');
        return $this->createResponse(409, $doc);
    }

    /**
     *
     * @param \Psr\Http\Message\ServerRequestInterface $p_request
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
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
