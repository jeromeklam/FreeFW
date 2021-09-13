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
     * @param int                $p_edi_id
     * @param int                $p_lang_id
     * @param \FreeFW\Core\Model $p_model
     *
     * @return string
     */
    public function printEdition($p_edi_id, $p_lang_id, \FreeFW\Core\Model $p_model)
    {
        $filename = '';
        $name     = '';
        /**
         * @var \FreeFW\Model\EditionLang $editionVersion
         */
        $editionVersion = null;
        $edition        = \FreeFW\Model\Edition::findFirst(['edi_id' => $p_edi_id]);
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
                        break;
                    }
                }
            }
        }
        if ($editionVersion) {
            $name = $editionVersion->getEdilFilename();
            if (method_exists($p_model, 'afterRead')) {
                $p_model->afterRead();
            }
            $mergeDatas = $p_model->getMergeData();
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
            $cfg  = $this->getAppConfig();
            $dir  = $cfg->get('ged:dir');
            if (!is_dir($dir)) {
                $dir = '/tmp/';
            }
            $bDir = rtrim(\FreeFW\Tools\Dir::mkStdFolder($dir), '/');
            $file = uniqid(true, 'edition');
            $src  = $bDir . '/print_' . $file . '_tpl.odt';
            $dest = $bDir . '/print_' . $file . '_dest.odt';
            $dPdf = $bDir . '/print_' . $file . '_dest.pdf';
            $ediContent = $edition->getEdiContent();
            file_put_contents($src, $ediContent);
            file_put_contents($dest, $ediContent);
            if ($user) {
                $mergeDatas->addGenericBlock('head_user');
                $mergeDatas->addGenericData($user->getFieldsAsArray(), 'head_user');
            }
            if ($group) {
                $mergeDatas->addGenericBlock('head_group');
                $mergeDatas->addGenericData($group->getFieldsAsArray(), 'head_group');
            }
            $mergeService = \FreeFW\DI\DI::get('FreeOffice::Service::Merge');
            $mergeService->merge($src, $dest, $mergeDatas);
            exec('/usr/bin/unoconv -f pdf -o ' . $dPdf . ' ' . $dest);
            @unlink($dest);
            @unlink($src);
            $filename = $dPdf;
        }
        return [
            'name' => $name,
            'filename'  => $filename,
        ];
    }
}
