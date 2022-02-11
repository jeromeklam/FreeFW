<?php
namespace FreeFW\Model\Base;

/**
 * Template
 *
 * @author jeromeklam
 */
abstract class Template extends \FreeFW\Model\StorageModel\Template
{

    /**
     * tpl_id
     * @var int
     */
    protected $tpl_id = null;

    /**
     * tpl_name
     * @var string
     */
    protected $tpl_name = null;

    /**
     * tpl_desc
     * @var mixed
     */
    protected $tpl_desc = null;

    /**
     * tpl_content
     * @var mixed
     */
    protected $tpl_content = null;

    /**
     * Set tpl_id
     *
     * @param int $p_value
     *
     * @return \FreeFW\Model\Template
     */
    public function setTplId($p_value)
    {
        $this->tpl_id = $p_value;
        return $this;
    }

    /**
     * Get tpl_id
     *
     * @return int
     */
    public function getTplId()
    {
        return $this->tpl_id;
    }

    /**
     * Set tpl_name
     *
     * @param string $p_value
     *
     * @return \FreeFW\Model\Template
     */
    public function setTplName($p_value)
    {
        $this->tpl_name = $p_value;
        return $this;
    }

    /**
     * Get tpl_name
     *
     * @return string
     */
    public function getTplName()
    {
        return $this->tpl_name;
    }

    /**
     * Set tpl_desc
     *
     * @param mixed $p_value
     *
     * @return \FreeFW\Model\Template
     */
    public function setTplDesc($p_value)
    {
        $this->tpl_desc = $p_value;
        return $this;
    }

    /**
     * Get tpl_desc
     *
     * @return mixed
     */
    public function getTplDesc()
    {
        return $this->tpl_desc;
    }

    /**
     * Set tpl_content
     *
     * @param mixed $p_value
     *
     * @return \FreeFW\Model\Template
     */
    public function setTplContent($p_value)
    {
        $this->tpl_content = $p_value;
        return $this;
    }

    /**
     * Get tpl_content
     *
     * @return mixed
     */
    public function getTplContent()
    {
        return $this->tpl_content;
    }
}
