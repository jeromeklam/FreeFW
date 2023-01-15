<?php

namespace FreeFW\Service;

/**
 * Edition service
 *
 * @author jeromeklam
 */
class Edition extends \FreeFW\Core\Service
{

    /**
     * Generate pdf
     *
     * @param int                  $p_edi_id
     * @param int                  $p_lang_id
     * @param \FreeFW\Core\Model   $p_model
     * @param \FreeSSO\Model\Group $p_grp_id
     *
     * @return array
     */
    public function printEdition($p_edi_id, $p_lang_id, \FreeFW\Core\Model $p_model)
    {
        $filename = '';
        $name     = '';
        /**
         * @var \FreeFW\Model\EditionLang $editionVersion
         */
        $editionVersion = null;
        /**
         * @var \FreeFW\Model\Edition $edition
         */
        $edition = \FreeFW\Model\Edition::findFirst(['edi_id' => $p_edi_id]);
        if ($edition instanceof \FreeFW\Model\Edition) {
            foreach ($edition->getVersions() as $oneVersion) {
                if ($oneVersion->getLangId() == $p_lang_id) {
                    $editionVersion = $oneVersion;
                    break;
                }
            }
            if ($editionVersion === null) {
                foreach ($edition->getVersions() as $oneVersion) {
                    $editionVersion = $oneVersion;
                    if ($oneVersion->getLangId() == $edition->getLangId()) {
                        $p_lang_id = $edition->getLangId();
                        break;
                    }
                }
            }
        }
        if ($editionVersion) {
            $includes = true;
            // Les includes de l'édition sont prioritaires
            if ($edition->getEdiIncludes() != '') {
                $includes = explode(',', $edition->getEdiIncludes());
            }
            $lang = \FreeFW\Model\Lang::findFirst(['lang_id' => $p_lang_id]);
            $name = $editionVersion->getEdilFilename();
            if (method_exists($p_model, 'afterRead')) {
                $p_model->afterRead();
            }
            $mergeDatas = $p_model->getMergeData($includes, '', '', false, $lang->getLangCode());
            // Get group and user
            $sso        = \FreeFW\DI\DI::getShared('sso');
            $user       = $sso->getUser();
            // @todo : rechercher le groupe principal de l'utilisateur
            if (method_exists($p_model, 'getGrpId')) {
                $grpId = $p_model->getGrpId();
            }
            if (!$grpId) {
                $group = $sso->getUserGroup();
                if ($group) {
                    $grpId = $group->getGrpId();
                }
            }
            $group = \FreeSSO\Model\Group::findFirst(
                [
                    'grp_id' => $grpId
                ]
            );
            //
            $mergeDatas->addBlock('main');
            $mergeDatas->addData(['now' => \FreeFW\Tools\Date::mysqlToddmmyyyy(\FreeFW\Tools\Date::getCurrentTimestamp(), false, false)], 'main');
            if ($user) {
                $mergeUser = $user->getMergeData(true, '', '', false, $lang->getLangCode(), 'head_user');
                $mergeDatas->merge($mergeUser);
            }
            if ($group) {
                $mergeGroup = $group->getMergeData(true, '', '', false, $lang->getLangCode(), 'head_group');
                $mergeDatas->merge($mergeGroup);
            }
            //
            $cfg  = $this->getAppConfig();
            $dir  = $cfg->get('ged:dir');
            if (!is_dir($dir)) {
                $dir = '/tmp/';
            }
            $bDir = rtrim(\FreeFW\Tools\Dir::mkStdFolder($dir), '/');
            $file = uniqid(true, 'edition');
            if ($edition->getEdiType() == \FreeFW\Model\Edition::TYPE_PDF) {
                $src  = $bDir . '/print_' . $file . '_tpl.pdf';
                $dPdf = $bDir . '/print_' . $file . '_dest.pdf';
                $this->logger->debug("before " . $dPdf);
                $ediContent = $editionVersion->getEdilData();
                file_put_contents($src, $ediContent);
                $mapping = json_decode($edition->getEdiMapping(), true);
                if (!$mapping) {
                    $this->logger->error('Mapping for edition not found or not json !');
                    return false;
                }
                $mergedDatas = $mergeDatas->computeFromMapping($mapping);
                $pdf = new \mikehaertl\pdftk\Pdf($src);
                $result = $pdf
                    ->fillForm($mergedDatas)
                    ->flatten()
                    ->saveAs($dPdf);
                @unlink($src);
            } else {
                $src  = $bDir . '/print_' . $file . '_tpl.odt';
                $dest = $bDir . '/print_' . $file . '_dest.odt';
                $dPdf = $bDir . '/print_' . $file . '_dest.pdf';
                $this->logger->debug("before " . $dPdf);
                $ediContent = $editionVersion->getEdilData();
                file_put_contents($src, $ediContent);
                file_put_contents($dest, $ediContent);
                $mergeService = \FreeFW\DI\DI::get('FreeOffice::Service::Merge');
                $mergeService->merge($src, $dest, $mergeDatas);
                exec('/usr/bin/unoconv -f pdf -o ' . $dPdf . ' ' . $dest);
                @unlink($dest);
                @unlink($src);
            }
            $filename = $dPdf;
            $this->logger->debug("after " . $filename);
        }
        return [
            'name' => $name,
            'filename' => $filename,
        ];
    }
}
