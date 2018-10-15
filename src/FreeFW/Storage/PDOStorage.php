<?php
namespace FreeFW\Storage;

use \FreeFW\Constants as FFCST;

/**
 *
 * @author jeromeklam
 *
 */
class PDOStorage extends \FreeFW\Storage\Storage
{

    /**
     * Provider
     * @var \FreeFW\Interfaces\StorageProviderInterface
     */
    protected $provider = null;

    /**
     * Models cache
     * @var array
     */
    protected static $models = [];

    /**
     * Uniqid
     * @var integer
     */
    protected static $uniqid = 187868;

    /**
     * Constructor
     *
     * @param \FreeFW\Interfaces\StorageProviderInterface $p_provider
     */
    public function __construct(\FreeFW\Interfaces\StorageProviderInterface $p_provider)
    {
        $this->provider = $p_provider;
        self::$uniqid   = rand(100000, 999999);
    }

    /**
     *
     * {@inheritDoc}
     * @see \FreeFW\Interfaces\StorageStrategyInterface::create()
     */
    public function create(\FreeFW\Core\StorageModel &$p_model)
    {
        $fields     = [];
        $source     = $p_model::getSource();
        $properties = $p_model::getProperties();
        $setter     = false;
        foreach ($properties as $name => $oneProperty) {
            $add = true;
            $pk  = false;
            if (array_key_exists(FFCST::PROPERTY_OPTIONS, $oneProperty)) {
                if (in_array(FFCST::OPTION_LOCAL, $oneProperty[FFCST::PROPERTY_OPTIONS])) {
                    $add = false;
                }
                if (in_array(FFCST::OPTION_PK, $oneProperty[FFCST::PROPERTY_OPTIONS])) {
                    $pk = true;
                }
            }
            if ($add) {
                // PK fields must be autoincrement...
                if ($pk) {
                    $fields[$oneProperty[FFCST::PROPERTY_PRIVATE]] = null;
                    // setter for id
                    $setter = 'set' . \FreeFW\Tools\PBXString::toCamelCase($name, true);
                } else {
                    // Compute getter
                    $getter = 'get' . \FreeFW\Tools\PBXString::toCamelCase($name, true);
                    // Get data
                    $val = $p_model->$getter();
                    if ($val === false) {
                        $val = 0;
                    }
                    $fields[':' . $oneProperty[FFCST::PROPERTY_PRIVATE]] = $val;
                }
            }
        }
        // Build query
        $sql = \FreeFW\Tools\Sql::makeInsertQuery($source, $fields);
        $this->logger->debug('PDOStorage.create : ' . $sql);
        try {
            // Get PDO and execute
            $query = $this->provider->prepare($sql, array(\PDO::ATTR_CURSOR => \PDO::CURSOR_FWDONLY));
            if ($query->execute($fields)) {
                if ($setter) {
                    $lastId = $this->provider->lastInsertId();
                    $p_model->$setter($lastId);
                }
            } else {
                $this->logger->debug('PDOStorage.create.error : ' . print_r($query->errorInfo(), true));
                $localErr = $query->errorInfo();
                $code     = 0;
                $message  = 'PDOStorage.create.error : ' . print_r($query->errorInfo(), true);
                if (is_array($localErr) && count($localErr) > 1) {
                    $code    = intval($localErr[0]);
                    $message = $localErr[2];
                }
                $p_model->addError($code, $message);
            }
        } catch (\Exception $ex) {
            $this->logger->debug('PDOStorage.create.error : ' . print_r($ex->getMessage(), true));
            $p_model->addError($ex->getCode(), $ex->getMessage());
        }
        return !$p_model->hasErrors();
    }

