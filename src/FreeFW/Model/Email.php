<?php
namespace FreeFW\Model;

use \FreeFW\Constants as FFCST;

/**
 * Email
 *
 * @author jeromeklam
 */
class Email extends \FreeFW\Model\Base\Email implements
    \FreeFW\Interfaces\ApiResponseInterface
{

    /**
     * Behaviours
     */
    use \FreeFW\Model\Behaviour\Lang;

    /**
     * Versions
     * @var [\FreeFW\Model\Edition]
     */
    protected $versions = null;

    /**
     * Set versions
     *
     * @param array $p_versions
     *
     * @return \FreeFW\Model\EmailLang
     */
    public function setVersions($p_versions)
    {
        $this->versions = $p_versions;
        return $this;
    }

    /**
     * Get versions
     *
     * @return [\FreeFW\Model\EmailLang]
     */
    public function getVersions()
    {
        if ($this->versions === null) {
            $this->versions = \FreeFW\Model\EmailLang::find(['email_id' => $this->getEmailId()]);
        }
        return $this->versions;
    }

    /**
     * Avant la sauvegarde
     *
     * @return boolean
     */
    public function beforeSave()
    {
        $olds = \FreeFW\Model\EmailLang::find(['email_id' => $this->getEmailId()]);
        foreach ($olds as $oneVersion) {
            if (!$oneVersion->remove()) {
                $this->addErrors($oneVersion->getErrors());
                return false;
            }
        }
        return true;
    }

    /**
     * Après la sauvegarde
     *
     * @return boolean
     */
    protected function saveVersions()
    {
        foreach ($this->versions as $oneVersion) {
            $oneVersion->setEmaillId(null);
            $oneVersion->setEmailId($this->getEmailId());
            if (!$oneVersion->create()) {
                $this->addErrors($oneVersion->getErrors());
                return false;
            }
        }
        return true;
    }

    /**
     * After save
     *
     * @return boolean
     */
    public function afterSave()
    {
        return $this->saveVersions();
    }

    /**
     * After create
     *
     * @return boolean
     */
    public function afterCreate()
    {
        return $this->saveVersions();
    }

    /**
     * Merge datas in fields
     *
     * @param array $p_datas
     */
    public function mergeDatas($p_datas)
    {
        $this->email_subject = \FreeFW\Tools\PBXString::parse($this->email_subject, $p_datas);
        $this->email_body    = \FreeFW\Tools\PBXString::parse($this->email_body, $p_datas);
    }
}
