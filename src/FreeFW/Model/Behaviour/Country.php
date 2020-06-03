<?php
namespace FreeFW\Model\Behaviour;

/**
 *
 * @author jeromeklam
 *
 */
trait Country
{

    /**
     * Country
     * @var \FreeFW\Model\Country
     */
    protected $cnty = null;

    /**
     * CountryId
     * @var number
     */
    protected $cnty_id = null;

    /**
     * Set cnty
     *
     * @param \FreeFW\Model\Country $p_cnty
     *
     * @return \FreeFW\Core\Model
     */
    public function setCountry($p_cnty)
    {
        $this->cnty = $p_cnty;
        if ($this->cnty instanceof \FreeFW\Model\Country) {
            $this->setCntyId($this->cnty->getCntyId());
        } else {
            $this->setCntyId(null);
        }
        return $this;
    }

    /**
     * Get cnty
     *
     * @return \FreeFW\Model\Country
     */
    public function getCountry()
    {
        if ($this->cnty === null) {
            if ($this->cnty_id > 0) {
                $this->cnty = \FreeFW\Model\Country::findFirst(['cnty_id' => $this->cnty_id]);
            }
        }
        return $this->cnty;
    }

    /**
     * Set cnty id
     *
     * @param number $p_id
     *
     * @return \FreeFW\Model\Behaviour\Country
     */
    public function setCntyId($p_id)
    {
        $this->cnty_id = $p_id;
        if ($this->cnty !== null) {
            if ($this->cnty_id != $this->cnty->getCntyId()) {
                $this->cnty = null;
            }
        }
        return $this;
    }

    /**
     * Get cnty id
     *
     * @return number
     */
    public function getCntyId()
    {
        return $this->cnty_id;
    }
}
