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
     * ex1 : (field, value)
     * ex2 : and(equals(field, value),equals(field2, value2)) : @todo
     *
     * @param string $p_operator
     * @param string $p_value
     *
     * @return mixed
     */
    protected function getNPICondition($p_operator, $p_value)
    {
        $value = trim($p_value);
        // Start with (, than simple condition
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
                // Only possible for IS NULL
                if ($p_operator == \FreeFW\Storage\Storage::COND_EMPTY) {
                    $aValue = new \FreeFW\Model\ConditionValue();
                    $aValue->setValue(null);
                    $aCondition->setRightMember($aValue);
                } else {
                    throw new \Exception(sprintf('%s operator must provide a value', $p_operator));
                }
            } else {
                // Can't be IS NULL
                if ($p_operator == \FreeFW\Storage\Storage::COND_EMPTY) {
                    throw new \Exception(sprintf('%s operator cannot provide a value', $p_operator));
                }
                if (count($parts) == 1) {
                    // Simple value, a simple string
                    $val = trim(trim($parts[0], '\''));
                    if ($val !== 'null') {
                        $aValue = new \FreeFW\Model\ConditionValue();
                        $aValue->setValue($val);
                        $aCondition->setRightMember($aValue);
                    } else {
                        if ($aCondition->getOperator() === \FreeFW\Storage\Storage::COND_EQUAL) {
                            $aCondition->setOperator(\FreeFW\Storage\Storage::COND_EMPTY);
                        } else {
                            if ($aCondition->getOperator() === \FreeFW\Storage\Storage::COND_NOT_EQUAL) {
                                $aCondition->setOperator(\FreeFW\Storage\Storage::COND_NOT_EMPTY);
                            }
                        }
                    }
                } else {
                    // BETWEEN, IN, ...
                    $aValue = new \FreeFW\Model\ConditionValue();
                    $addA = [];
                    foreach ($parts as $val) {
                        $addA[] = trim($val);
                    }
                    $aValue->setValue($addA);
                    $aCondition->setRightMember($aValue);
                }
            }
            return $aCondition;
        } else {
            // first word is a operator with condition in ()
            $pos         = strpos($value, '(');
            $oper        = substr($value, 0, $pos);
            $right       = substr($value, $pos);
            $right       = substr($right, 0, -1);
            $aConditions = new \FreeFW\Model\Conditions();
            return $aConditions->initFromArray([$oper => $right]);
        }
    }

    /**
     * Split NPI string
     *
     * @param string $p_string
     *
     * @return array
     */
    protected function splitNPICondition($p_string)
    {
        $splitted = [];
        $toCheck = trim($p_string);
        // Si le premier n'est pas un (, c'est un opérateur.
        $matches = [];
        $nbP   = 0;
        $start = 0;
        $found = false;
        for ($i=0; $i<strlen($toCheck); $i++) {
            if ($toCheck[$i] === '(') {
                $nbP++;
                $found = true;
            } else {
                if ($toCheck[$i] === ')') {
                    $nbP--;
                } else {
                    if ($toCheck[$i] === ',') {
                        if ($nbP == 0) {
                            $matches[] = substr($toCheck, $start, $i - $start);
                            $start = $i + 1;
                        }
                    }
                }
            }
        }
        if (!$found) {
            return '(' . $toCheck . ')';
        }
        $matches[] = substr($toCheck, $start, strlen($toCheck) - $start);
        if (count($matches) <= 1) {
            if ($toCheck[0] != '(') {
                $pos  = strpos($toCheck, '(');
                $oper = substr($toCheck, 0, $pos);
                $end  = substr($toCheck, $pos);
                $splitted[$oper] = $this->splitNPICondition($end);
            } else {
                $splitted = $this->splitNPICondition(substr($toCheck, 1, -1));
            }
        } else {
            foreach ($matches as $match) {
                $splitted[] = $this->splitNPICondition($match);
            }
        }
        return $splitted;
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
    public function initFromArray(array $p_conditions, string $p_operator = null, bool $p_not = false)
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
            if (is_numeric($index)) {
                $aCondition = new \FreeFW\Model\Conditions();
                if (is_array($value)) {
                    $aCondition->initFromArray($value, $p_operator);
                } else {
                    $splitted = $this->splitNPICondition($value);
                    $aCondition->initFromArray($splitted, $p_operator);
                }
                $this->conditions[] = $aCondition;
            } else {
                $simpleCond = null;
                switch (strtoupper(str_replace('_', '', $index))) {
                    case 'NOT':
                    case 'AND':
                    case 'OR':
                        $aCondition = new \FreeFW\Model\Conditions();
                        if (is_array($value)) {
                            $aCondition->initFromArray($value, strtolower($index));
                        } else {
                            $splitted = $this->splitNPICondition($value);
                            $aCondition->initFromArray($splitted, strtolower($index));
                        }
                        break;
                    case 'EQ':
                    case 'EQUAL':
                    case 'EQUALS':
                        $simpleCond = \FreeFW\Storage\Storage::COND_EQUAL;
                        break;
                    case 'EQN':
                    case 'EQUALORNULL':
                    case 'EQUALSORNULL':
                        $simpleCond = \FreeFW\Storage\Storage::COND_EQUAL_OR_NULL;
                        break;
                    case 'ltw':
                    case 'LESSTHAN':
                        $simpleCond = \FreeFW\Storage\Storage::COND_LOWER;
                        break;
                    case 'ltwn':
                    case 'LESSTHANORNULL':
                        $simpleCond = \FreeFW\Storage\Storage::COND_LOWER_OR_NULL;
                        break;
                    case 'ltwe':
                    case 'LESSOREQUAL':
                        $simpleCond = \FreeFW\Storage\Storage::COND_LOWER_EQUAL;
                        break;
                    case 'ltwen':
                    case 'LESSOREQUALORNULL':
                        $simpleCond = \FreeFW\Storage\Storage::COND_LOWER_EQUAL_OR_NULL;
                        break;
                    case 'gt':
                    case 'GREATERTHAN':
                        $simpleCond = \FreeFW\Storage\Storage::COND_GREATER;
                        break;
                    case 'gtn':
                    case 'GREATERTHANORNULL':
                        $simpleCond = \FreeFW\Storage\Storage::COND_GREATER_OR_NULL;
                        break;
                    case 'gte':
                    case 'GREATEROREQUAL':
                        $simpleCond = \FreeFW\Storage\Storage::COND_GREATER_EQUAL;
                        break;
                    case 'gten':
                    case 'GREATEROREQUALORNULL':
                        $simpleCond = \FreeFW\Storage\Storage::COND_GREATER_EQUAL_OR_NULL;
                        break;
                    case 'LIKE':
                    case 'CONTAINS':
                        $simpleCond = \FreeFW\Storage\Storage::COND_LIKE;
                        break;
                    case 'LIKEN':
                    case 'CONTAINSORNULL':
                        $simpleCond = \FreeFW\Storage\Storage::COND_LIKE_OR_NULL;
                        break;
                    case 'CONTAINSB';
                    case 'STARTSWITH':
                        $simpleCond = \FreeFW\Storage\Storage::COND_BEGIN_WITH;
                        break;
                    case 'CONTAINSE':
                    case 'ENDSWITH':
                        $simpleCond = \FreeFW\Storage\Storage::COND_END_WITH;
                        break;
                    case 'EMPTY':
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
                                if (is_numeric($idx2) && in_array($value2, [\FreeFW\Storage\Storage::COND_EMPTY, \FreeFW\Storage\Storage::COND_NOT_EMPTY])) {
                                    $aCondition->setOperator($value2);
                                    $aCondition->setRightMember(null);
                                } else {
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
                                            if (strpos($value2, ',') > 0) {
                                                $arrCondition = new \FreeFW\Model\Conditions();
                                                if (in_array($idx2, [\FreeFW\Storage\Storage::COND_NOT_EQUAL, \FreeFW\Storage\Storage::COND_NOT_EQUAL_OR_NULL])) {
                                                    $arrCondition->setOperator(\FreeFW\Storage\Storage::COND_AND);
                                                } else {
                                                    $arrCondition->setOperator(\FreeFW\Storage\Storage::COND_OR);
                                                }
                                                foreach (explode(',', $value2) as $oneValue) {
                                                    $a2Condition = new \FreeFW\Model\SimpleCondition();
                                                    $a2Condition->setLeftMember($aField);
                                                    $aValue = new \FreeFW\Model\ConditionValue();
                                                    $aValue->setValue($oneValue);
                                                    $a2Condition->setOperator($idx2);
                                                    $a2Condition->setRightMember($aValue);
                                                    $arrCondition[] = $a2Condition;
                                                }
                                                $aCondition = $arrCondition;
                                            } else {
                                                $aValue = new \FreeFW\Model\ConditionValue();
                                                $aValue->setValue($value2);
                                                $aCondition->setOperator($idx2);
                                                $aCondition->setRightMember($aValue);
                                            }
                                        }
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