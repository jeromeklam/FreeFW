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
    use \FreeFW\Model\Behaviour\Template;
    use \FreeSSO\Model\Behaviour\Group;

    /**
     * Versions
     * @var [\FreeFW\Model\Edition]
     */
    protected $versions = null;

    /**
     * edition 1
     * @var \FreeFW\Model\Edition
     */
    protected $edition1 = null;

    /**
     * edition 2
     * @var \FreeFW\Model\Edition
     */
    protected $edition2 = null;

    /**
     * Set email_edi1_id
     *
     * @param int $p_value
     *
     * @return \FreeFW\Model\Email
     */
    public function setEmailEdi1Id($p_value)
    {
        $this->email_edi1_id = $p_value;
        if ($this->edition1 && $this->edition1->getEdiId() !== $this->email_edi1_id) {
            $this->edition1 = null;
        }
        return $this;
    }

    /**
     * Set edition1
     *
     * @param \FreeFW\Model\Edition $p_value
     *
     * @return \FreeFW\Model\Email
     */
    public function setEdition1($p_value)
    {
        $this->edition1 = $p_value;
        if ($this->edition1) {
            $this->email_edi1_id = $this->edition1->getEdiId();
        }
        return $this;
    }

    /**
     * Get edition1
     *
     * @param boolean $p_force
     *
     * @return \FreeFW\Model\Edition
     */
    public function getEdition1($p_force = false)
    {
        if ($this->edition1 === null || $p_force) {
            if ($this->email_edi1_id > 0) {
                $this->edition1 = \FreeFW\Model\Edition::findFirst(['edi_id' => $this->email_edi1_id]);
            } else {
                $this->edition1 = null;
            }
        }
        return $this->edition1;
    }

    /**
     * Set email_edi2_id
     *
     * @param int $p_value
     *
     * @return \FreeFW\Model\Email
     */
    public function setEmailEdi2Id($p_value)
    {
        $this->email_edi2_id = $p_value;
        if ($this->edition2 && $this->edition2->getEdiId() !== $this->email_edi2_id) {
            $this->edition2 = null;
        }
        return $this;
    }

    /**
     * Set edition2
     *
     * @param \FreeFW\Model\Edition $p_value
     *
     * @return \FreeFW\Model\Email
     */
    public function setEdition2($p_value)
    {
        $this->edition2 = $p_value;
        if ($this->edition2) {
            $this->email_edi2_id = $this->edition2->getEdiId();
        }
        return $this;
    }

    /**
     * Get edition2
     *
     * @param boolean $p_force
     *
     * @return \FreeFW\Model\Edition
     */
    public function getEdition2($p_force = false)
    {
        if ($this->edition2 === null || $p_force) {
            if ($this->email_edi2_id > 0) {
                $this->edition2 = \FreeFW\Model\Edition::findFirst(['edi_id' => $this->email_edi2_id]);
            } else {
                $this->edition2 = null;
            }
        }
        return $this->edition2;
    }

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
        if (is_array($this->versions)) {
            foreach ($this->versions as $oneVersion) {
                $oneVersion->setEmaillId(null);
                $oneVersion->setEmailId($this->getEmailId());
                if (!$oneVersion->create()) {
                    $this->addErrors($oneVersion->getErrors());
                    return false;
                }
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
