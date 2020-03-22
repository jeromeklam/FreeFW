<?php
namespace FreeFW\Model;

use \FreeFW\Constants as FFCST;

/**
 * Email
 *
 * @author jeromeklam
 */
class Email extends \FreeFW\Model\Base\Email implements
    \FreeFW\Interfaces\ApiResponseInterface
{

    /**
     * Lang
     * @var \FreeFW\Model\Lang
     */
    protected $lang = null;

    /**
     *
     * {@inheritDoc}
     * @see \FreeFW\Core\Model::init()
     */
    public function init()
    {
        $this->email_id = 0;
        $this->brk_id   = 0;
        $this->lang_id  = 0;
        return $this;
    }

    /**
     * Set lang
     * 
     * @param \FreeFW\Model\Lang $p_lang
     * 
     * @return \FreeFW\Model\Email
     */
    public function setLang($p_lang)
    {
        $this->lang = $p_lang;
        return $this;
    }

    /**
     * Get lang
     * 
     * @return \FreeFW\Model\Lang
     */
    public function getLang()
    {
        return $this->lang;
    }

    /**
     * Merge datas in fields
     * 
     * @param array $p_datas
     */
    public function mergeDatas($p_datas)
    {
        $this->email_subject = \FreeFW\Tools\PBXString::parse($this->email_subject, $p_datas);
        $this->email_body    = \FreeFW\Tools\PBXString::parse($this->email_body, $p_datas);
    }
}
