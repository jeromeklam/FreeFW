<?php
namespace FreeFW\Http;

/**
 * Cookie
 *
 * @author jeromeklam
 */
class Cookie
{

    /**
     * Name
     * @var string
     */
    protected $name = null;

    /**
     * Value
     * @var mixed
     */
    protected $value = null;

    /**
     * Constructor
     *
     * @param string $p_name
     * @param mixed  $p_value
     */
    public function __construct(string $p_name, $p_value)
    {
        $this->name  = $p_name;
        $this->value = $p_value;
    }

    /**
     * Set name
     *
     * @param string $p_name
     *
     * @return \FreeFW\Http\Cookie
     */
    public function setName(string $p_name)
    {
        $this->name = $p_name;
        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set value
     *
     * @param mixed $p_value
     *
     * @return \FreeFW\Http\Cookie
     */
    public function setValue($p_value)
    {
        $this->value = $p_value;
        return $this;
    }

    /**
     * Get value
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }
}
