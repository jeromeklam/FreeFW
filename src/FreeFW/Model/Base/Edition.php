<?php
namespace FreeFW\Model\Base;

/**
 * Edition
 *
 * @author jeromeklam
 */
abstract class Edition extends \FreeFW\Model\StorageModel\Edition
{

    /**
     * edi_id
     * @var int
     */
    protected $edi_id = null;

    /**
     * brk_id
     * @var int
     */
    protected $brk_id = null;

    /**
     * grp_id
     * @var int
     */
    protected $grp_id = null;

    /**
     * lang_id
     * @var int
     */
    protected $lang_id = null;

    /**
     * edi_object_name
     * @var string
     */
    protected $edi_object_name = null;

    /**
     * edi_object_id
     * @var int
     */
    protected $edi_object_id = null;

    /**
     * edi_ts
     * @var mixed
     */
    protected $edi_ts = null;

    /**
     * edi_name
     * @var string
     */
    protected $edi_name = null;

    /**
     * edi_desc
     * @var mixed
     */
    protected $edi_desc = null;

    /**
     * edi_data
     * @var mixed
     */
    protected $edi_data = null;

    /**
     * edi_type
     * @var string
     */
    protected $edi_type = null;

    /**
     * edi_mode
     * @var string
     */
    protected $edi_mode = null;

    /**
     * edi_duration
     * @var int
     */
    protected $edi_duration = null;

    /**
     * Set edi_id
     *
     * @param int $p_value
     *
     * @return \FreeFW\Model\Edition
     */
    public function setEdiId($p_value)
    {
        $this->edi_id = $p_value;
        return $this;
    }

    /**
     * Get edi_id
     *
     * @return int
     */
    public function getEdiId()
    {
        return $this->edi_id;
    }

    /**
     * Set brk_id
     *
     * @param int $p_value
     *
     * @return \FreeFW\Model\Edition
     */
    public function setBrkId($p_value)
    {
        $this->brk_id = $p_value;
        return $this;
    }

    /**
     * Get brk_id
     *
     * @return int
     */
    public function getBrkId()
    {
        return $this->brk_id;
    }

    /**
     * Set grp_id
     *
     * @param int $p_value
     *
     * @return \FreeFW\Model\Edition
     */
    public function setGrpId($p_value)
    {
        $this->grp_id = $p_value;
        return $this;
    }

    /**
     * Get grp_id
     *
     * @return int
     */
    public function getGrpId()
    {
        return $this->grp_id;
    }

    /**
     * Set lang_id
     *
     * @param int $p_value
     *
     * @return \FreeFW\Model\Edition
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
     * Set edi_object_name
     *
     * @param string $p_value
     *
     * @return \FreeFW\Model\Edition
     */
    public function setEdiObjectName($p_value)
    {
        $this->edi_object_name = $p_value;
        return $this;
    }

    /**
     * Get edi_object_name
     *
     * @return string
     */
    public function getEdiObjectName()
    {
        return $this->edi_object_name;
    }

    /**
     * Set edi_object_id
     *
     * @param int $p_value
     *
     * @return \FreeFW\Model\Edition
     */
    public function setEdiObjectId($p_value)
    {
        $this->edi_object_id = $p_value;
        return $this;
    }

    /**
     * Get edi_object_id
     *
     * @return int
     */
    public function getEdiObjectId()
    {
        return $this->edi_object_id;
    }

    /**
     * Set edi_ts
     *
     * @param mixed $p_value
     *
     * @return \FreeFW\Model\Edition
     */
    public function setEdiTs($p_value)
    {
        $this->edi_ts = $p_value;
        return $this;
    }

    /**
     * Get edi_ts
     *
     * @return mixed
     */
    public function getEdiTs()
    {
        return $this->edi_ts;
    }

    /**
     * Set edi_name
     *
     * @param string $p_value
     *
     * @return \FreeFW\Model\Edition
     */
    public function setEdiName($p_value)
    {
        $this->edi_name = $p_value;
        return $this;
    }

    /**
     * Get edi_name
     *
     * @return string
     */
    public function getEdiName()
    {
        return $this->edi_name;
    }

    /**
     * Set edi_desc
     *
     * @param mixed $p_value
     *
     * @return \FreeFW\Model\Edition
     */
    public function setEdiDesc($p_value)
    {
        $this->edi_desc = $p_value;
        return $this;
    }

    /**
     * Get edi_desc
     *
     * @return mixed
     */
    public function getEdiDesc()
    {
        return $this->edi_desc;
    }

    /**
     * Set edi_data
     *
     * @param mixed $p_value
     *
     * @return \FreeFW\Model\Edition
     */
    public function setEdiData($p_value)
    {
        $this->edi_data = $p_value;
        return $this;
    }

    /**
     * Get edi_data
     *
     * @return mixed
     */
    public function getEdiData()
    {
        return $this->edi_data;
    }

    /**
     * Set edi_type
     *
     * @param string $p_value
     *
     * @return \FreeFW\Model\Edition
     */
    public function setEdiType($p_value)
    {
        $this->edi_type = $p_value;
        return $this;
    }

    /**
     * Get edi_type
     *
     * @return string
     */
    public function getEdiType()
    {
        return $this->edi_type;
    }

    /**
     * Set edi_mode
     *
     * @param string $p_value
     *
     * @return \FreeFW\Model\Edition
     */
    public function setEdiMode($p_value)
    {
        $this->edi_mode = $p_value;
        return $this;
    }

    /**
     * Get edi_mode
     *
     * @return string
     */
    public function getEdiMode()
    {
        return $this->edi_mode;
    }

    /**
     * Set edi_duration
     *
     * @param int $p_value
     *
     * @return \FreeFW\Model\Edition
     */
    public function setEdiDuration($p_value)
    {
        $this->edi_duration = $p_value;
        return $this;
    }

    /**
     * Get edi_duration
     *
     * @return int
     */
    public function getEdiDuration()
    {
        return $this->edi_duration;
    }
}
