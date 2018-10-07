<?php
namespace FreeFW\Model;

/**
 * Storage model
 *
 * @author jeromeklam
 */
class Query extends \FreeFW\Core\Model implements \FreeFW\Interfaces\StorageStrategyInterface
{

    /**
     * Types
     * @var string
     */
    const QUERY_SELECT   = 'SELECT';
    const QUERY_DISTINCT = 'DISTINCT';
    const QUERY_UPDATE   = 'UPDATE';
    const QUERY_DELETE   = 'DELETE';

    /**
     * Storage strategy
     * @var \FreeFW\Interfaces\StorageInterface
     */
    protected $stategy = null;

    /**
     * Type
     * @var string
     */
    protected $type = self::QUERY_SELECT;

    /**
     * Main model
     * @var string
     */
    protected $main_model = null;

    /**
     * Conditions
     * @var array
     */
    protected $conditions = [];

    /**
     * ResultSet
     * @var \FreeFW\Model\ResultSet
     */
    protected $result_set = false;

    /**
     * Constructor
     *
     * @param \FreeFW\Interfaces\StorageInterface $p_strategy
     */
    public function __construct(\FreeFW\Interfaces\StorageInterface $p_strategy = null)
    {
        $this->result_set = new \FreeFW\Model\ResultSet();
        $this->strategy   = $p_strategy;
    }

    /**
     *
     * {@inheritDoc}
     * @see \FreeFW\Interfaces\StorageStrategyInterface::setStrategy()
     */
    public function setStrategy(\FreeFW\Interfaces\StorageInterface $p_strategy)
    {
        $this->strategy = $p_strategy;
        return $this;
    }

    /**
     * Set type
     *
     * @param string $p_type
     *
     * @return \FreeFW\Model\Query
     */
    public function setType(string $p_type)
    {
        $this->type = $p_type;
        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set main model
     *
     * @param string $p_model
     *
     * @return \FreeFW\Model\Query
     */
    public function setMainModel(string $p_model)
    {
        $this->main_model = $p_model;
        return $this;
    }

    /**
     * Get main model
     *
     * @return string
     */
    public function getMainModel()
    {
        return $this->main_model;
    }

    /**
     * Add a condition
     *
     * @param \FreeFW\Model\Condition $p_condition
     *
     * @return \FreeFW\Model\Query
     */
    public function addCondition(\FreeFW\Model\Condition $p_condition)
    {
        $this->conditions[] = $p_condition;
        return $this;
    }

    /**
     * Add simple condition
     *
     * @param string $p_operator
     * @param string $p_left
     * @param mixed  $p_right
     *
     * @return \FreeFW\Model\Query
     */
    public function addSimpleCondition(string $p_operator, string $p_left, $p_right = null)
    {
        /**
         * condition
         * @var \FreeFW\Model\Condition $condition
         */
        $condition = \FreeFW\DI\DI::get('FreeFW::Model::Condition');
        $left = null;
        if (strpos($p_left, '::Model::') !== false) {
            $left = new \FreeFW\Model\ConditionMember();
            $left->setField($p_left);
        } else {
            $left = new \FreeFW\Model\ConditionValue();
            $left->setField($p_left);
        }
        $right = null;
        if ($p_right !== null) {
            if (strpos($p_right, '::Model::') !== false) {
                $right = new \FreeFW\Model\ConditionMember();
                $right->setField($p_right);
            } else {
                $right = new \FreeFW\Model\ConditionValue();
                $right->setField($p_right);
            }
        }
        $condition
            ->setLeftMember($left)
            ->setOperator($p_operator)
        ;
        if ($right !== null) {
            $condition->setRightMember($right);
        }
        return $this->addCondition($condition);
    }

    /**
     * Simple lower condition
     *
     * @param string $p_member
     * @param mixed $p_value
     *
     * @return \FreeFW\Core\StorageModel
     */
    public function conditionLower(string $p_member, $p_value)
    {
        return $this->addSimpleCondition(\FreeFW\Storage\Storage::COND_LOWER, $p_member, $p_value);
    }

    /**
     * Set conditions
     *
     * @param array $p_filters
     *
     * @return \FreeFW\Model\Query
     */
    public function setConditions(array $p_filters = [])
    {
        foreach ($p_filters as $field => $condition) {
            if (strpos($field, '::Model::') === false) {
                $field = $this->main_model . '.' . $field;
            }
            if (is_array($condition)) {
                foreach ($condition as $oper => $value) {
                    $this->addSimpleCondition($oper, $field, $value);
                }
            } else {
                $this->addSimpleCondition(\FreeFW\Storage\Storage::COND_EQUAL, $field, $condition);
            }
        }
        return $this;
    }

    /**
     * Execute
     *
     * @return boolean
     */
    public function execute()
    {
        $this->result_set = new \FreeFW\Model\ResultSet();
        switch ($this->type) {
            case self::QUERY_SELECT:
                $model            = \FreeFW\DI\DI::get($this->main_model);
                $this->result_set = $this->strategy->select($model, $this->conditions);
                return true;
                break;
            case self::QUERY_DELETE:
                $model = \FreeFW\DI\DI::get($this->main_model);
                return $this->strategy->delete($model, $this->conditions);
                break;
            default:
                var_dump('error query execute');
                die;
        }
        return false;
    }

    /**
     * Get resultSet
     *
     * @return \FreeFW\Model\ResultSet
     */
    public function getResult()
    {
        return $this->result_set;
    }

    /**
     *
     */
    public function init()
    {
    }

    /**
     *
     */
    protected function validate()
    {
    }
}
