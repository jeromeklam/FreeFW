<?php
namespace FreeFW\Model\Base;

/**
 * Rate
 *
 * @author jeromeklam
 */
abstract class PrintOptions extends \FreeFW\Model\Model\PrintOptions
{

    /**
     * prt_name
     * @var string
     */
    protected $prt_name = null;

    /**
     * prt_type
     * @var string
     */
    protected $prt_type = null;

    /**
     * edi_id
     * @var number
     */
    protected $edi_id = null;

    /**
     * email_id
     * @var number
     */
    protected $email_id = null;

    /**
     * Set prt_name
     *
     * @param string $p_value
     *
     * @return \FreeFW\Model\Base\PrintOptions
     */
    public function setPrtName($p_value)
    {
        $this->prt_name = $p_value;
        return $this;
    }

    /**
     * Get prt_name
     *
     * @return string
     */
    public function getPrtName()
    {
        return $this->prt_name;
    }

    /**
     * Set prt_type
     *
     * @param string $p_value
     *
     * @return \FreeFW\Model\Base\PrintOptions
     */
    public function setPrtType($p_value)
    {
        $this->prt_type = $p_value;
        return $this;
    }

    /**
     * Get prt_type
     *
     * @return string
     */
    public function getPrtType()
    {
        return $this->prt_type;
    }

    /**
     * Set edi_id
     *
     * @param number $p_value
     *
     * @return \FreeFW\Model\Base\PrintOptions
     */
    public function setEdiId($p_value)
    {
        $this->edi_id = $p_value;
        return $this;
    }

    /**
     * Get edi_id
     *
     * @return number
     */
    public function getEdiId()
    {
        return $this->edi_id;
    }

    /**
     * Set email_id
     *
     * @param number $p_value
     *
     * @return \FreeFW\Model\Base\PrintOptions
     */
    public function setEmailId($p_value)
    {
        $this->email_id = $p_value;
        return $this;
    }

    /**
     * Get email_id
     *
     * @return number
     */
    public function getEmailId()
    {
        return $this->email_id;
    }
}
