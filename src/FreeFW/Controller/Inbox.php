<?php
namespace FreeFW\Controller;

use \FreeFW\Constants as FFCST;

/**
 * Controller Inbox
 *
 * @author jeromeklam
 */
class Inbox extends \FreeFW\Core\ApiController
{

    /**
     * Download content
     *
     * @param \Psr\Http\Message\ServerRequestInterface $p_request
     */
    public function downloadOne(\Psr\Http\Message\ServerRequestInterface $p_request, $p_id = null)
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
                $model->setInboxDownloadTs(\FreeFW\Tools\Date::getCurrentTimestamp());
                $model->save();
                return $this->createMimeTypeResponse($model->getInboxFilename(), $model->getInboxContent());
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
}
