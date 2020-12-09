<?php
namespace FreeFW\Controller;

/**
 * Controller Edition
 *
 * @author jeromeklam
 */
class Export extends \FreeFW\Core\ApiController
{

    /**
     * Export all rows to xlsx
     *
     * @param \Psr\Http\Message\ServerRequestInterface $p_request
     */
    public function calc(\Psr\Http\Message\ServerRequestInterface $p_request)
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
        $data    = $apiParams->getData();
        $toPrint = new \FreeFW\Model\ResultSet();
        if (is_array($data) || $toPrint instanceof \ArrayAccess) {
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
        $dest   = $tmpDir . 'edi_' . $file . '_dest.ods';
        $sheet  = new \FreeOffice\Model\Sheet($dest);
        foreach ($data as $oneModel) {
            $oneModel = $oneModel->findFirst(['id' => $oneModel->getApiId()]);
            /**
             * @var \FreeFW\Model\MergeModel $mergeDatas
             */
            $mergeDatas = $oneModel->getMergeData($tmpDir, false);
            $sheet->addLine($mergeDatas);
        }
        $sheet->close();
        $this->logger->debug('FreeFW.Controller.Edition.print.end');
        return $this->createMimeTypeResponse('export.xls', file_get_contents($dest));
    }
}
