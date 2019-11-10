<?php
namespace FreeFW\Interfaces;

/**
 * Condition interface
 *
 * @author jeromeklam
 */
interface ConditionInterface
{

    /**
     * Set value
     *
     * @param mixed $p_value
     *
     * @return \FreeFW\Interfaces\ConditionInterface
     */
    public function setValue($p_value);

    /**
     * get value
     *
     * @return mixed
     */
    public function getValue();
}
