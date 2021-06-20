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
     * Get simple condition
     * 
     * @param string $p_operator
     * @param string $p_value
     * 
     * @return mixed
     */
    protected function getNPICondition($p_operator, $p_value)
    {
        $value = trim($p_value);
        if (substr($value, 0, 1) == '(') {
            $content = substr($value, 1, -1);
            $parts   = explode(',', $content);
            $field   = $parts[0];
            $aField  = new \FreeFW\Model\ConditionMember();
            $aField->setValue($field);
            $aCondition = new \FreeFW\Model\SimpleCondition();
            $aCondition->setLeftMember($aField);
            $aCondition->setOperator($p_operator);
            array_shift($parts);
            if (count($parts) == 0) {
                if ($p_operator == \FreeFW\Storage\Storage::COND_EMPTY) {
                    $aValue = new \FreeFW\Model\ConditionValue();
                    $aValue->setValue(null);
                    $aCondition->setRightMember($aValue);
                } else {
                    throw new \Exception(sprintf('%s operator must provide a value', $p_operator));
                }
            } else {
                if ($p_operator == \FreeFW\Storage\Storage::COND_EMPTY) {
                    throw new \Exception(sprintf('%s operator cannot provide a value', $p_operator));
                }
                if (count($parts) == 1) {
                    $aValue = new \FreeFW\Model\ConditionValue();
                    $val = trim(trim($parts[0], '\''));
                    $aValue->setValue($val);
                    $aCondition->setRightMember($aValue);
                } else {
                    $aValue = new \FreeFW\Model\ConditionValue();
                    $aValue->setValue($parts);
                    $aCondition->setRightMember($aValue);
                }
            }
            return $aCondition;
        } else {
            $pos = strpos($value, '(');
            $oper = substr($value, 0, $pos);
            $right = substr($value, $pos);
            $right = substr($right, 0, -1);
            $aConditions = new \FreeFW\Model\Conditions();
            return $aConditions->initFromNPIArray([$oper => $right]);
        }
    }
    
    /**
     * NPI filter
     * 
     * @param array   $p_conditions
     * @param string  $p_operator
     * @param bool    $p_not
     * 
     * @throws \Exception
     */
    public function initFromNPIArray(array $p_conditions, string $p_operator = null, bool $p_not = false)
    {
        $operator = $p_operator;
        if (!in_array(
            $operator,
            [
                \FreeFW\Storage\Storage::COND_AND,
                \FreeFW\Storage\Storage::COND_OR,
                \FreeFW\Storage\Storage::COND_NOT
                
            ])) {
            $operator = \FreeFW\Storage\Storage::COND_AND;
        }
        $this->operator   = $operator;
        $this->conditions = [];
        foreach ($p_conditions as $index => $value) {
            $simpleCond = null;
            switch(strtoupper($index)) {
                case 'NOT':
                case 'AND':
                case 'OR':
                    $aCondition = new \FreeFW\Model\Conditions();
                    $aCondition->initFromNPIArray($value, strtolower($index));
                    break;
                case 'EQUALS':
                    $simpleCond = \FreeFW\Storage\Storage::COND_EQUAL;
                    break;
                case 'EQUALSORNULL':
                    $simpleCond = \FreeFW\Storage\Storage::COND_EQUAL_OR_NULL;
                    break;
                case 'LESSTHAN':
                    $simpleCond = \FreeFW\Storage\Storage::COND_LOWER;
                    break;
                case 'LESSTHANORNULL':
                    $simpleCond = \FreeFW\Storage\Storage::COND_LOWER_OR_NULL;
                    break;
                case 'LESSOREQUAL':
                    $simpleCond = \FreeFW\Storage\Storage::COND_LOWER_EQUAL;
                    break;
                case 'LESSOREQUALORNULL':
                    $simpleCond = \FreeFW\Storage\Storage::COND_LOWER_EQUAL_OR_NULL;
                    break;
                case 'GREATERTHAN':
                    $simpleCond = \FreeFW\Storage\Storage::COND_GREATER;
                    break;
                case 'GREATERTHANORNULL':
                    $simpleCond = \FreeFW\Storage\Storage::COND_GREATER_OR_NULL;
                    break;
                case 'GREATEROREQUAL':
                    $simpleCond = \FreeFW\Storage\Storage::COND_GREATER_EQUAL;
                    break;
                case 'GREATEROREQUALORNULL':
                    $simpleCond = \FreeFW\Storage\Storage::COND_GREATER_EQUAL_OR_NULL;
                    break;
                case 'CONTAINS':
                    $simpleCond = \FreeFW\Storage\Storage::COND_LIKE;
                    break;
                case 'CONTAINSORNULL':
                    $simpleCond = \FreeFW\Storage\Storage::COND_LIKE_OR_NULL;
                    break;
                case 'STARTSWITH':
                    $simpleCond = \FreeFW\Storage\Storage::COND_BEGIN_WITH;
                    break;
                case 'ENDSWITH':
                    $simpleCond = \FreeFW\Storage\Storage::COND_END_WITH;
                    break;
                case 'ISNULL':
                    $simpleCond = \FreeFW\Storage\Storage::COND_EMPTY;
                    break;
                case 'BETWEEN':
                    $simpleCond = \FreeFW\Storage\Storage::COND_BETWEEN;
                    break;
                case 'ANY':
                    $simpleCond = \FreeFW\Storage\Storage::COND_IN;
                    break;
                case 'HAS':
                    break;
                default:
                    // $index must be a field...
                    $aField = new \FreeFW\Model\ConditionMember();
                    $aField->setValue($index);
                    /**
                     * @var \FreeFW\Model\SimpleCondition $aCondition
                     */
                    $aCondition = new \FreeFW\Model\SimpleCondition();
                    $aCondition->setLeftMember($aField);
                    if (is_array($value)) {
                        foreach ($value as $idx2 => $value2) {
                            // Verify oper...
                            if (is_array($value2)) {
                                $aValueArr = new \FreeFW\Model\ConditionValue();
                                $aValueArr->setValue($value2);
                                $aCondition->setOperator($idx2);
                                $aCondition->setRightMember($aValueArr);
                            } else {
                                if ($value === null || $value === '') {
                                    if (in_array($idx2, [\FreeFW\Storage\Storage::COND_EMPTY, \FreeFW\Storage\Storage::COND_NOT_EMPTY])) {
                                        $aCondition->setOperator($idx2);
                                        $aCondition->setRightMember(null);
                                    } else {
                                        continue;
                                    }
                                } else {
                                    $aValue = new \FreeFW\Model\ConditionValue();
                                    $aValue->setValue($value2);
                                    $aCondition->setOperator($idx2);
                                    $aCondition->setRightMember($aValue);
                                }
                            }
                        }
                    } else {
                        $aValue = new \FreeFW\Model\ConditionValue();
                        $aValue->setValue($value);
                        $aCondition->setRightMember($aValue);
                    }
                    break;
            }
            if ($simpleCond) {
                if (!is_array($value)) {
                    $aCondition = $this->getNPICondition($simpleCond, $value);
                    $this->conditions[] = $aCondition;
                } else {
                    foreach ($value as $oneValue) {
                        $aCondition = $this->getNPICondition($simpleCond, $oneValue);
                        $this->conditions[] = $aCondition;
                    }
                }
            } else {
                $this->conditions[] = $aCondition;
            }
        }
    }
    
    /**
     * Constructor
     */
    public function initFromArray(
        array $p_conditions,
        string $p_oper = \FreeFW\Storage\Storage::COND_AND,
        string $p_cond = \FreeFW\Storage\Storage::COND_EQUAL
    ) {
        $this->operator   = $p_oper;
        $this->conditions = [];
        foreach ($p_conditions as $idx => $value) {
            if (strtolower($idx) == 'or' || strtolower($idx) == 'and') {
                $aCondition = new \FreeFW\Model\Conditions();
                $aCondition->initFromArray($value, strtolower($idx));
            } else {
                // $idx must be a field...
                $aField = new \FreeFW\Model\ConditionMember();
                $aField->setValue($idx);
                /**
                 * @var \FreeFW\Model\SimpleCondition $aCondition
                 */
                $aCondition = new \FreeFW\Model\SimpleCondition();
                $aCondition->setLeftMember($aField);
                $aCondition->setOperator($p_cond);
                if (is_array($value)) {
                    foreach ($value as $idx2 => $value2) {
                        // Verify oper...
                        if (is_array($value2)) {
                            $aValueArr = new \FreeFW\Model\ConditionValue();
                            $aValueArr->setValue($value2);
                            $aCondition->setOperator($idx2);
                            $aCondition->setRightMember($aValueArr);
                        } else {
                            if ($value === null || $value === '') {
                                if (in_array($idx2, [\FreeFW\Storage\Storage::COND_EMPTY, \FreeFW\Storage\Storage::COND_NOT_EMPTY])) {
                                    $aCondition->setOperator($idx2);
                                    $aCondition->setRightMember(null);
                                } else {
                                    continue;
                                }
                            } else {
                                $aValue = new \FreeFW\Model\ConditionValue();
                                $aValue->setValue($value2);
                                $aCondition->setOperator($idx2);
                                $aCondition->setRightMember($aValue);
                            }
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
     * Set operator
     *
     * @param string $p_oper
     *
     * @return \FreeFW\Model\Conditions
     */
    public function setOperator($p_oper)
    {
        $this->operator = $p_oper;
        return $this;
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
     * @param \FreeFW\Interfaces\ConditionInterface $value
     *
     * @return \FreeFW\Model\Conditions
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
    
    /**
     * 
     * {@inheritDoc}
     * @see \FreeFW\Core\Model::__toString()
     */
    public function __toString()
    {
        $str   = '';
        $first = true;
        foreach ($this->conditions as $oneCondition) {
            if ($first) {
                $first = false;
            } else {
                $str = $str . ' ' . $this->operator;
            }
            $str = $str . ' ' . $oneCondition->__toString();
        }
        return '( ' . trim($str) . ' )';
    }
}