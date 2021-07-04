<?php
namespace FreeFW\Controller;

use \FreeFW\Constants as FFCST;

/**
 * Notification controller
 *
 * @author jeromeklam
 */
class Notification extends \FreeFW\Core\ApiController
{

    /**
     * Comportement
     */
    use \FreeSSO\Controller\Behaviour\Group;

    /**
     * Update single element
     *
     * @param \Psr\Http\Message\ServerRequestInterface $p_request
     */
    public function validateOne(\Psr\Http\Message\ServerRequestInterface $p_request, $p_id)
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
            /**
             * @var \FreeFW\Core\StorageModel $data
             */
            $data = $model->findFirst([$model->getPkField() => $p_id]);
            if ($data) {
                /**
                 * @var \FreeFW\Model\Notification $data
                 */
                $data
                    ->setNotifRead(true)
                    ->setNotifReadTs(\FreeFW\TOols\Date::getCurrentTimestamp())
                ;
                $sso = \FreeFW\DI\DI::getShared('sso');
                if ($sso) {
                    $user = $sso->getUser();
                    if ($user) {
                        $data->setUserId($user->getUserId());
                    }
                }
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
            $code = FFCST::ERROR_ID_IS_MANDATORY; // 409
        }
        $this->logger->debug('FreeFW.ApiController.updateOne.end');
        return $this->createErrorResponse($code, $data);
    }
}
