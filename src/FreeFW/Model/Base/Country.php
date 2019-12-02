<?php
namespace FreeFW\Model\Base;

/**
 * Country
 *
 * @author jeromeklam
 */
abstract class Country extends \FreeFW\Model\StorageModel\Country
{

    /**
     * cnty_id
     * @var int
     */
    protected $cnty_id = null;

    /**
     * cnty_name
     * @var string
     */
    protected $cnty_name = null;

    /**
     * cnty_code
     * @var string
     */
    protected $cnty_code = null;

    /**
     * Set cnty_id
     *
     * @param int $p_value
     *
     * @return \FreeFW\Model\Country
     */
    public function setCntyId($p_value)
    {
        $this->cnty_id = $p_value;
        return $this;
    }

    /**
     * Get cnty_id
     *
     * @return int
     */
    public function getCntyId()
    {
        return $this->cnty_id;
    }

    /**
     * Set cnty_name
     *
     * @param string $p_value
     *
     * @return \FreeFW\Model\Country
     */
    public function setCntyName($p_value)
    {
        $this->cnty_name = $p_value;
        return $this;
    }

    /**
     * Get cnty_name
     *
     * @return string
     */
    public function getCntyName()
    {
        return $this->cnty_name;
    }

    /**
     * Set cnty_code
     *
     * @param string $p_value
     *
     * @return \FreeFW\Model\Country
     */
    public function setCntyCode($p_value)
    {
        $this->cnty_code = $p_value;
        return $this;
    }

    /**
     * Get cnty_code
     *
     * @return string
     */
    public function getCntyCode()
    {
        return $this->cnty_code;
    }
}
