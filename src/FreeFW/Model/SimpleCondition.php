<?php
namespace FreeFW\Model;

/**
 *
 * @author jeromeklam
 *
 */
class SimpleCondition extends \FreeFW\Core\Model
{

    /**
     *
     * @var \FreeFW\Interfaces\ConditionInterface
     */
    protected $left = null;

    /**
     *
     * @var string
     */
    protected $operator = \FreeFW\Storage\Storage::COND_EQUAL;

    /**
     *
     * @var \FreeFW\Interfaces\ConditionInterface
     */
    protected $right = null;

    /**
     * Set left member
     *
     * @param \FreeFW\Interfaces\ConditionInterface $p_member
     *
     * @return \FreeFW\Model\SimpleCondition
     */
    public function setLeftMember(\FreeFW\Interfaces\ConditionInterface $p_member)
    {
        $this->left = $p_member;
        return $this;
    }

    /**
     * Get left member
     *
     * @return \FreeFW\Interfaces\ConditionInterface | null
     */
    public function getLeftMember()
    {
        return $this->left;
    }

    /**
     * Set right member
     *
     * @param \FreeFW\Interfaces\ConditionInterface $p_member
     *
     * @return \FreeFW\Model\SimpleCondition
     */
    public function setRightMember(\FreeFW\Interfaces\ConditionInterface $p_member)
    {
        $this->right = $p_member;
        return $this;
    }

    /**
     * Get right member
     *
     * @return \FreeFW\Interfaces\ConditionInterface | null
     */
    public function getRightMember()
    {
        return $this->right;
    }

    /**
     * Set operator
     *
     * @param string $p_operator
     *
     * @return \FreeFW\Model\SimpleCondition
     */
    public function setOperator(string $p_operator)
    {
        if (!in_array($p_operator, \FreeFW\Storage\Storage::getAllOperators())) {
            throw new \FreeFW\Core\FreeFWStorageException(
                sprintf('Unknown operator : %s !', $p_operator)
            );
        }
        $this->operator = $p_operator;
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
     * @return string
     */
    public function render()
    {
        $final = '';
        return $final;
    }
    
    /**
     * 
     * {@inheritDoc}
     * @see \FreeFW\Core\Model::__toString()
     */
    public function __toString()
    {
        $str = $this->left->__toString() . ' ' . $this->operator;
        if ($this->right) {
            $str = $str . ' ' . $this->right->__toString();
        }
        return $str;
    }
}
