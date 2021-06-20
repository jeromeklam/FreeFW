<?php
namespace FreeFW\Model;

/**
 *
 * @author jeromeklam
 *
 */
class ConditionMember implements \FreeFW\Interfaces\ConditionInterface
{

    /**
     * field name
     * @var string
     */
    protected $field = null;

    /**
     *
     * {@inheritDoc}
     * @see \FreeFW\Interfaces\ConditionInterface::setValue()
     */
    public function setValue($p_value)
    {
        $this->field = $p_value;
        return $this;
    }

    /**
     *
     * {@inheritDoc}
     * @see \FreeFW\Interfaces\ConditionInterface::getValue()
     */
    public function getValue()
    {
        return $this->field;
    }
    
    /**
     * 
     * @return string
     */
    public function __toString()
    {
        return $this->field;
    }
}
