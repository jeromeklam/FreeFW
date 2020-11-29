<?php
namespace FreeFW\Controller;

use FreeFW\Model\MergeModel;

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
        $edition = \FreeFW\Model\Edition::findFirst(['edi_id' => $p_id]);
        if (!$edition) {
            return $this->createErrorResponse(\FreeFW\Constants::ERROR_EDITION_NOT_FOUND, 'Edition not found !');
        }
        $data    = $apiParams->getData();
        $toPrint = new \FreeFW\Model\ResultSet();
        if (is_array($data) || $toPrint instanceof \ArrayAccess) {
            $toPrint = $data;
        } else {
            $toPrint->add($data);
        }
        $file = uniqid(true, 'edi');
        file_put_contents('/tmp/edi_' . $file . '_tpl.odt', $edition->getEdiData());
        file_put_contents('/tmp/edi_' . $file . '_dest.odt', $edition->getEdiData());
        foreach ($data as $oneModel) {
            $oneModel     = $oneModel->findFirst(['id' => $oneModel->getApiId()]);
            $mergeDatas   = $oneModel->getMergeData();
            $mergeService = \FreeFW\DI\DI::get('FreeOffice::Service::Merge');
            $mergeService->merge('/tmp/edi_' . $file . '_tpl.odt', '/tmp/edi_' . $file . '_dest.odt', $mergeDatas);
        }


        return $this->createMimeTypeResponse('res.odt', file_get_contents('/tmp/edi_' . $file . '_dest.odt'));
        $this->logger->debug('FreeFW.Controller.Edition.print.end');
        return $this->createResponse(200, $data);
    }
}
