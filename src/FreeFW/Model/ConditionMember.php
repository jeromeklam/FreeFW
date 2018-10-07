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
     * @see \FreeFW\Interfaces\ConditionInterface::setField()
     */
    public function setField($p_field)
    {
        $this->field = $p_field;
        return $this;
    }

    /**
     *
     * {@inheritDoc}
     * @see \FreeFW\Interfaces\ConditionInterface::getField()
     */
    public function getField()
    {
        return $this->field;
    }
}
