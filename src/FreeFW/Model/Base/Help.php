<?php
namespace FreeFW\Model\Base;

/**
 * Help
 *
 * @author jeromeklam
 */
abstract class Help extends \FreeFW\Model\StorageModel\Help
{

    /**
     * help_id
     * @var int
     */
    protected $help_id = null;

    /**
     * help_name
     * @var string
     */
    protected $help_name = null;

    /**
     * help_desc
     * @var mixed
     */
    protected $help_desc = null;

    /**
     * help_position
     * @var int
     */
    protected $help_position = null;

    /**
     * help_type
     * @var string
     */
    protected $help_type = null;

    /**
     * help_content
     * @var mixed
     */
    protected $help_content = null;

    /**
     * help_scope
     * @var mixed
     */
    protected $help_scope = null;

    /**
     * Set help_id
     *
     * @param int $p_value
     *
     * @return \FreeFW\Model\Help
     */
    public function setHelpId($p_value)
    {
        $this->help_id = $p_value;
        return $this;
    }

    /**
     * Get help_id
     *
     * @return int
     */
    public function getHelpId()
    {
        return $this->help_id;
    }

    /**
     * Set help_name
     *
     * @param string $p_value
     *
     * @return \FreeFW\Model\Help
     */
    public function setHelpName($p_value)
    {
        $this->help_name = $p_value;
        return $this;
    }

    /**
     * Get help_name
     *
     * @return string
     */
    public function getHelpName()
    {
        return $this->help_name;
    }

    /**
     * Set help_desc
     *
     * @param mixed $p_value
     *
     * @return \FreeFW\Model\Help
     */
    public function setHelpDesc($p_value)
    {
        $this->help_desc = $p_value;
        return $this;
    }

    /**
     * Get help_desc
     *
     * @return mixed
     */
    public function getHelpDesc()
    {
        return $this->help_desc;
    }

    /**
     * Set help_position
     *
     * @param int $p_value
     *
     * @return \FreeFW\Model\Help
     */
    public function setHelpPosition($p_value)
    {
        $this->help_position = $p_value;
        return $this;
    }

    /**
     * Get help_position
     *
     * @return int
     */
    public function getHelpPosition()
    {
        return $this->help_position;
    }

    /**
     * Set help_type
     *
     * @param string $p_value
     *
     * @return \FreeFW\Model\Help
     */
    public function setHelpType($p_value)
    {
        $this->help_type = $p_value;
        return $this;
    }

    /**
     * Get help_type
     *
     * @return string
     */
    public function getHelpType()
    {
        return $this->help_type;
    }

    /**
     * Set help_content
     *
     * @param mixed $p_value
     *
     * @return \FreeFW\Model\Help
     */
    public function setHelpContent($p_value)
    {
        $this->help_content = $p_value;
        return $this;
    }

    /**
     * Get help_content
     *
     * @return mixed
     */
    public function getHelpContent()
    {
        return $this->help_content;
    }

    /**
     * Set help_scope
     *
     * @param mixed $p_value
     *
     * @return \FreeFW\Model\Help
     */
    public function setHelpScope($p_value)
    {
        $this->help_scope = $p_value;
        return $this;
    }

    /**
     * Get help_scope
     *
     * @return mixed
     */
    public function getHelpScope()
    {
        return $this->help_scope;
    }
}
