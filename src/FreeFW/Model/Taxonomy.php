<?php
namespace FreeFW\Model;

use \FreeFW\Constants as FFCST;

/**
 * Model Taxonomy
 *
 * @author jeromeklam
 */
class Taxonomy extends \FreeFW\Model\Base\Taxonomy
{

    /**
     * Versions
     * @var [\FreeFW\Model\TaxonomyLang]
     */
    protected $versions = null;

    /**
     * Get traductions
     * 
     * return [\FreeFW\Model\TaxonomyLang]
     */
    public function getVersions()
    {
        if ($this->versions === null) {
            $this->versions = \FreeFW\Model\TaxonomyLang::find(['tx_id' => $this->getTxId()]);
        }
        return $this->versions;
    }

    /**
     * Set versions
     * 
     * @param [\FreeFW\Model\TaxonomyLang] $p_versions
     * 
     * @return \FreeFW\Model\Taxonomy 
     */
    public function setVersions($p_versions)
    {
        $this->versions = $p_versions;
        return $this;
    }

        /**
     * Avant la sauvegarde
     *
     * @return boolean
     */
    public function beforeSave()
    {
        $olds = \FreeFW\Model\TaxonomyLang::find(['tx_id' => $this->getTxId()]);
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
                $oneVersion->setTxlId(null);
                $oneVersion->setTxId($this->getTxId());
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
     * Undocumented function
     *
     * @param [type] $p_object_name
     * @param [type] $p_object_id
     * @param [type] $p_lang_code
     * 
     * @return Array
     */
    public static function getAsFieldsByObjectAndLang($p_object_name, $p_object_id, $p_lang_code)
    {
        $fields = [];
        $taxos = \FreeFW\Model\Taxonomy::find(
            [
                'tx_object_name' => $p_object_name,
                'tx_object_id' => $p_object_id
            ]
        );
        foreach ($taxos as $oneTaxo) {
            $content = '';
            $oneVersion = \FreeFW\Model\TaxonomyLang::findFirst(
                [
                    'lang.lang_code' => $p_lang_code,
                    'tx_id' => $oneTaxo->getTxId()
                ]
            );
            if ($oneVersion) {
                $content = $oneVersion->getTxlContent();
            }
            $fields[$oneTaxo->getTxCode()] = [
                'name'    => $oneTaxo->getTxCode(),
                'title'   => $oneTaxo->getTxCode(),
                'type'    => \FreeFW\Constants::TYPE_HTML,
                'content' => $content 
            ];
            $fields[$oneTaxo->getTxCode()] = [
                'name'    => $oneTaxo->getTxCode() . '_txt',
                'title'   => $oneTaxo->getTxCode(),
                'type'    => \FreeFW\Constants::TYPE_TEXT,
                'content' => \FreeFW\Tools\PBXString::htmlToText($content) 
            ];
        }
        return $fields;
    }
}
