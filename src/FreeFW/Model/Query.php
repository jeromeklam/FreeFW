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
    const QUERY_COUNT    = 'COUNT';

    /**
     * Joins
     * @var string
     */
    const JOIN_LEFT  = 'LEFT';
    const JOIN_RIGHT = 'RIGHT';
    const JOIN_INNER = 'INNER';

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
     * @var \FreeFW\Model\Conditions
     */
    protected $conditions = null;

    /**
     * Relations
     * @var array
     */
    protected $relations = [];

    /**
     * ResultSet
     * @var \FreeFW\Model\ResultSet
     */
    protected $result_set = false;

    /**
     * From
     * @var integer
     */
    protected $from = 0;

    /**
     * Length
     * @var integer
     */
    protected $length = 0;

    /**
     * Sort by
     * @var array
     */
    protected $sort = [];

    /**
     * Constructor
     *
     * @param \FreeFW\Interfaces\StorageInterface $p_strategy
     */
    public function __construct(\FreeFW\Interfaces\StorageInterface $p_strategy = null)
    {
        $this->result_set = \FreeFW\DI\DI::get('FreeFW::Model::ResultSet');
        $this->conditions = \FreeFW\DI\DI::get('FreeFW::Model::Conditions');
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
     * Set operator
     * 
     * @param string $p_operator
     * 
     * @return \FreeFW\Model\Query
     */
    public function setOperator($p_operator)
    {
        $this->conditions->setOperator($p_operator);
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
     * @param \FreeFW\Model\SimpleCondition $p_condition
     *
     * @return \FreeFW\Model\Query
     */
    public function addCondition(\FreeFW\Model\SimpleCondition $p_condition)
    {
        $this->conditions->add($p_condition);
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
        $condition = \FreeFW\DI\DI::get('FreeFW::Model::SimpleCondition');
        $left = null;
        if (strpos($p_left, '::Model::') !== false) {
            $left = new \FreeFW\Model\ConditionMember($p_left);
            $left->setValue($p_left);
        } else {
            $left = new \FreeFW\Model\ConditionValue($p_left);
            $left->setValue($p_left);
        }
        $right = null;
        if ($p_right !== null) {
            if (strpos($p_right, '::Model::') !== false) {
                $right = new \FreeFW\Model\ConditionMember();
                $right->setValue($p_right);
            } else {
                $right = new \FreeFW\Model\ConditionValue();
                $right->setValue($p_right);
            }
        }
        $condition->setOperator($p_operator);
        if ($left !== null) {
            $condition->setLeftMember($left);
        } else {
            // @todo : strange...
        }
        if ($right !== null) {
            $condition->setRightMember($right);
        } else {
            if ($p_operator === \FreeFW\Storage\Storage::COND_EQUAL) {
                $condition->setOperator(\FreeFW\Storage\Storage::COND_EMPTY);
            }
        }
        return $this->addCondition($condition);
    }

    /**
     * Simple lower condition
     *
     * @param string $p_member
     * @param mixed $p_value
     *
     * @return \FreeFW\Model\Query
     */
    public function conditionLower(string $p_member, $p_value)
    {
        return $this->addSimpleCondition(\FreeFW\Storage\Storage::COND_LOWER, $p_member, $p_value);
    }

    /**
     * Add conditions
     *
     * @param \FreeFW\Model\Conditions $p_conditions
     *
     * @return \FreeFW\Model\Query
     */
    public function addConditions(\FreeFW\Model\Conditions $p_conditions = null)
    {
        if ($p_conditions) {
            $this->conditions->add($p_conditions);
        }
        return $this;
    }

    /**
     * Set conditions
     *
     * @param array $p_filters
     *
     * @return \FreeFW\Model\Query
     */
    public function addFromFilters(array $p_filters = [])
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
     * Add relations
     * 
     * @param array $p_relations
     * 
     * @return \FreeFW\Model\Query
     */
    public function addRelations($p_relations)
    {
        $this->relations = $p_relations;
        return $this;
    }

    /**
     * Execute
     *
     * @return boolean
     */
    public function execute(array $p_fields = [], $p_function = null)
    {
        $this->result_set = new \FreeFW\Model\ResultSet();
        switch ($this->type) {
            case self::QUERY_COUNT:
                $model            = \FreeFW\DI\DI::get($this->main_model);
                $this->result_set = $this->strategy->count(
                    $model,
                    $this->conditions,
                    $this->relations,
                    $this->from,
                    $this->length,
                    $this->sort
                );
                return true;
            case self::QUERY_SELECT:
                $model            = \FreeFW\DI\DI::get($this->main_model);
                $this->result_set = $this->strategy->select(
                    $model,
                    $this->conditions,
                    $this->relations,
                    $this->from,
                    $this->length,
                    $this->sort,
                    '',
                    $p_function
                );
                return true;
            case self::QUERY_UPDATE:
                $model = \FreeFW\DI\DI::get($this->main_model);
                return $this->strategy->update($model, $p_fields, $this->conditions);
            case self::QUERY_DELETE:
                $model = \FreeFW\DI\DI::get($this->main_model);
                return $this->strategy->delete($model, $this->conditions);
            default:
                var_dump('error query execute');
                die;
        }
        return false;
    }

    /**
     * Set query limit
     *
     * @param int $p_start
     * @param int $p_len
     *
     * @return \FreeFW\Model\Query
     */
    public function setLimit(int $p_start = 0, int $p_len = 0)
    {
        $this->from   = $p_start;
        $this->length = $p_len;
        return $this;
    }

    /**
     * Get start
     *
     * @return int
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * get length
     *
     * @return int
     */
    public function getLength()
    {
        return $this->length;
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
     * Initialization
     *
     * return self
     */
    public function init()
    {
        $this->result_set = false;
    }

    /**
     * Set sort fields
     * 
     * @param array $p_sort
     * 
     * @return \FreeFW\Model\Query
     */
    public function setSort($p_sort)
    {
        if (is_array($p_sort)) {
            $this->sort = $p_sort;
        } else {
            $this->sort = [];
            $sorts = explode(',', $p_sort);
            foreach ($sorts as $idx => $field) {
                if ($field[0] == '-') {
                    $this->sort[substr($field, 1)] = '-';
                } else {
                    if ($field[0] == '-') {
                        $this->sort[substr($field, 1)] = '+';
                    } else {
                        $this->sort[$field] = '+';
                    }
                }
            }
        }
        return $this;
    }
}
