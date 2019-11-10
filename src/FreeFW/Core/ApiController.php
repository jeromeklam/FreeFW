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
     * Get all
     *
     * @param \Psr\Http\Message\ServerRequestInterface $p_request
     */
    public function getAll(\Psr\Http\Message\ServerRequestInterface $p_request)
    {
        $this->logger->debug('FreeFW.ApiController.getAll.start');
        /**
         * @var \FreeFW\Http\ApiParams $apiParams
         */
        $apiParams = $p_request->getAttribute('api_params', false);
        if (!isset($p_request->default_model)) {
            throw new \FreeFW\Core\FreeFWStorageException(
                sprintf('No default model for route !')
            );
        }
        $default = $p_request->default_model;
        $model   = \FreeFW\DI\DI::get($default);
        /**
         * @var \FreeFW\Model\Query $query
         */
        $query = $model->getQuery();
        $query
            ->addConditions($apiParams->getFilters())
            ->setLimit($apiParams->getStart(), $apiParams->getlength())
        ;
        $data = null;
        if ($query->execute()) {
            $data = $query->getResult();
        }
        $this->logger->debug('FreeFW.ApiController.getAll.end');
        return $this->createResponse(200, $data);
    }

    /**
     * Add new single element
     *
     * @param \Psr\Http\Message\ServerRequestInterface $p_request
     */
    public function createOne(\Psr\Http\Message\ServerRequestInterface $p_request)
    {
        $this->logger->debug('FreeFW.ApiController.createOne.start');
        $apiParams = $p_request->getAttribute('api_params', false);
        //
        if ($apiParams->hasData()) {
            /**
             * @var \FreeFW\Core\StorageModel $data
             */
            $data = $apiParams->getData();
            if (!$data->isValid()) {
                $this->logger->debug('FreeFW.ApiController.createOne.end');
                return $this->createResponse(409, $data);
            }
            $data->create();
            $this->logger->debug('FreeFW.ApiController.createOne.end');
            return $this->createResponse(201, $data);
        } else {
            return $this->createResponse(409);
        }
    }

    /**
     * Update single element
     *
     * @param \Psr\Http\Message\ServerRequestInterface $p_request
     */
    public function updateOne(\Psr\Http\Message\ServerRequestInterface $p_request)
    {
        $this->logger->debug('FreeFW.ApiController.updateOne.start');
        $apiParams = $p_request->getAttribute('api_params', false);
        //
        if ($apiParams->hasData()) {
            /**
             * @var \FreeFW\Core\StorageModel $data
             */
            $data = $apiParams->getData();
            if ($data->isValid()) {
                $data->update();
            }
            $this->logger->debug('FreeFW.ApiController.updateOne.end');
            return $this->createResponse(200, $data);
        } else {
            return $this->createResponse(409);
        }
    }

    /**
     * Remove single element
     *
     * @param \Psr\Http\Message\ServerRequestInterface $p_request
     */
    public function removeOne(\Psr\Http\Message\ServerRequestInterface $p_request)
    {
        $this->logger->debug('FreeFW.ApiController.removeOne.start');
        $apiParams = $p_request->getAttribute('api_params', false);
        //
        if ($apiParams->hasData()) {
            /**
             * @var \FreeFW\Core\StorageModel $data
             */
            $data = $apiParams->getData();
            $data->remove();
            $this->logger->debug('FreeFW.ApiController.removeOne.end');
            return $this->createResponse(204);
        } else {
            return $this->createResponse(409);
        }
    }
}
