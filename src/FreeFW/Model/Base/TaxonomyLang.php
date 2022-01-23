<?php
namespace FreeFW\Model\Base;

/**
 * TaxonomyLang
 *
 * @author jeromeklam
 */
abstract class TaxonomyLang extends \FreeFW\Model\StorageModel\TaxonomyLang
{

    /**
     * txl_id
     * @var int
     */
    protected $txl_id = null;

    /**
     * tx_id
     * @var int
     */
    protected $tx_id = null;

    /**
     * lang_id
     * @var int
     */
    protected $lang_id = null;

    /**
     * txl_content
     * @var mixed
     */
    protected $txl_content = null;

    /**
     * Set txl_id
     *
     * @param int $p_value
     *
     * @return \FreeFW\Model\TaxonomyLang
     */
    public function setTxlId($p_value)
    {
        $this->txl_id = $p_value;
        return $this;
    }

    /**
     * Get txl_id
     *
     * @return int
     */
    public function getTxlId()
    {
        return $this->txl_id;
    }

    /**
     * Set tx_id
     *
     * @param int $p_value
     *
     * @return \FreeFW\Model\TaxonomyLang
     */
    public function setTxId($p_value)
    {
        $this->tx_id = $p_value;
        return $this;
    }

    /**
     * Get tx_id
     *
     * @return int
     */
    public function getTxId()
    {
        return $this->tx_id;
    }

    /**
     * Set lang_id
     *
     * @param int $p_value
     *
     * @return \FreeFW\Model\TaxonomyLang
     */
    public function setLangId($p_value)
    {
        $this->lang_id = $p_value;
        return $this;
    }

    /**
     * Get lang_id
     *
     * @return int
     */
    public function getLangId()
    {
        return $this->lang_id;
    }

    /**
     * Set txl_content
     *
     * @param mixed $p_value
     *
     * @return \FreeFW\Model\TaxonomyLang
     */
    public function setTxlContent($p_value)
    {
        $this->txl_content = $p_value;
        return $this;
    }

    /**
     * Get txl_content
     *
     * @return mixed
     */
    public function getTxlContent()
    {
        return $this->txl_content;
    }
}
