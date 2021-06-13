<?php
namespace FreeFW\Model\Base;

/**
 * EditionLang
 *
 * @author jeromeklam
 */
abstract class EditionLang extends \FreeFW\Model\StorageModel\EditionLang
{

    /**
     * edil_id
     * @var int
     */
    protected $edil_id = null;

    /**
     * edi_id
     * @var int
     */
    protected $edi_id = null;

    /**
     * lang_id
     * @var int
     */
    protected $lang_id = null;

    /**
     * edil_data
     * @var mixed
     */
    protected $edil_data = null;

    /**
     * edil_filename
     * @var string
     */
    protected $edil_filename = null;

    /**
     * Set edil_id
     *
     * @param int $p_value
     *
     * @return \FreeFW\Model\EditionLang
     */
    public function setEdilId($p_value)
    {
        $this->edil_id = $p_value;
        return $this;
    }

    /**
     * Get edil_id
     *
     * @return int
     */
    public function getEdilId()
    {
        return $this->edil_id;
    }

    /**
     * Set edi_id
     *
     * @param int $p_value
     *
     * @return \FreeFW\Model\EditionLang
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
     * Set lang_id
     *
     * @param int $p_value
     *
     * @return \FreeFW\Model\EditionLang
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
     * Set edil_data
     *
     * @param mixed $p_value
     *
     * @return \FreeFW\Model\EditionLang
     */
    public function setEdilData($p_value)
    {
        $this->edil_data = $p_value;
        return $this;
    }

    /**
     * Get edil_data
     *
     * @return mixed
     */
    public function getEdilData()
    {
        return $this->edil_data;
    }

    /**
     * Set edil_filename
     *
     * @param string $p_value
     *
     * @return \FreeFW\Model\Base\EditionLang
     */
    public function setEdilFilename($p_value)
    {
        $this->edil_filename = $p_value;
        return $this;
    }

    /**
     * Get edil_filename
     *
     * @return string
     */
    public function getEdilFilename()
    {
        return $this->edil_filename;
    }
}
