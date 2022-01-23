<?php
namespace FreeFW\Model\Base;

/**
 * Taxonomy
 *
 * @author jeromeklam
 */
abstract class Taxonomy extends \FreeFW\Model\StorageModel\Taxonomy
{

    /**
     * tx_id
     * @var int
     */
    protected $tx_id = null;

    /**
     * tx_code
     * @var string
     */
    protected $tx_code = null;

    /**
     * tx_desc
     * @var mixed
     */
    protected $tx_desc = null;

    /**
     * tx_object_name
     * @var string
     */
    protected $tx_object_name = null;

    /**
     * tx_object_id
     * @var int
     */
    protected $tx_object_id = null;

    /**
     * tx_parent_id
     * @var int
     */
    protected $tx_parent_id = null;

    /**
     * Set tx_id
     *
     * @param int $p_value
     *
     * @return \FreeFW\Model\Taxonomy
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
     * Set tx_code
     *
     * @param string $p_value
     *
     * @return \FreeFW\Model\Taxonomy
     */
    public function setTxCode($p_value)
    {
        $this->tx_code = $p_value;
        return $this;
    }

    /**
     * Get tx_code
     *
     * @return string
     */
    public function getTxCode()
    {
        return $this->tx_code;
    }

    /**
     * Set tx_desc
     *
     * @param mixed $p_value
     *
     * @return \FreeFW\Model\Taxonomy
     */
    public function setTxDesc($p_value)
    {
        $this->tx_desc = $p_value;
        return $this;
    }

    /**
     * Get tx_desc
     *
     * @return mixed
     */
    public function getTxDesc()
    {
        return $this->tx_desc;
    }

    /**
     * Set tx_object_name
     *
     * @param string $p_value
     *
     * @return \FreeFW\Model\Taxonomy
     */
    public function setTxObjectName($p_value)
    {
        $this->tx_object_name = $p_value;
        return $this;
    }

    /**
     * Get tx_object_name
     *
     * @return string
     */
    public function getTxObjectName()
    {
        return $this->tx_object_name;
    }

    /**
     * Set tx_object_id
     *
     * @param int $p_value
     *
     * @return \FreeFW\Model\Taxonomy
     */
    public function setTxObjectId($p_value)
    {
        $this->tx_object_id = $p_value;
        return $this;
    }

    /**
     * Get tx_object_id
     *
     * @return int
     */
    public function getTxObjectId()
    {
        return $this->tx_object_id;
    }

    /**
     * Set tx_parent_id
     *
     * @param int $p_value
     *
     * @return \FreeFW\Model\Taxonomy
     */
    public function setTxParentId($p_value)
    {
        $this->tx_parent_id = $p_value;
        return $this;
    }

    /**
     * Get tx_parent_id
     *
     * @return int
     */
    public function getTxParentId()
    {
        return $this->tx_parent_id;
    }
}
