<?php
namespace FreeFW\Model\Base;

/**
 * Lang
 *
 * @author jeromeklam
 */
abstract class Lang extends \FreeFW\Model\StorageModel\Lang
{

    /**
     * lang_id
     * @var int
     */
    protected $lang_id = null;

    /**
     * lang_name
     * @var string
     */
    protected $lang_name = null;

    /**
     * lang_code
     * @var string
     */
    protected $lang_code = null;

    /**
     * lang_iso
     * @var string
     */
    protected $lang_iso = null;

    /**
     * lang_flag
     * @var string
     */
    protected $lang_flag = null;

    /**
     * Set lang_id
     *
     * @param int $p_value
     *
     * @return \FreeFW\Model\Lang
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
     * Set lang_name
     *
     * @param string $p_value
     *
     * @return \FreeFW\Model\Lang
     */
    public function setLangName($p_value)
    {
        $this->lang_name = $p_value;
        return $this;
    }

    /**
     * Get lang_name
     *
     * @return string
     */
    public function getLangName()
    {
        return $this->lang_name;
    }

    /**
     * Set lang_code
     *
     * @param string $p_value
     *
     * @return \FreeFW\Model\Lang
     */
    public function setLangCode($p_value)
    {
        $this->lang_code = $p_value;
        return $this;
    }

    /**
     * Get lang_code
     *
     * @return string
     */
    public function getLangCode()
    {
        return $this->lang_code;
    }

    /**
     * Set lang_iso
     *
     * @param string $p_value
     *
     * @return \FreeFW\Model\Lang
     */
    public function setLangIso($p_value)
    {
        $this->lang_iso = $p_value;
        return $this;
    }

    /**
     * Get lang_iso
     *
     * @return string
     */
    public function getLangIso()
    {
        return $this->lang_iso;
    }

    /**
     * Set lang_flag
     *
     * @param string $p_value
     *
     * @return \FreeFW\Model\Lang
     */
    public function setLangFlag($p_value)
    {
        $this->lang_flag = $p_value;
        return $this;
    }

    /**
     * Get lang_flag
     *
     * @return string
     */
    public function getLangFlag()
    {
        return $this->lang_flag;
    }
}
