<?php
namespace FreeFW\Model;

/**
 * Conditions
 *
 * @author jeromeklam
 */
class Conditions extends \FreeFW\Core\Model implements
    \FreeFW\Interfaces\ConditionInterface,
    \ArrayAccess,
    \Countable,
    \Iterator
{

    /**
     * Models
     * @var array
     */
    protected $conditions = [];

    /**
     * Count
     * @var number
     */
    protected $my_count = 0;

    /**
     * Total count
     * @var number
     */
    protected $total_count = 0;

    /**
     * Default operator
     * @var string
     */
    protected $operator = \FreeFW\Storage\Storage::COND_AND;

    /**
     * Constructor
     */
    public function initFromArray(array $p_conditions, string $p_oper = \FreeFW\Storage\Storage::COND_AND)
    {
        $this->operator   = $p_oper;
        $this->conditions = [];
        foreach ($p_conditions as $idx => $value) {
            if (strtolower($idx) == 'or' || strtolower($idx) == 'and') {
                $aCondition = \FreeFW\Model\Conditions::getNew();
                $aCondition->initFromArray($value, strtolower($idx));
            } else {
                // $idx must be a field...
                $aField = new \FreeFW\Model\ConditionMember();
                $aField->setValue($idx);
                /**
                 * @var \FreeFW\Model\SimpleCondition $aCondition
                 */
                $aCondition = \FreeFW\Model\SimpleCondition::getNew();
                $aCondition->setLeftMember($aField);
                if (is_array($value)) {
                    foreach ($value as $idx2 => $value2) {
                        // Verify oper...
                        $aCondition->setOperator($idx2);
                        if (is_array($value2)) {

                        } else {
                            $aValue = new \FreeFW\Model\ConditionValue();
                            $aValue->setValue($value2);
                            $aCondition->setRightMember($aValue);
                        }
                    }
                } else {
                    $aValue = new \FreeFW\Model\ConditionValue();
                    $aValue->setValue($value);
                    $aCondition->setRightMember($aValue);
                }
            }
            $this->conditions[] = $aCondition;
        }
        //var_dump($this->conditions);die;
    }

    /**
     * Get operator
     *
     * @return string
     */
    public function getOperator()
    {
        return $this->operator;
    }

    /**
     *
     * {@inheritDoc}
     * @see \FreeFW\Interfaces\ConditionInterface::getValue()
     */
    public function getValue()
    {
        return $this->conditions;
    }

    /**
     *
     * {@inheritDoc}
     * @see \FreeFW\Interfaces\ConditionInterface::setValue()
     */
    public function setValue($p_value)
    {
        $this->conditions = $p_value;
        return $this;
    }

    /**
     * Get total count
     *
     * @return number
     */
    public function getTotalCount()
    {
        return $this->total_count;
    }

    /**
     * Set total count
     *
     * @param number $p_count
     *
     * @return \FreeFW\Model\ResultSet
     */
    public function setTotalCount($p_count)
    {
        $this->total_count = $p_count;
        return $this;
    }

    /**
     *
     * {@inheritDoc}
     * @see \Iterator::rewind()
     */
    public function rewind()
    {
        reset($this->conditions);
    }

    /**
     *
     * {@inheritDoc}
     * @see \Iterator::current()
     */
    public function current()
    {
        $var = current($this->conditions);
        return $var;
    }

    /**
     *
     * {@inheritDoc}
     * @see \Iterator::key()
     */
    public function key()
    {
        $var = key($this->conditions);
        return $var;
    }

    /**
     *
     * {@inheritDoc}
     * @see \Iterator::next()
     */
    public function next()
    {
        $var = next($this->conditions);
        return $var;
    }

    /**
     *
     * {@inheritDoc}
     * @see \Iterator::valid()
     */
    public function valid()
    {
        $key = key($this->conditions);
        $var = ($key !== null && $key !== false);
        return $var;
    }

    /**
     *
     * {@inheritDoc}
     * @see \Countable::count()
     */
    public function count()
    {
        return $this->my_count;
    }

    /**
     *
     * @param \FreeFW\Core\Model $value
     *
     * @return \FreeFW\Model\ResultSet
     */
    public function add($p_value)
    {
        $this->conditions[] = $p_value;
        $this->my_count     = count($this->conditions);
        return $this;
    }

    /**
     *
     * {@inheritDoc}
     * @see \ArrayAccess::offsetSet()
     */
    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->conditions[] = $value;
        } else {
            $this->conditions[$offset] = $value;
        }
        $this->my_count = count($this->conditions);
    }

    /**
     *
     * {@inheritDoc}
     * @see \ArrayAccess::offsetExists()
     */
    public function offsetExists($offset)
    {
        return isset($this->conditions[$offset]);
    }

    /**
     *
     * {@inheritDoc}
     * @see \ArrayAccess::offsetUnset()
     */
    public function offsetUnset($offset)
    {
        unset($this->conditions[$offset]);
        $this->my_count = count($this->conditions);
    }

    /**
     *
     * {@inheritDoc}
     * @see \ArrayAccess::offsetGet()
     */
    public function offsetGet($offset)
    {
        return isset($this->conditions[$offset]) ? $this->conditions[$offset] : null;
    }

    /**
     * Empty ?
     *
     * @return boolean
     */
    public function isEmpty()
    {
        if ($this->my_count <= 0) {
            return true;
        }
        return false;
    }
}
