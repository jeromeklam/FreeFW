<?php
namespace FreeFW\Controller;

/**
 * Controller Rate
 *
 * @author jeromeklam
 */
class Rate extends \FreeFW\Core\ApiController
{

    /**
     * Get latest
     *
     * @param \Psr\Http\Message\ServerRequestInterface $p_request
     */
    public function getLatest(\Psr\Http\Message\ServerRequestInterface $p_request)
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

        $latest = \FreeFW\Model\Rate::findFirst([], ['rate_ts' => '-']);
        /**
         * @var \FreeFW\Model\Query $query
         */
        $query   = $model->getQuery();
        $filters = [
            'rate_ts' => $latest->getRateTs()
        ];
        $query
            ->addFromFilters($filters)
            ->addRelations($apiParams->getInclude())
        ;
        $data = new \FreeFW\Model\ResultSet();
        if ($query->execute()) {
            $data = $query->getResult();
        }
        $this->logger->debug('FreeFW.ApiController.getAll.end');
        return $this->createResponse(200, $data);
    }
}
