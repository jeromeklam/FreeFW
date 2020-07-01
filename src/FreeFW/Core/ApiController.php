<?php
namespace FreeFW\Core;

use \FreeFW\Constants as FFCST;

/**
 * Base controller
 *
 * @author jeromeklam
 */
class ApiController extends \FreeFW\Core\Controller
{

    /**
     * get Model by Id
     *
     * @param \FreeFW\Http\ApiParams $p_params
     * @param \FreeFW\Core\Model     $p_model
     * @param string                 $p_id
     *
     * @return NULL|\FreeFW\Model\ResultSet
     */
    protected function getModelById($p_params, $p_model, $p_id)
    {
        $filters  = new \FreeFW\Model\Conditions();
        $pk_field = $p_model->getPkField();
        $aField   = new \FreeFW\Model\ConditionMember();
        $aValue   = new \FreeFW\Model\ConditionValue();
        $aValue->setValue($p_id);
        $aField->setValue($pk_field);
        $aCondition = new \FreeFW\Model\SimpleCondition();
        $aCondition->setLeftMember($aField);
        $aCondition->setOperator(\FreeFW\Storage\Storage::COND_EQUAL);
        $aCondition->setRightMember($aValue);
        $filters->add($aCondition);
        /**
         * @var \FreeFW\Model\Query $query
         */
        $query = $p_model->getQuery();
        $query
            ->addConditions($filters)
            ->addRelations($p_params->getInclude())
            ->setLimit(0, 1)
        ;
        $data = null;
        if ($query->execute()) {
            $data = $query->getResult();
        }
        return $data;
    }

    /**
     * AutoComplete
     *
     * @param \Psr\Http\Message\ServerRequestInterface $p_request
     * @param string $p_search
     *
     * @throws \FreeFW\Core\FreeFWStorageException
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function autocomplete(\Psr\Http\Message\ServerRequestInterface $p_request, $p_search = '')
    {
        $this->logger->debug('FreeFW.ApiController.autocomplete.start');
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
        $fields  = $model->getAutocompleteField();
        $filters = [];
        if (is_array($fields)) {
            foreach ($fields as $oneField) {
                $filters[$oneField] = [\FreeFW\Storage\Storage::COND_LIKE => $p_search];
            }
        } else {
            $filters[$fields] = [\FreeFW\Storage\Storage::COND_LIKE => $p_search];
        }
        /**
         *
         * @var \FreeFW\Model\Query $query
         */
        $query = $model->getQuery();
        $query
            ->addFromFilters($filters)
            ->setOperator(\FreeFW\Storage\Storage::COND_OR)
            ->addRelations($apiParams->getInclude())
            ->setLimit(0, 30)
            ->setSort($apiParams->getSort())
        ;
        $data = new \FreeFW\Model\ResultSet();
        if ($query->execute()) {
            $data = $query->getResult();
        }
        if (count($data) > 0) {
            $this->logger->debug('FreeFW.ApiController.autocomplete.end');
            return $this->createSuccessResponse(FFCST::SUCCESS_RESPONSE_OK, $data); // 200
        }
        $this->logger->debug('FreeFW.ApiController.autocomplete.end');
        return $this->createErrorResponse(FFCST::ERROR_NOT_FOUND); // 404
    }

    /**
     * Get children
     *
     * @param \Psr\Http\Message\ServerRequestInterface $p_request
     * @param number                                   $p_id
     *
     * @throws \FreeFW\Core\FreeFWStorageException
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getChildren(\Psr\Http\Message\ServerRequestInterface $p_request, $p_id = null)
    {
        $this->logger->debug('FreeFW.ApiController.getChildren.start');
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
         * Id
         */
        if (intval($p_id) > 0) {
            $filters  = new \FreeFW\Model\Conditions();
            $pk_field = $model->getPkField();
            $aField   = new \FreeFW\Model\ConditionMember();
            $aValue   = new \FreeFW\Model\ConditionValue();
            $aValue->setValue($p_id);
            $aField->setValue($pk_field);
            $aCondition = new \FreeFW\Model\SimpleCondition();
            $aCondition->setLeftMember($aField);
            $aCondition->setOperator(\FreeFW\Storage\Storage::COND_EQUAL);
            $aCondition->setRightMember($aValue);
            $filters->add($aCondition);
            /**
             * @var \FreeFW\Model\Query $query
             */
            $query = $model->getQuery();
            $query
                ->addConditions($filters)
                ->addRelations($apiParams->getInclude())
                ->setLimit(0, 1)
            ;
            $data = new \FreeFW\Model\ResultSet();
            if ($query->execute()) {
                $data = $query->getResult();
            }
            if (count($data) > 0) {
                $children = $model->find(
                    [
                        $model->getFieldNameByOption(FFCST::OPTION_NESTED_PARENT_ID) => $data[0]->getApiId()
                    ]
                );
            } else {
                $this->logger->debug('FreeFW.ApiController.getChildren.end');
                return $this->createErrorResponse(FFCST::ERROR_NOT_FOUND); // 404
            }
        } else {
            $children = $model->find(
                [
                    $model->getFieldNameByOption(FFCST::OPTION_NESTED_LEVEL) => 1
                ]
            );
        }

        $this->logger->debug('FreeFW.ApiController.getChildren.end');