    /**
     *
     * {@inheritDoc}
     * @see \FreeFW\Interfaces\StorageInterface::findFirst()
     */
    public function findFirst(\FreeFW\Core\StorageModel &$p_model, $p_filters = null)
    {
        $source     = $p_model->getSource();
        $properties = $p_model->getProperties();
        $fields     = [];
        if (is_int($p_filters)) {
            foreach ($properties as $name => $oneProperty) {
                if (array_key_exists(FFCST::PROPERTY_OPTIONS, $oneProperty)) {
                    if (in_array(FFCST::OPTION_PK, $oneProperty[FFCST::PROPERTY_OPTIONS])) {
                        $fields[':' . $oneProperty[FFCST::PROPERTY_PRIVATE]] = $p_filters;
                        break;
                    }
                }
            }
        } else {
            if (is_array($p_filters)) {
                foreach ($p_filters as $field => $value) {
                    if (array_key_exists($field, $properties)) {
                        if ($value === false) {
                            $value = 0;
                        }
                        $fields[':' . $properties[$field][FFCST::PROPERTY_PRIVATE]] = $value;
                    } else {
                        throw new \FreeFW\Core\FreeFWStorageException(sprintf('Unkown %s field !', $field));
                    }
                }
            }
        }
        // Build query
        $sql = \FreeFW\Tools\Sql::makeSimpleSelect($source, $fields);
        $this->logger->debug('PDOStorage.create : ' . $sql);
        try {
            // Get PDO and execute
            $query = $this->provider->prepare($sql, array(\PDO::ATTR_CURSOR => \PDO::CURSOR_FWDONLY));
            if ($query->execute($fields)) {
                while ($row = $query->fetch(\PDO::FETCH_OBJ)) {
                    $p_model->setFromArray($row);
                    break;
                }
            } else {
                $this->logger->debug('PDOStorage.create.error : ' . print_r($query->errorInfo(), true));
                $localErr = $query->errorInfo();
                $code     = 0;
                $message  = 'PDOStorage.create.error : ' . print_r($query->errorInfo(), true);
                if (is_array($localErr) && count($localErr) > 1) {
                    $code    = intval($localErr[0]);
                    $message = $localErr[2];
                }
                $p_model->addError($code, $message);
            }
        } catch (\Exception $ex) {
            var_dump($ex);
        }
        return $p_model->isValid();
    }

    /**
     *
     * {@inheritDoc}
     * @see \FreeFW\Interfaces\StorageStrategyInterface::remove()
     */
    public function remove(\FreeFW\Core\StorageModel &$p_model)
    {
        $fields     = [];
        $source     = $p_model::getSource();
        $properties = $p_model::getProperties();
        foreach ($properties as $name => $oneProperty) {
            if (array_key_exists(FFCST::PROPERTY_OPTIONS, $oneProperty)) {
                if (in_array(FFCST::OPTION_PK, $oneProperty[FFCST::PROPERTY_OPTIONS])) {
                    // Compute getter
                    $getter = 'get' . \FreeFW\Tools\PBXString::toCamelCase($name, true);
                    // Get data
                    $fields[':' . $oneProperty[FFCST::PROPERTY_PRIVATE]] = $p_model->$getter();
                }
            }
        }
        // Build query
        $ok  = false;
        $sql = \FreeFW\Tools\Sql::makeDeleteQuery($source, $fields);
        $this->logger->debug('PDOStorage.remove : ' . $sql);
        try {
            // Get PDO and execute
            $query = $this->provider->prepare($sql, array(\PDO::ATTR_CURSOR => \PDO::CURSOR_FWDONLY));
            if ($query->execute($fields)) {
                $code = '';
                $ok   = true;
            } else {
                $this->logger->debug('PDOStorage.remove.error : ' . print_r($query->errorInfo(), true));
                $localErr = $query->errorInfo();
                $code     = 0;
                $message  = 'PDOStorage.remove.error : ' . print_r($query->errorInfo(), true);
                if (is_array($localErr) && count($localErr) > 1) {
                    $code    = intval($localErr[0]);
                    $message = $localErr[2];
                }
                $p_model->addError($code, $message);
            }
        } catch (\Exception $ex) {
            var_dump($ex);
        }
        return $ok;
    }

    /**
     *
     * {@inheritDoc}
     * @see \FreeFW\Interfaces\StorageInterface::save()
     */
    public function save(\FreeFW\Core\StorageModel &$p_model)
    {
        $pks        = [];
        $fields     = [];
        $source     = $p_model::getSource();
        $properties = $p_model::getProperties();
        foreach ($properties as $name => $oneProperty) {
            $add = true;
            $pk  = false;
            if (array_key_exists(FFCST::PROPERTY_OPTIONS, $oneProperty)) {
                if (in_array(FFCST::OPTION_LOCAL, $oneProperty[FFCST::PROPERTY_OPTIONS])) {
                    $add = false;
                }
                if (in_array(FFCST::OPTION_PK, $oneProperty[FFCST::PROPERTY_OPTIONS])) {
                    $pk = true;
                }
            }
            if ($add) {
                // PK fields must be autoincrement...
                $getter = 'get' . \FreeFW\Tools\PBXString::toCamelCase($name, true);
                if ($pk) {
                    // Get data
                    $pks[':' . $oneProperty[FFCST::PROPERTY_PRIVATE]] = $p_model->$getter();
                } else {
                    // Get data
                    $val = $p_model->$getter();
                    if ($val === false) {
                        $val = 0;
                    }
                    $fields[':' . $oneProperty[FFCST::PROPERTY_PRIVATE]] = $val;
                }
            }
        }
        // Build query
        $sql = \FreeFW\Tools\Sql::makeUpdateQuery($source, $fields, $pks);
        $this->logger->debug('PDOStorage.save : ' . $sql);
        try {
            // Get PDO and execute
            $query = $this->provider->prepare($sql, array(\PDO::ATTR_CURSOR => \PDO::CURSOR_FWDONLY));
            if ($query->execute($fields)) {
                $code = '';
            } else {
                $this->logger->debug('PDOStorage.save.error : ' . print_r($query->errorInfo(), true));
                $localErr = $query->errorInfo();
                $code     = 0;
                $message  = 'PDOStorage.save.error : ' . print_r($query->errorInfo(), true);
                if (is_array($localErr) && count($localErr) > 1) {
                    $code    = intval($localErr[0]);
                    $message = $localErr[2];
                }
                $p_model->addError($code, $message);
            }
        } catch (\Exception $ex) {
            var_dump($ex);
        }
        return !$p_model->hasErrors();
    }

