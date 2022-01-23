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
     * Traductions
     * @var [\FreeFW\Model\TaxonomyLang]
     */
    protected $traductions = null;

    /**
     * Get traductions
     * 
     * return [\FreeFW\Model\TaxonomyLang]
     */
    public function getTraductions()
    {
        if ($this->traductions === null) {
            $this->traductions = \FreeFW\Model\TaxonomyLang::find(['tx_id' => $this->getTxId()]);
        }
        return $this->traductions;
    }

    /**
     * Set traductions
     * 
     * @param [\FreeFW\Model\TaxonomyLang] $p_traductions
     * 
     * @return \FreeFW\Model\Taxonomy 
     */
    public function setTraductiuons($p_traductions)
    {
        $this->traductions = $p_traductions;
        return $this;
    }
}