//        if (count($chilren) > 0) {
            return $this->createSuccessResponse(FFCST::SUCCESS_RESPONSE_OK, $children); // 200
//        }
//
//        return $this->createErrorResponse(FFCST::ERROR_NO_DATA); // 409
    }

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
        $model = \FreeFW\DI\DI::get($default);
        /**
         * @var \FreeFW\Model\Query $query
         */
        $query = $model->getQuery();
        $query
            ->addConditions($apiParams->getFilters())
            ->addRelations($apiParams->getInclude())
            ->setLimit($apiParams->getStart(), $apiParams->getlength())
            ->setSort($apiParams->getSort())
        ;
        $data = new \FreeFW\Model\ResultSet();
        if ($query->execute()) {
            $data = $query->getResult();
        }
        if (count($data) > 0) {
            $this->logger->debug('FreeFW.ApiController.getOne.end');
            return $this->createSuccessResponse(FFCST::SUCCESS_RESPONSE_OK, $data); // 200
        }
        $this->logger->debug('FreeFW.ApiController.getAll.end');
        return $this->createErrorResponse(FFCST::ERROR_NOT_FOUND); // 404
    }

    /**
     * Get one by id
     *
     * @param \Psr\Http\Message\ServerRequestInterface $p_request
     */
    public function getOne(\Psr\Http\Message\ServerRequestInterface $p_request, $p_id = null)
    {
        $this->logger->debug('FreeFW.ApiController.getOne.start');
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
        $model = \FreeFW\DI\DI::get($default);
        /**
         * Id
         */
        if (intval($p_id) > 0) {
            $filters  = new \FreeFW\Model\Conditions();
            $pk_field = $model->getPkField();
            $aField   = new \FreeFW\Model\ConditionMember();
            $aValue   = new \FreeFW\Model\ConditionValue();
            $aValue->setValue($p_id);
            $aField->setValue($pk_field);
            $aCondition = new \FreeFW\Model\SimpleCondition();
            $aCondition->setLeftMember($aField);
            $aCondition->setOperator(\FreeFW\Storage\Storage::COND_EQUAL);
            $aCondition->setRightMember($aValue);
            $filters->add($aCondition);
            /**
             * @var \FreeFW\Model\Query $query
             */
            $query = $model->getQuery();
            $query
                ->addConditions($filters)
                ->addRelations($apiParams->getInclude())
                ->setLimit(0, 1)
            ;
            $data = new \FreeFW\Model\ResultSet();
            if ($query->execute()) {
                $data = $query->getResult();
            }
            if (count($data) > 0) {
                $model = $data[0];
                $model->setModelBehaviour(\FreeFW\Core\Model::MODEL_BEHAVIOUR_API);
                if (method_exists($model, 'afterRead')) {
                    $model->afterRead();
                }
                $this->logger->debug('FreeFW.ApiController.getOne.end');
                return $this->createSuccessOkResponse($model); // 200
            } else {
                $data = null;
                $code = FFCST::ERROR_NOT_FOUND; // 404
            }
        } else if (intval($p_id) == 0) {
            $model->setModelBehaviour(\FreeFW\Core\Model::MODEL_BEHAVIOUR_API);
            if (method_exists($model, 'afterRead')) {
                $model->afterRead();
            }
            $this->logger->debug('FreeFW.ApiController.getOne.end');
            return $this->createSuccessResponse(FFCST::SUCCESS_RESPONSE_OK, $model); // 200
        } else {
            $data = null;
            $code = FFCST::ERROR_ID_IS_MANDATORY; // 409
        }

        $this->logger->debug('FreeFW.ApiController.getOne.end');
        return $this->createErrorResponse($code, $data);
    }

    /**
     * Add new single element
     *
     * @param \Psr\Http\Message\ServerRequestInterface $p_request
     */
    public function createOne(\Psr\Http\Message\ServerRequestInterface $p_request)
    {
        $this->logger->debug('FreeFW.ApiController.createOne.start');
        /**
         * @var \FreeFW\Http\ApiParams $apiParams
         */
        $apiParams = $p_request->getAttribute('api_params', false);
        if (!isset($p_request->default_model)) {
            throw new \FreeFW\Core\FreeFWStorageException(
                sprintf('No default model for route !')
                );
        }
        if ($apiParams->hasData()) {
            /**
             * @var \FreeFW\Core\StorageModel $data
             */
            $data = $apiParams->getData();
            $data->setModelBehaviour(\FreeFW\Core\Model::MODEL_BEHAVIOUR_API);
            if ($data->create()) {
                $data = $this->getModelById($apiParams, $data, $data->getApiId());
                $this->logger->debug('FreeFW.ApiController.createOne.end');
                return $this->createSuccessResponse(FFCST::SUCCESS_RESPONSE_ADD, $data); // 201
            } else {
                if (!$data->hasErrors()) {
                    $data = null;
                }
                $code = FFCST::ERROR_NOT_INSERT; // 412
            }
        } else {
            $data = null;
            $code = FFCST::ERROR_NO_DATA; // 409
        }

        $this->logger->debug('FreeFW.ApiController.createOne.end');
        return $this->createErrorResponse($code, $data);
    }

    /**
     * Update single element
     *
     * @param \Psr\Http\Message\ServerRequestInterface $p_request
     */
    public function updateOne(\Psr\Http\Message\ServerRequestInterface $p_request, $p_id)
    {
        $this->logger->debug('FreeFW.ApiController.updateOne.start');
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
        $model = \FreeFW\DI\DI::get($default);
        if (intval($p_id) > 0) {
            if ($apiParams->hasData()) {
                /**
                 * @var \FreeFW\Core\StorageModel $data
                 */
                $data = $model->findFirst([$model->getPkField() => $p_id]);
                if ($data) {
                    /**
                     * @var \FreeFW\Core\StorageModel $data
                     */
                    $data = $apiParams->getData();
                    $data->setModelBehaviour(\FreeFW\Core\Model::MODEL_BEHAVIOUR_API);
                    if ($data->save()) {
                        $data = $this->getModelById($apiParams, $data, $data->getApiId());
                        $this->logger->debug('FreeFW.ApiController.updateOne.end');
                        return $this->createSuccessResponse(FFCST::SUCCESS_RESPONSE_OK, $data); // 200
                    } else {
                        if (!$data->hasErrors()) {
                            $data = null;
                        }
                        $code = FFCST::ERROR_NOT_UPDATE; // 412
                    }
                } else {
                    $data = null;
                    $code = FFCST::ERROR_ID_IS_UNAVALAIBLE; // 404
                }
            } else {
                $data = null;
                $code = FFCST::ERROR_NO_DATA; // 409
            }
        } else {
            $data = null;
            $code = FFCST::ERROR_ID_IS_MANDATORY; // 409
        }

        $this->logger->debug('FreeFW.ApiController.updateOne.end');
        return $this->createErrorResponse($code, $data);
    }

    /**
     * Remove single element
     *
     * @param \Psr\Http\Message\ServerRequestInterface $p_request
     */
    public function removeOne(\Psr\Http\Message\ServerRequestInterface $p_request, $p_id = null)
    {
        $this->logger->debug('FreeFW.ApiController.removeOne.start');
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
        $model = \FreeFW\DI\DI::get($default);
        if (intval($p_id) > 0) {
            /**
             * @var \FreeFW\Core\StorageModel $data
             */
            $data = $model->findFirst([$model->getPkField() => $p_id]);
            if ($data) {
                $data->setModelBehaviour(\FreeFW\Core\Model::MODEL_BEHAVIOUR_API);
                if ($data->remove()) {
                    $this->logger->debug('FreeFW.ApiController.removeOne.end');
                    return $this->createSuccessResponse(FFCST::SUCCESS_RESPONSE_EMPTY); // 204
                } else {
                    if (!$data->hasErrors()) {
                        $data = null;
                    }
                    $code = FFCST::ERROR_NOT_DELETE; // 412
                }
            } else {
                $data = null;
                $code = FFCST::ERROR_ID_IS_UNAVALAIBLE; // 404
            }
        } else {
            $data = null;
            $code = FFCST::ERROR_ID_IS_MANDATORY; // 409
        }

        $this->logger->debug('FreeFW.ApiController.removeOne.end');
        return $this->createErrorResponse($code, $data);
    }
}
