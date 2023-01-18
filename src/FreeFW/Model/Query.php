<?php
namespace FreeFW\Model;

use FreeFW\Core\Model;

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
    const QUERY_GROUP    = 'GROUP';
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
    const JOIN_NONE  = 'NONE';

    /**
     * Storage strategy
     * @var \FreeFW\Interfaces\StorageInterface
     */
    protected $strategy = null;

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
     * Fields
     * @var array
     */
    protected $fields = [];

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
     * Cache
     */
    protected static $_cached = [];

    /**
     * Get default storage
     *
     * @return string
     */
    protected static function getDefaultStorage()
    {
        return 'default';
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->result_set = new \FreeFW\Model\ResultSet();
        $this->conditions = new \FreeFW\Model\Conditions();
        $this->strategy = \FreeFW\DI\DI::getShared('Storage::' . self::getDefaultStorage());
        $this->flushErrors();
        $this->flushFields();
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
     * @param array  $p_fields
     *
     * @return \FreeFW\Model\Query
     */
    public function setType(string $p_type, array $p_fields = [])
    {
        $this->type = $p_type;
        $this->setFields($p_fields);
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
     * Set fields
     *
     * @param array $p_fields
     *
     * @return \FreeFW\Model\Query
     */
    public function setFields(array $p_fields)
    {
        if (is_array($p_fields) && count($p_fields) > 0) {
            foreach ($p_fields as $oneField) {
                $this->addField($oneField);
            }
        }
        return $this;
    }

    /**
     * Get fields
     *
     * @return array
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * Has fields
     *
     * @return boolean
     */
    public function hasFields()
    {
        return count($this->fields) > 0;
    }

    /**
     * Flush fields
     *
     * @return \FreeFW\Model\Query
     */
    public function flushFields()
    {
        $this->fields = [];
        return $this;
    }

    /**
     * Add one field
     *
     * @param string|\FreeFW\Model\Field $p_field
     * @param string                     $p_function
     *
     * @return \FreeFW\Model\Query
     */
    public function addField($p_field, $p_function = '')
    {
        if (is_string($p_field)) {
            $field    = $p_field;
            $function = $p_function;
            if (strpos(':', $p_field) !== false) {
                $parts    = explode(':', $p_field);
                $field    = $parts[0];
                $function = $parts[1];
            }
            $newField = new \FreeFW\Model\Field();
            $newField
                ->setFldName($field)
                ->setFldFunction($function)
            ;
            $this->fields[] = $newField;
        } else {
            if ($p_field instanceof \FreeFW\Model\Field) {
                $this->fields[] = $p_field;
            } else {
                throw new \InvalidArgumentException('Incorrect select type class');
            }
        }
        return $this;
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
    public function addSimpleCondition(
        string $p_operator,
        \FreeFW\Interfaces\ConditionInterface $p_left,
        \FreeFW\Interfaces\ConditionInterface $p_right = null
        ) {
        /**
         * condition
         * @var \FreeFW\Model\SimpleCondition $condition
         */
        $condition = new \FreeFW\Model\SimpleCondition;
        $condition->setOperator($p_operator);
        if ($p_left !== null) {
            $condition->setLeftMember($p_left);
        } else {
            // @todo : strange...
        }
        if ($p_right !== null) {
            $condition->setRightMember($p_right);
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
            $left = new \FreeFW\Model\ConditionMember($field);
            $left->setValue($field);
            if (is_array($condition)) {
                foreach ($condition as $oper => $value) {
                    if ($oper === 0) {
                        $this->addSimpleCondition($value, $left, null);
                    } else {
                        // Simple condition
                        $right = new \FreeFW\Model\ConditionValue();
                        $right->setValue($value);
                        $this->addSimpleCondition($oper, $left, $right);
                    }
                }
            } else {
                if ($condition === null) {
                    $this->addSimpleCondition(\FreeFW\Storage\Storage::COND_EMPTY, $left);
                } else {
                    if ($condition === \FreeFW\Storage\Storage::COND_EMPTY) {
                        $this->addSimpleCondition(\FreeFW\Storage\Storage::COND_EMPTY, $left);
                    } else {
                        if ($condition === \FreeFW\Storage\Storage::COND_NOT_EMPTY) {
                            $this->addSimpleCondition(\FreeFW\Storage\Storage::COND_NOT_EMPTY, $left);
                        } else {
                            $right = new \FreeFW\Model\ConditionValue();
                            $right->setValue($condition);
                            $this->addSimpleCondition(\FreeFW\Storage\Storage::COND_EQUAL, $left, $right);
                        }
                    }
                }
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
     * Execute with cache
     *
     * @param array  $p_fields
     * @param string $p_function
     * @param array  $p_parameters
     *
     * @return boolean
     */
    public function executeWithCache(array $p_fields = [], $p_function = null, $p_parameters = [])
    {
        $key = md5(serialize($this->conditions));
        if (isset(self::$_cached[$key])) {
            return self::$_cached[$key];
        }
        self::$_cached[$key] = $this->execute($p_fields, $p_function, $p_parameters);
        return self::$_cached[$key];
    }

    /**
     * Execute
     *
     * @param array  $p_fields
     * @param string $p_function
     * @param array  $p_parameters
     * @param bool   $p_force_blob
     *
     * @return boolean
     */
    public function execute(array $p_fields = [], $p_function = null, $p_parameters = [], $p_force_blob = false)
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
            case self::QUERY_GROUP:
                $model            = \FreeFW\DI\DI::get($this->main_model);
                $this->result_set = $this->strategy->select(
                    $model,
                    $this->conditions,
                    $this->relations,
                    $this->from,
                    $this->length,
                    $this->sort,
                    '',
                    $p_function,
                    $this->getFields(),
                    'GROUPBY',
                    $p_parameters,
                    $p_force_blob
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
                    $p_function,
                    [],
                    'SELECT',
                    $p_parameters,
                    $p_force_blob
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
        if (!$this->result_set instanceof \FreeFW\Model\ResultSet) {
            $this->result_set = new \FreeFW\Model\ResultSet();
        }
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

    /**
     * Get conditions
     *
     * @return \FreeFW\Model\Conditions
     */
    public function getConditions()
    {
        return $this->conditions;
    }
}
