<?php
namespace FreeFW\Model;

use \FreeFW\Constants as FFCST;

/**
 * Model Edition
 *
 * @author jeromeklam
 */
class Edition extends \FreeFW\Model\Base\Edition
{

    /**
     * Types
     * @var string
     */
    const TYPE_WRITER  = 'WRITER';
    const TYPE_IMPRESS = 'CALC';
    const TYPE_CALC    = 'CALC';
    const TYPE_HTML    = 'HTML';
    const TYPE_PDF     = 'PDF';

    /**
     * Behaviours
     */
    use \FreeFW\Model\Behaviour\Lang;
    use \FreeSSO\Model\Behaviour\Broker;
    use \FreeSSO\Model\Behaviour\Group;

    /**
     * Versions
     * @var [\FreeFW\Model\EditionLang]
     */
    protected $versions = null;

    /**
     * Set versions
     *
     * @param array $p_versions
     *
     * @return \FreeFW\Model\EditionLang
     */
    public function setVersions($p_versions)
    {
        $this->versions = $p_versions;
        return $this;
    }

    /**
     * Get versions
     *
     * @return [\FreeFW\Model\EditionLang]
     */
    public function getVersions()
    {
        if ($this->versions === null) {
            $this->versions = \FreeFW\Model\EditionLang::find(['edi_id' => $this->getEdiId()]);
        }
        return $this->versions;
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function getLangs()
    {
        $versions = $this->getVersions();
        $langs = [];
        /**
         * @var \FreeFW\Model\EditionLang $oneVersion
         */
        foreach ($versions as $oneVersion) {
            $langs[] = $oneVersion->getLang()->getLangCode();
        }
        return $langs;
    }

    /**
     * Get content
     *
     * @return mixed
     */
    public function getEdiContent($p_lang = '')
    {
        $retVersion = null;
        foreach ($this->getVersions() as $oneVersion) {
            $retVersion = $oneVersion->getEdilData();
            if (strtoupper($oneVersion->getLang()->getLangCode()) == strtoupper($p_lang)) {
                break;
            }
        }
        return $retVersion;
    }

    /**
     * Avant la sauvegarde
     *
     * @return boolean
     */
    public function beforeSave()
    {
        $olds = \FreeFW\Model\EditionLang::find(['edi_id' => $this->getEdiId()]);
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
        if ($this->versions) {
            foreach ($this->versions as $oneVersion) {
                $oneVersion->setEdilId(null);
                $oneVersion->setEdiId($this->getEdiId());
                if (!$oneVersion->create()) {
                    $this->addErrors($oneVersion->getErrors());
                    return false;
                }
            }
            return true;
        }
    }

    /**
     * After update
     *
     * @return boolean
     */
    public function afterSave()
    {
        return $this->saveVersions();
    }

    /**
     * After Create
     *
     * @return boolean
     */
    public function afterCreate()
    {
        return $this->saveVersions();
    }
}
