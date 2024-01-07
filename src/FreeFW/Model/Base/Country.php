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
     * cnty_name_en
     * @var string
     */
    protected $cnty_name_en = null;

    /**
     * cnty_code
     * @var string
     */
    protected $cnty_code = null;

    /**
     * cnty_cog
     * @var string
     */
    protected $cnty_cog = null;

    /**
     * cnty_iso2
     * @var string
     */
    protected $cnty_iso2 = null;

    /**
     * cnty_iso3
     * @var string
     */
    protected $cnty_iso3 = null;

    /**
     * cnty_num
     * @var string
     */
    protected $cnty_num = null;

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
     * Set cnty_name_en
     *
     * @param string $p_value
     *
     * @return \FreeFW\Model\Country
     */
    public function setCntyNameEn($p_value)
    {
        $this->cnty_name_en = $p_value;
        return $this;
    }

    /**
     * Get cnty_name_en
     *
     * @return string
     */
    public function getCntyNameEn()
    {
        return $this->cnty_name_en;
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

    /**
     * Set cnty_cog
     *
     * @param string $p_value
     *
     * @return \FreeFW\Model\Country
     */
    public function setCntyCog($p_value)
    {
        $this->cnty_cog = $p_value;
        return $this;
    }

    /**
     * Get cnty_cog
     *
     * @return string
     */
    public function getCntyCog()
    {
        return $this->cnty_cog;
    }

    /**
     * Set cnty_iso2
     *
     * @param string $p_value
     *
     * @return \FreeFW\Model\Country
     */
    public function setCntyIso2($p_value)
    {
        $this->cnty_iso2 = $p_value;
        return $this;
    }

    /**
     * Get cnty_iso2
     *
     * @return string
     */
    public function getCntyIso2()
    {
        return $this->cnty_iso2;
    }

    /**
     * Set cnty_iso3
     *
     * @param string $p_value
     *
     * @return \FreeFW\Model\Country
     */
    public function setCntyIso3($p_value)
    {
        $this->cnty_iso3 = $p_value;
        return $this;
    }

    /**
     * Get cnty_iso3
     *
     * @return string
     */
    public function getCntyIso3()
    {
        return $this->cnty_iso3;
    }

    /**
     * Set cnty_num
     *
     * @param string $p_value
     *
     * @return \FreeFW\Model\Country
     */
    public function setCntyNum($p_value)
    {
        $this->cnty_num = $p_value;
        return $this;
    }

    /**
     * Get cnty_num
     *
     * @return string
     */
    public function getCntyNum()
    {
        return $this->cnty_num;
    }
}
