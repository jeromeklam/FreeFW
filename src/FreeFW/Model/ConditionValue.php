<?php
namespace FreeFW\Model;

/**
 *
 * @author jeromeklam
 *
 */
class ConditionValue implements \FreeFW\Interfaces\ConditionInterface
{

    /**
     * field value
     * @var string
     */
    protected $value = null;

    /**
     *
     * {@inheritDoc}
     * @see \FreeFW\Interfaces\ConditionInterface::setValue()
     */
    public function setValue($p_value)
    {
        $this->value = $p_value;
        return $this;
    }

    /**
     *
     * {@inheritDoc}
     * @see \FreeFW\Interfaces\ConditionInterface::getValue()
     */
    public function getValue()
    {
        return $this->value;
    }
    
    /**
     * 
     * @return unknown|string
     */
    public function __toString()
    {
        if (!is_array($this->value)) {
            return $this->value;
        }
        return implode(',', $this->value);
    }
}