    /**
     * Select the model
     *
     * @param \FreeFW\Core\StorageModel $p_model
     * @param array                     $p_conditions
     *
     * @return \FreeFW\Model\ResultSet
     */
    public function select(\FreeFW\Core\StorageModel &$p_model, array $p_conditions = [])
    {
        $source     = $p_model::getSource();
        $properties = $p_model::getProperties();
        $values     = [];
        $result     = \FreeFW\DI\DI::get('FreeFW::Model::ResultSet');
        /**
         * @var \FreeFW\Model\Condition $oneCondition
         */
        $where = '';
        foreach ($p_conditions as $idx => $oneCondition) {
            $part = $this->renderCondition($oneCondition);
            if ($where != '') {
                $where = $where . ' AND ';
            }
            $where  = $where . $part['sql'];
            $values = array_merge($values, $part['values']);
        }
        // Build query
        $sql = 'SELECT * FROM ' . $source . ' WHERE ' . $where;
        $this->logger->debug('PDOStorage.select : ' . $sql);
        // I got all, run query...
        try {
            // Get PDO and execute
            $query = $this->provider->prepare($sql, array(\PDO::ATTR_CURSOR => \PDO::CURSOR_FWDONLY));
            if ($query->execute($values)) {
                while ($row = $query->fetch(\PDO::FETCH_OBJ)) {
                    $model = clone($p_model);
                    $model
                        ->init()
                        ->setFromArray($row)
                    ;
                    $result[] = $model;
                }
            } else {
                $this->logger->debug('PDOStorage.select.error : ' . print_r($query->errorInfo(), true));
                $localErr = $query->errorInfo();
                $code     = 0;
                $message  = 'PDOStorage.select.error : ' . print_r($query->errorInfo(), true);
                die($message);
                if (is_array($localErr) && count($localErr) > 1) {
                    $code    = intval($localErr[0]);
                    $message = $localErr[2];
                }
                $p_model->addError($code, $message);
            }
        } catch (\Exception $ex) {
            var_dump($ex);
        }
        return $result;
    }

    /**
     * Remove the model
     *
     * @param \FreeFW\Core\StorageModel $p_model
     * @param array                     $p_conditions
     *
     * @return boolean
     */
    public function delete(\FreeFW\Core\StorageModel &$p_model, array $p_conditions = [])
    {
        $source     = $p_model::getSource();
        $properties = $p_model::getProperties();
        $values     = [];
        $ok         = false;
        /**
         * @var \FreeFW\Model\Condition $oneCondition
         */
        $where = '';
        foreach ($p_conditions as $idx => $oneCondition) {
            $part = $this->renderCondition($oneCondition);
            if ($where != '') {
                $where = $where . ' AND ';
            }
            $where  = $where . $part['sql'];
            $values = array_merge($values, $part['values']);
        }
        // Build query
        $sql = 'DELETE FROM ' . $source . ' WHERE ' . $where;
        $this->logger->debug('PDOStorage.delete : ' . $sql);
        // I got all, run query...
        try {
            // Get PDO and execute
            $query = $this->provider->prepare($sql, array(\PDO::ATTR_CURSOR => \PDO::CURSOR_FWDONLY));
            if ($query->execute($values)) {
                $ok = true;
            } else {
                $this->logger->debug('PDOStorage.delete.error : ' . print_r($query->errorInfo(), true));
                $localErr = $query->errorInfo();
                $code     = 0;
                $message  = 'PDOStorage.delete.error : ' . print_r($query->errorInfo(), true);
                if (is_array($localErr) && count($localErr) > 1) {
                    $code    = intval($localErr[0]);
                    $message = $localErr[2];
                }
                $p_model->addError($code, $message);
            }
        } catch (\Exception $ex) {
            var_dump($ex);
        }
        return $ok;
    }

