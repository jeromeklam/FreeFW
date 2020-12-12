<?php
namespace FreeFW\Controller;

/**
 * Controller Edition
 *
 * @author jeromeklam
 */
class Edition extends \FreeFW\Core\ApiController
{

    /**
     * Get latest
     *
     * @param \Psr\Http\Message\ServerRequestInterface $p_request
     * @param mixed                                    $p_id
     */
    public function print(\Psr\Http\Message\ServerRequestInterface $p_request, $p_id)
    {
        $this->logger->debug('FreeFW.Controller.Edition.print.start');
        /**
         * @var \FreeFW\Http\ApiParams $apiParams
         */
        $apiParams = $p_request->getAttribute('api_params', false);
        if (!isset($p_request->default_model)) {
            throw new \FreeFW\Core\FreeFWStorageException(
                sprintf('No default model for route !')
            );
        }
        $data = null;
        /**
         * @var \FreeFW\Model\Edition $edition
         */
        $edition = \FreeFW\Model\Edition::findFirst(['edi_id' => $p_id]);
        if (!$edition) {
            return $this->createErrorResponse(\FreeFW\Constants::ERROR_EDITION_NOT_FOUND, 'Edition not found !');
        }
        $data    = $apiParams->getData();
        $toPrint = new \FreeFW\Model\ResultSet();
        if (is_array($data) || $data instanceof \ArrayAccess) {
            $toPrint = $data;
        } else {
            $toPrint->add($data);
        }
        // Get group and user
        $sso        = \FreeFW\DI\DI::getShared('sso');
        $user       = $sso->getUser();
        $group      = \FreeSSO\Model\Group::findFirst(['grp_id' => 15]);
        //
        $cfg    = $this->getAppConfig();
        $tmpDir = rtrim($cfg->get('tmpDir', '/tmp'), '/'). '/';
        $file   = uniqid(true, 'edi');
        $ext    = '';
        switch ($edition->getEdiType()) {
            case \FreeFW\Model\Edition::TYPE_WRITER:
                $ext = 'odt';
                break;
            case \FreeFW\Model\Edition::TYPE_CALC:
                $ext = 'ods';
                break;
            case \FreeFW\Model\Edition::TYPE_IMPRESS:
                $ext = 'odp';
                break;
            case \FreeFW\Model\Edition::TYPE_HTML:
                $ext = 'html';
                break;
            default:
                return $this->createErrorResponse(\FreeFW\Constants::ERROR_EDITION_NOT_FOUND, 'Unknown format !');
        }
        $src  = $tmpDir . 'edi_' . $file . '_tpl.' . $ext;
        $dest = $tmpDir . 'edi_' . $file . '_dest.' . $ext;
        file_put_contents($src, $edition->getEdiData());
        file_put_contents($dest, $edition->getEdiData());
        foreach ($toPrint as $oneModel) {
            $oneModel     = $oneModel->findFirst(['id' => $oneModel->getApiId()]);
            /**
             * @var \FreeFW\Model\MergeModel $mergeDatas
             */
            $mergeDatas = $oneModel->getMergeData($tmpDir);
            $mergeDatas->addGenericBlock('user');
            $mergeDatas->addGenericData($user->getFieldsAsArray(), 'user');
            $mergeDatas->addGenericBlock('group');
            $mergeDatas->addGenericData($group->getFieldsAsArray(), 'group');
            $mergeService = \FreeFW\DI\DI::get('FreeOffice::Service::Merge');
            $mergeService->merge($src, $dest, $mergeDatas);
        }
        $this->logger->debug('FreeFW.Controller.Edition.print.end');
        return $this->createMimeTypeResponse('res.odt', file_get_contents($dest));
    }
}
