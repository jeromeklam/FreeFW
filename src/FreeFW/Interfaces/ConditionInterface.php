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
     * Set field
     *
     * @param string $p_field
     *
     * @return \FreeFW\Interfaces\ConditionInterface
     */
    public function setField($p_field);

    /**
     * get Field
     *
     * @return mixed
     */
    public function getField();
}
