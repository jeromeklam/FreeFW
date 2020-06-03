<?php
namespace FreeFW\Model\Behaviour;

/**
 *
 * @author jeromeklam
 *
 */
trait Lang
{

    /**
     * Lang
     * @var \FreeFW\Model\Lang
     */
    protected $lang = null;

    /**
     * LangId
     * @var number
     */
    protected $lang_id = null;

    /**
     * Set lang
     *
     * @param \FreeFW\Model\Lang $p_lang
     *
     * @return \FreeFW\Core\Model
     */
    public function setLang($p_lang)
    {
        $this->lang = $p_lang;
        if ($this->lang instanceof \FreeFW\Model\Lang) {
            $this->setLangId($this->lang->getLangId());
        } else {
            $this->setLangId(null);
        }
        return $this;
    }

    /**
     * Get lang
     *
     * @return \FreeFW\Model\Lang
     */
    public function getLang()
    {
        if ($this->lang === null) {
            if ($this->lang_id > 0) {
                $this->lang = \FreeFW\Model\Lang::findFirst(['lang_id' => $this->lang_id]);
            }
        }
        return $this->lang;
    }

    /**
     * Set lang id
     *
     * @param number $p_id
     *
     * @return \FreeFW\Model\Behaviour\Lang
     */
    public function setLangId($p_id)
    {
        $this->lang_id = $p_id;
        if ($this->lang !== null) {
            if ($this->lang_id != $this->lang->getLangId()) {
                $this->lang = null;
            }
        }
        return $this;
    }

    /**
     * Get lang id
     *
     * @return number
     */
    public function getLangId()
    {
        return $this->lang_id;
    }
}
