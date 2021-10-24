<?php
namespace FreeFW\Model\Base;

/**
 * Translation
 *
 * @author jeromeklam
 */
abstract class Translation extends \FreeFW\Model\StorageModel\Translation
{

    /**
     * tr_id
     * @var int
     */
    protected $tr_id = null;

    /**
     * tr_key
     * @var string
     */
    protected $tr_key = null;

    /**
     * tr_desc
     * @var mixed
     */
    protected $tr_desc = null;

    /**
     * tr_html
     * @var bool
     */
    protected $tr_html = null;

    /**
     * tr_type
     * @var string
     */
    protected $tr_type = null;

    /**
     * tr_lang_fr
     * @var mixed
     */
    protected $tr_lang_fr = null;

    /**
     * tr_lang_en
     * @var mixed
     */
    protected $tr_lang_en = null;

    /**
     * tr_lang_ch
     * @var mixed
     */
    protected $tr_lang_ch = null;

    /**
     * tr_lang_de
     * @var mixed
     */
    protected $tr_lang_de = null;

    /**
     * tr_lang_es
     * @var mixed
     */
    protected $tr_lang_es = null;

    /**
     * tr_lang_id
     * @var mixed
     */
    protected $tr_lang_id = null;

    /**
     * Set tr_id
     *
     * @param int $p_value
     *
     * @return \FreeFW\Model\Translation
     */
    public function setTrId($p_value)
    {
        $this->tr_id = $p_value;
        return $this;
    }

    /**
     * Get tr_id
     *
     * @return int
     */
    public function getTrId()
    {
        return $this->tr_id;
    }

    /**
     * Set tr_key
     *
     * @param string $p_value
     *
     * @return \FreeFW\Model\Translation
     */
    public function setTrKey($p_value)
    {
        $this->tr_key = $p_value;
        return $this;
    }

    /**
     * Get tr_key
     *
     * @return string
     */
    public function getTrKey()
    {
        return $this->tr_key;
    }

    /**
     * Set tr_desc
     *
     * @param mixed $p_value
     *
     * @return \FreeFW\Model\Translation
     */
    public function setTrDesc($p_value)
    {
        $this->tr_desc = $p_value;
        return $this;
    }

    /**
     * Get tr_desc
     *
     * @return mixed
     */
    public function getTrDesc()
    {
        return $this->tr_desc;
    }

    /**
     * Set tr_html
     *
     * @param bool $p_value
     *
     * @return \FreeFW\Model\Translation
     */
    public function setTrHtml($p_value)
    {
        $this->tr_html = $p_value;
        return $this;
    }

    /**
     * Get tr_html
     *
     * @return bool
     */
    public function getTrHtml()
    {
        return $this->tr_html;
    }

    /**
     * Set tr_type
     *
     * @param string $p_value
     *
     * @return \FreeFW\Model\Translation
     */
    public function setTrType($p_value)
    {
        $this->tr_type = $p_value;
        return $this;
    }

    /**
     * Get tr_type
     *
     * @return string
     */
    public function getTrType()
    {
        return $this->tr_type;
    }

    /**
     * Set tr_lang_fr
     *
     * @param mixed $p_value
     *
     * @return \FreeFW\Model\Translation
     */
    public function setTrLangFr($p_value)
    {
        $this->tr_lang_fr = $p_value;
        return $this;
    }

    /**
     * Get tr_lang_fr
     *
     * @return mixed
     */
    public function getTrLangFr()
    {
        return $this->tr_lang_fr;
    }

    /**
     * Set tr_lang_en
     *
     * @param mixed $p_value
     *
     * @return \FreeFW\Model\Translation
     */
    public function setTrLangEn($p_value)
    {
        $this->tr_lang_en = $p_value;
        return $this;
    }

    /**
     * Get tr_lang_en
     *
     * @return mixed
     */
    public function getTrLangEn()
    {
        return $this->tr_lang_en;
    }

    /**
     * Set tr_lang_ch
     *
     * @param mixed $p_value
     *
     * @return \FreeFW\Model\Translation
     */
    public function setTrLangCh($p_value)
    {
        $this->tr_lang_ch = $p_value;
        return $this;
    }

    /**
     * Get tr_lang_ch
     *
     * @return mixed
     */
    public function getTrLangCh()
    {
        return $this->tr_lang_ch;
    }

    /**
     * Set tr_lang_de
     *
     * @param mixed $p_value
     *
     * @return \FreeFW\Model\Translation
     */
    public function setTrLangDe($p_value)
    {
        $this->tr_lang_de = $p_value;
        return $this;
    }

    /**
     * Get tr_lang_de
     *
     * @return mixed
     */
    public function getTrLangDe()
    {
        return $this->tr_lang_de;
    }

    /**
     * Set tr_lang_es
     *
     * @param mixed $p_value
     *
     * @return \FreeFW\Model\Translation
     */
    public function setTrLangEs($p_value)
    {
        $this->tr_lang_es = $p_value;
        return $this;
    }

    /**
     * Get tr_lang_es
     *
     * @return mixed
     */
    public function getTrLangEs()
    {
        return $this->tr_lang_es;
    }

    /**
     * Set tr_lang_id
     *
     * @param mixed $p_value
     *
     * @return \FreeFW\Model\Translation
     */
    public function setTrLangId($p_value)
    {
        $this->tr_lang_id = $p_value;
        return $this;
    }

    /**
     * Get tr_lang_id
     *
     * @return mixed
     */
    public function getTrLangId()
    {
        return $this->tr_lang_id;
    }
}
