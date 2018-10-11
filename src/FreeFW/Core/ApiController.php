<?php
namespace FreeFW\Core;

/**
 * Base controller
 *
 * @author jeromeklam
 */
class ApiController extends \FreeFW\Core\Controller
{

    /**
     * Add new single element
     *
     * @param \Psr\Http\Message\ServerRequestInterface $p_request
     */
    public function createOne(\Psr\Http\Message\ServerRequestInterface $p_request)
    {
        $this->logger->debug('FreeFW.ApiController.addOne.start');
        $apiParams = $p_request->getAttribute('api_params', false);
        //
        if ($apiParams->hasData()) {
            /**
             * @var \FreeFW\Core\StorageModel $data
             */
            $data = $apiParams->getData();
            if ($data->isValid()) {
                $data->create();
            }
            $this->logger->debug('FreeFW.ApiController.addOne.end');
            return $this->createResponse(200, $data);
        } else {
            return $this->createResponse(409);
        }
    }
}
