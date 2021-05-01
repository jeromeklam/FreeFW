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
            foreach ($data as $line) {
                $line->id = $line->getApiId();
            }
        }
        // data can be empty, but it's a 2*
        $this->logger->debug('FreeFW.ApiController.autocomplete.end');
        return $this->createSuccessOkResponse($data); // 200
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
        return $this->createSuccessOkResponse($children); // 200
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
        if (method_exists($this, 'adaptApiParams')) {
            $apiParams = $this->adaptApiParams($apiParams, 'getAll');
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
        // data can be empty, but it's a 2*
        $this->logger->debug('FreeFW.ApiController.getAll.end');
        return $this->createSuccessOkResponse($data); // 200
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
        if (method_exists($this, 'adaptApiParams')) {
            $apiParams = $this->adaptApiParams($apiParams, 'getOne');
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
                $model = $data[0];
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
            $model->init();
            if (method_exists($model, 'afterRead')) {
                $model->afterRead();
            }
            if (method_exists($model, 'initCreate')) {
                $model->initCreate();
            }
            $this->logger->debug('FreeFW.ApiController.getOne.end');
            return $this->createSuccessOkResponse($model); // 200
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
            if (method_exists($this, 'adaptApiParams')) {
                $apiParams = $this->adaptApiParams($apiParams, 'updateOne');
            }
            /**
             * @var \FreeFW\Core\StorageModel $data
             */
            $data = $apiParams->getData();
            if ($data->create()) {
                $data = $this->getModelById($apiParams, $data, $data->getApiId());
                $this->logger->debug('FreeFW.ApiController.createOne.end');
                return $this->createSuccessAddResponse($data); // 201
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
                if (method_exists($this, 'adaptApiParams')) {
                    $apiParams = $this->adaptApiParams($apiParams, 'updateOne');
                }
                /**
                 * @var \FreeFW\Core\StorageModel $data
                 */
                $data = $model->findFirst([$model->getPkField() => $p_id]);
                if ($data) {
                    /**
                     * @var \FreeFW\Core\StorageModel $data
                     */
                    $newData = $apiParams->getData();
                    $data->merge($newData);
                    if ($data->save()) {
                        $data = $this->getModelById($apiParams, $data, $data->getApiId());
                        $this->logger->debug('FreeFW.ApiController.updateOne.end');
                        return $this->createSuccessUpdateResponse($data); // 200
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
            if (method_exists($this, 'adaptApiParams')) {
                $apiParams = $this->adaptApiParams($apiParams, 'removeOne');
            }
            /**
             * @var \FreeFW\Core\StorageModel $data
             */
            $data = $model->findFirst([$model->getPkField() => $p_id]);
            if ($data) {
                if ($data->remove()) {
                    $this->logger->debug('FreeFW.ApiController.removeOne.end');
                    return $this->createSuccessRemoveResponse(); // 204
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

    /**
     * Print one by id
     *
     * @param \Psr\Http\Message\ServerRequestInterface $p_request
     */
    public function printOne(\Psr\Http\Message\ServerRequestInterface $p_request, $p_id = null)
    {
        $this->logger->info('FreeFW.ApiController.printOne.start');
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
         *
         */
        $print = $apiParams->getData();
        if (!$print instanceof \FreeFW\Model\PrintOptions) {
            $this->logger->info('FreeFW.ApiController.printOne.error.wrong_body');
            return $this->createErrorResponse(FFCST::ERROR_IN_DATA);
        } else {
            $edition = \FreeFW\Model\Edition::findFirst(['edi_id' => $print->getEdiId()]);
            if (!$edition instanceof \FreeFW\Model\Edition) {
                $this->logger->info('FreeFW.ApiController.printOne.error.edi_not_found');
                return $this->createErrorResponse(FFCST::ERROR_EDITION_NOT_FOUND);
            }
            // more checks...
        }
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
                ->addConditions($apiParams->getFilters())
                ->addRelations($apiParams->getInclude())
                ->setLimit(0, 1)
            ;
            $data = new \FreeFW\Model\ResultSet();
            if ($query->execute()) {
                $data = $query->getResult();
            }
            if (count($data) > 0) {
                $model = $data[0];
                if (method_exists($model, 'afterRead')) {
                    $model->afterRead();
                }
                $mergeDatas = $model->getMergeData($apiParams->getInclude());
                // Get group and user
                $sso        = \FreeFW\DI\DI::getShared('sso');
                $user       = $sso->getUser();
                // @todo : rechercher le groupe principal de l'utilisateur
                $group      = \FreeSSO\Model\Group::findFirst(
                    [
                        'grp_id' => 4
                    ]
                );
                //
                $file = uniqid(true, 'edition');
                $src  = '/tmp/print_' . $file . '_tpl.odt';
                $dest = '/tmp/print_' . $file . '_dest.odt';
                $dPdf = '/tmp/print_' . $file . '_dest.pdf';
                $ediContent = $edition->getEdiContent();
                file_put_contents($src, $ediContent);
                file_put_contents($dest, $ediContent);
                $mergeDatas->addGenericBlock('head_user');
                $mergeDatas->addGenericData($user->getFieldsAsArray(), 'head_user');
                $mergeDatas->addGenericBlock('head_group');
                $mergeDatas->addGenericData($group->getFieldsAsArray(), 'head_group');
                $mergeService = \FreeFW\DI\DI::get('FreeOffice::Service::Merge');
                $mergeService->merge($src, $dest, $mergeDatas);
                exec('/usr/bin/unoconv -f pdf -o ' . $dPdf . ' ' . $dest);
                @unlink($dest);
                @unlink($src);
                if (is_file($dPdf)) {
                    $this->logger->info('FreeFW.ApiController.printOne.end');
                    return $this->createMimeTypeResponse($dPdf, file_get_contents($dPdf));
                }
            } else {
                $data = null;
                $code = FFCST::ERROR_NOT_FOUND; // 404
            }
        } else {
            $data = null;
            $code = FFCST::ERROR_ID_IS_MANDATORY; // 409
        }
        $this->logger->info('FreeFW.ApiController.printOne.end');
        return $this->createErrorResponse(FFCST::ERROR_IN_DATA);
    }
}
