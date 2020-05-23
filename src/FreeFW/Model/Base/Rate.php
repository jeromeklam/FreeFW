<?php
namespace FreeFW\Model\Base;

/**
 * Rate
 *
 * @author jeromeklam
 */
abstract class Rate extends \FreeFW\Model\StorageModel\Rate
{

    /**
     * rate_id
     * @var int
     */
    protected $rate_id = null;

    /**
     * rate_money_from
     * @var string
     */
    protected $rate_money_from = null;

    /**
     * rate_money_to
     * @var string
     */
    protected $rate_money_to = null;

    /**
     * rate_ts
     * @var mixed
     */
    protected $rate_ts = null;

    /**
     * rate_change
     * @var mixed
     */
    protected $rate_change = null;

    /**
     * Set rate_id
     *
     * @param int $p_value
     *
     * @return \FreeFW\Model\Rate
     */
    public function setRateId($p_value)
    {
        $this->rate_id = $p_value;
        return $this;
    }

    /**
     * Get rate_id
     *
     * @return int
     */
    public function getRateId()
    {
        return $this->rate_id;
    }

    /**
     * Set rate_money_from
     *
     * @param string $p_value
     *
     * @return \FreeFW\Model\Rate
     */
    public function setRateMoneyFrom($p_value)
    {
        $this->rate_money_from = $p_value;
        return $this;
    }

    /**
     * Get rate_money_from
     *
     * @return string
     */
    public function getRateMoneyFrom()
    {
        return $this->rate_money_from;
    }

    /**
     * Set rate_money_to
     *
     * @param string $p_value
     *
     * @return \FreeFW\Model\Rate
     */
    public function setRateMoneyTo($p_value)
    {
        $this->rate_money_to = $p_value;
        return $this;
    }

    /**
     * Get rate_money_to
     *
     * @return string
     */
    public function getRateMoneyTo()
    {
        return $this->rate_money_to;
    }

    /**
     * Set rate_ts
     *
     * @param mixed $p_value
     *
     * @return \FreeFW\Model\Rate
     */
    public function setRateTs($p_value)
    {
        $this->rate_ts = $p_value;
        return $this;
    }

    /**
     * Get rate_ts
     *
     * @return mixed
     */
    public function getRateTs()
    {
        return $this->rate_ts;
    }

    /**
     * Set rate_change
     *
     * @param mixed $p_value
     *
     * @return \FreeFW\Model\Rate
     */
    public function setRateChange($p_value)
    {
        $this->rate_change = $p_value;
        return $this;
    }

    /**
     * Get rate_change
     *
     * @return mixed
     */
    public function getRateChange()
    {
        return $this->rate_change;
    }
}
