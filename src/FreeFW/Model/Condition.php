<?php
namespace FreeFW\Model;

/**
 *
 * @author jeromeklam
 *
 */
class Condition extends \FreeFW\Core\Model
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
    protected $operator = null;

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
     * @return \FreeFW\Model\Condition
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
     * @return \FreeFW\Model\Condition
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
     * @return \FreeFW\Model\Condition
     */
    public function setOperator(string $p_operator)
    {
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
}