    /**
     * Render a condition
     *
     * @param \FreeFW\Interfaces\ConditionInterface $p_field
     *
     * @throws \FreeFW\Core\FreeFWStorageException
     *
     * @return array
     */
    protected function renderConditionField(\FreeFW\Interfaces\ConditionInterface $p_field)
    {
        if ($p_field instanceof \FreeFW\Model\ConditionMember) {
            $field = $p_field->getField();
            return $this->renderModelField($field);
        } else {
            if ($p_field instanceof \FreeFW\Model\ConditionValue) {
                $value = $p_field->getField();
                return $this->renderValueField($value);
            } else {
                throw new \FreeFW\Core\FreeFWStorageException(
                    sprintf('Unknown condition objecte !')
                );
            }
        }
    }

    /**
     * Render a full condition
     *
     * @param \FreeFW\Model\Condition $p_condition
     *
     * @throws \FreeFW\Core\FreeFWStorageException
     */
    protected function renderCondition(\FreeFW\Model\Condition $p_condition)
    {
        $result = [];
        $left   = $p_condition->getLeftMember();
        $right  = $p_condition->getRightMember();
        $oper   = $p_condition->getOperator();
        switch ($oper) {
            case \FreeFW\Storage\Storage::COND_LOWER:
            case \FreeFW\Storage\Storage::COND_LOWER_EQUAL:
            case \FreeFW\Storage\Storage::COND_GREATER:
            case \FreeFW\Storage\Storage::COND_GREATER_EQUAL:
            case \FreeFW\Storage\Storage::COND_EQUAL:
                if ($left !== null && $right !== null) {
                    $leftDatas  = $this->renderConditionField($left);
                    $rightDatas = $this->renderConditionField($right);
                    $result = [
                        'sql'    => $leftDatas['id'] . ' ' . $oper . ' ' . $rightDatas['id'],
                        'values' => [],
                        'type'   => false
                    ];
                    if ($leftDatas['type'] === false) {
                        $result['values'][$leftDatas['id']] = $leftDatas['value'];
                    } else {
                        $result['type'] = $leftDatas['type'];
                    }
                    if ($rightDatas['type'] === false) {
                        $result['values'][$rightDatas['id']] = $rightDatas['value'];
                    } else {
                        $result['type'] = $rightDatas['type'];
                    }
                } else {
                    throw new \FreeFW\Core\FreeFWStorageException(
                        sprintf('Wrong fields for %s condition', $oper)
                    );
                }
                break;
            default:
                throw new \FreeFW\Core\FreeFWStorageException(
                    sprintf('Unknown condition : %s !', $oper)
                );
                break;
        }
        return $result;
    }

    /**
     * Render a model field
     *
     * @param string $p_field
     *
     * @throws \FreeFW\Core\FreeFWStorageException
     */
    protected function renderModelField(string $p_field)
    {
        $parts = explode('.', $p_field);
        $class = $parts[0];
        $field = $parts[1];
        if (!array_key_exists($class, self::$models)) {
            self::$models[$class] = \FreeFW\DI\DI::get($class);
        }
        $model      = self::$models[$class];
        $source     = $model::getSource();
        $properties = $model::getProperties();
        $type       = \FreeFW\Constants::TYPE_STRING;
        if (array_key_exists($field, $properties)) {
            $real = $source . '.' . $properties[$field][FFCST::PROPERTY_PRIVATE];
            $type = $properties[$field][FFCST::PROPERTY_TYPE];
        } else {
            throw new \FreeFW\Core\FreeFWStorageException(
                sprintf('Unknown field : %s !', $p_field)
            );
        }
        return [
            'id'    => $real,
            'value' => $p_field,
            'type'  => $type
        ];
    }

    /**
     * Render a value
     *
     * @param mixed $p_value
     *
     * @return []
     */
    protected function renderValueField($p_value)
    {
        self::$uniqid = self::$uniqid + 1;
        return [
            'id'    => ':i' . rand(10, 99) . '_' . self::$uniqid,
            'value' => $p_value,
            'type'  => false
        ];
    }

    /**
     *
     * {@inheritDoc}
     * @see \FreeFW\Interfaces\StorageInterface::getFields()
     */
    public function getFields(string $p_object): array
    {
        $fields = [];
        if ($this->provider) {
            $rs = $this->provider->query('SELECT * FROM ' . $p_object . ' LIMIT 0');
            for ($i = 0; $i < $rs->columnCount(); $i++) {
                $fields[] = \FreeFW\Model\Field::getFromPDO($rs->getColumnMeta($i));
            }
        }
        return $fields;
    }
}
