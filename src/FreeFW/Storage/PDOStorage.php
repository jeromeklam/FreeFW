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
        $sso        = \FreeFW\DI\DI::getShared('sso');
        if (method_exists($p_model, 'beforeCreate')) {
            if (!$p_model->beforeCreate()) {
                return false;
            }
        }
        $setter = false;
        foreach ($properties as $name => $oneProperty) {
            $add = true;
            $pk  = false;
            $brk = false;
            $dtz = false;
            if (array_key_exists(FFCST::PROPERTY_OPTIONS, $oneProperty)) {
                if (in_array(FFCST::OPTION_LOCAL, $oneProperty[FFCST::PROPERTY_OPTIONS])) {
                    $add = false;
                }
                if (in_array(FFCST::OPTION_PK, $oneProperty[FFCST::PROPERTY_OPTIONS])) {
                    $pk = true;
                }
                if (in_array(FFCST::OPTION_BROKER, $oneProperty[FFCST::PROPERTY_OPTIONS])) {
                    $brk = true;
                }
                if ($oneProperty[FFCST::PROPERTY_TYPE] == FFCST::TYPE_DATETIMETZ) {
                    $dtz = true;
                }
            }
            if ($add) {
                // PK fields must be autoincrement...
                if ($pk) {
                    $fields[$oneProperty[FFCST::PROPERTY_PRIVATE]] = null;
                    // setter for id
                    $setter = 'set' . \FreeFW\Tools\PBXString::toCamelCase($name, true);
                } else {
                    if ($brk) {
                        $fields[':' . $oneProperty[FFCST::PROPERTY_PRIVATE]] = $sso->getBrokerId();
                    } else {
                        // Compute getter
                        $getter = 'get' . \FreeFW\Tools\PBXString::toCamelCase($name, true);
                        // Get data
                        $val = $p_model->$getter();
                        if ($val === false) {
                            $val = 0;
                        }
                        if ($dtz && $val != '') {
                            $val = \FreeFW\Tools\Date::stringToMysql($val);
                        }
                        $fields[':' . $oneProperty[FFCST::PROPERTY_PRIVATE]] = $val;
                    }
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
                try {
                    $this->forwardRawEvent(FFCST::EVENT_STORAGE_CREATE, $p_model);
                } catch (\Exception $ex) {
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
                try {
                    $this->forwardRawEvent(FFCST::EVENT_STORAGE_DELETE, $p_model);
                } catch (\Exception $ex) {
                }
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
        $sso        = \FreeFW\DI\DI::getShared('sso');
        if (method_exists($p_model, 'beforeSave')) {
            if (!$p_model->beforeSave()) {
                return false;
            }
        }
        foreach ($properties as $name => $oneProperty) {
            $add = true;
            $pk  = false;
            $brk = false;
            $dtz = false;
            if (array_key_exists(FFCST::PROPERTY_OPTIONS, $oneProperty)) {
                if (in_array(FFCST::OPTION_LOCAL, $oneProperty[FFCST::PROPERTY_OPTIONS])) {
                    $add = false;
                }
                if (in_array(FFCST::OPTION_PK, $oneProperty[FFCST::PROPERTY_OPTIONS])) {
                    $pk = true;
                }
                if (in_array(FFCST::OPTION_BROKER, $oneProperty[FFCST::PROPERTY_OPTIONS])) {
                    $brk = true;
                }
                if ($oneProperty[FFCST::PROPERTY_TYPE] == FFCST::TYPE_DATETIMETZ) {
                    $dtz = true;
                }
            }
            if ($add) {
                // PK fields must be autoincrement...
                $getter = 'get' . \FreeFW\Tools\PBXString::toCamelCase($name, true);
                if ($pk) {
                    // Get data
                    $pks[':' . $oneProperty[FFCST::PROPERTY_PRIVATE]]    = $p_model->$getter();
                    $fields[':' . $oneProperty[FFCST::PROPERTY_PRIVATE]] = $p_model->$getter();
                } else {
                    if ($brk) {
                        $fields[':' . $oneProperty[FFCST::PROPERTY_PRIVATE]] = $sso->getBrokerId();
                    } else {
                        // Get data
                        $val = $p_model->$getter();
                        if ($val === false) {
                            $val = 0;
                        }
                        if ($dtz && $val != '') {
                            $val = \FreeFW\Tools\Date::stringToMysql($val);
                        }
                        $fields[':' . $oneProperty[FFCST::PROPERTY_PRIVATE]] = $val;
                    }
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
                try {
                    $this->forwardRawEvent(FFCST::EVENT_STORAGE_UPDATE, $p_model);
                } catch (\Exception $ex) {
                }
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
        return !$p_model->hasErrors();
    }

    /**
     * Select the model
     *
     * @param \FreeFW\Core\StorageModel $p_model
     * @param \FreeFW\Model\Conditions  $p_conditions
     *
     * @return \FreeFW\Model\ResultSet
     */
    public function select(
        \FreeFW\Core\StorageModel &$p_model,
        \FreeFW\Model\Conditions $p_conditions = null,
        array $p_relations = [],
        int $p_from = 0,
        int $p_length = 0,
        array $p_sort = [],
        string $p_force_select = ''
    ) {
        $crtAlias     = 'A';
        $aliases      = [];
        $aliases['@'] = $crtAlias;
        $select       = $p_model->getFieldsForSelect($crtAlias);
        $from         = $p_model::getSource() . ' AS ' . $crtAlias;
        $properties   = $p_model::getProperties();
        $values       = [];
        $result       = \FreeFW\DI\DI::get('FreeFW::Model::ResultSet');
        $fks          = [];
        $joins        = [];
        $loadModels   = [];
        $whereBroker  = '';
        /**
         * 
         */
        $sort = '';
        foreach ($p_sort as $column => $order) {
            if ($sort == '') {
                $sort = ' ORDER BY ';
            } else {
                $sort = $sort . ', ';
            }
            if ($order == '+') {
                $sort = $sort . $crtAlias . '.' . $column;
            } else {
                $sort = $sort . $crtAlias . '.' . $column . ' DESC';
            }
        }
        /**
         * Check specific properties
         */
        foreach ($properties as $name => $property) {
            if (array_key_exists(FFCST::PROPERTY_OPTIONS, $property)) {
                if (in_array(FFCST::OPTION_BROKER, $property[FFCST::PROPERTY_OPTIONS])) {
                    $whereBroker = ' AND ( ' . $crtAlias . '.' . $name . ' = ' . $p_model->getMainBroker() . ')';
                }
            }
            if (array_key_exists(FFCST::PROPERTY_FK, $property)) {
                foreach ($property[FFCST::PROPERTY_FK] as $fkname => $fkprops) {
                    $fks[$fkname] = [
                        'left'  => $name,
                        'right' => $fkprops
                    ];
                }
            }
        }
        foreach ($p_relations as $idx => $shortcut) {
            $parts     = explode('.', $shortcut);
            $onePart   = array_shift($parts);
            $crtFKs    = $fks;
            $getter    = '';
            $baseAlias = '@';
            while ($onePart != '') {
                if (array_key_exists($onePart, $crtFKs) && !array_key_exists($onePart, $joins)) {
                    $joins[$onePart] = $crtFKs[$onePart]['right'];
                    $newModel = \FreeFW\DI\DI::get($crtFKs[$onePart]['right']['model']);
                    self::$models[$onePart] = $newModel;
                    ++$crtAlias;
                    $aliases[$baseAlias . '.' . $onePart] = $crtAlias;
                    $select   = $select . ', ' . $newModel->getFieldsForSelect($crtAlias);
                    $alias1   = $aliases[$baseAlias];
                    switch ($crtFKs[$onePart]['right']['type']) {
                        case \FreeFW\Model\Query::JOIN_RIGHT:
                            $from = $from . ' RIGHT JOIN ' . $newModel::getSource() . ' AS ' . $crtAlias . ' ON ';
                            $from = $from . $crtAlias . '.' . $crtFKs[$onePart]['right']['field'] . ' = ';
                            $from = $from . $alias1 . '.' . $crtFKs[$onePart]['left'];
                            break;
                        case \FreeFW\Model\Query::JOIN_LEFT:
                            $from = $from . ' LEFT JOIN ' . $newModel::getSource() . ' AS ' . $crtAlias . ' ON ';
                            $from = $from . $crtAlias . '.' . $crtFKs[$onePart]['right']['field'] . ' = ';
                            $from = $from . $alias1 . '.' . $crtFKs[$onePart]['left'];
                            break;
                        default:
                            $from = $from . ' INNER JOIN ' . $newModel::getSource() . ' AS ' . $crtAlias . ' ON ';
                            $from = $from . $crtAlias . '.' . $crtFKs[$onePart]['right']['field'] . ' = ';
                            $from = $from . $alias1 . '.' . $crtFKs[$onePart]['left'];
                            break;
                    }
                    $loadModels[] = [
                        'model'  => $crtFKs[$onePart]['right']['model'],
                        'setter' => 'set' . \FreeFW\Tools\PBXString::toCamelCase($onePart, true),
                        'getter' => $getter
                    ];
                }
                $baseAlias = $baseAlias . '.' . $onePart;
                $getter = 'get' . \FreeFW\Tools\PBXString::toCamelCase($onePart, true);
                if (count($parts) > 0) {
                    $onePart    = array_shift($parts);
                    $properties = $newModel::getProperties();
                    $crtFKs     = [];
                    foreach ($properties as $name => $property) {
                        if (array_key_exists(FFCST::PROPERTY_FK, $property)) {
                            foreach ($property[FFCST::PROPERTY_FK] as $fkname => $fkprops) {
                                $crtFKs[$fkname] = [
                                    'left'  => $name,
                                    'right' => $fkprops
                                ];
                            }
                        }
                    }
                } else {
                    $onePart = '';
                }
            }
        }
        $parts  = $this->renderConditions($p_conditions, $p_model, $aliases, '@');
        $where  = $parts['sql'];
        $values = $parts['values'];
        /**
         * @var \FreeFW\Model\Condition $oneCondition
         */
        // Build query
        if (trim($where) == '') {
            $where = ' 1 = 1';
        }
        $limit = '';
        if ($p_from > 0 || $p_length > 0) {
            $limit = ' LIMIT ' . $p_from;
            if ($p_length > 0) {
                $limit = $limit . ', ' . $p_length;
            }
        }
        if ($p_force_select !== '') {
            $select = $p_force_select;
        }
        $sql = 'SELECT ' . $select . ' FROM ' . $from . ' WHERE ( ' . $where . ' ) ' . $whereBroker . ' ' . $sort . ' ' . $limit;
        $this->logger->debug('PDOStorage.select : ' . $sql);
        // I got all, run query...
        try {
            // Get PDO and execute
            $query = $this->provider->prepare($sql, array(\PDO::ATTR_CURSOR => \PDO::CURSOR_FWDONLY));
            if ($query->execute($values)) {
                if ($p_force_select !== '') {
                    $result = [];
                    while ($row = $query->fetch(\PDO::FETCH_OBJ)) {
                        $result[] = $row;
                    }
                } else {
                    $clName = str_replace('\\', '::', get_class($p_model));
                    while ($row = $query->fetch(\PDO::FETCH_OBJ)) {
                        $model = \FreeFW\DI\DI::get($clName);
                        $model
                            ->init()
                            ->setFromArray($row, $aliases, '@')
                        ;
                        $result[] = $model;
                    }
                }
            } else {
                $this->logger->debug('PDOStorage.select.error : ' . print_r($query->errorInfo(), true));
                $localErr = $query->errorInfo();
                $code     = 0;
                $message  = 'PDOStorage.select.error : ' . print_r($query->errorInfo(), true);
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
     * Count
     *
     * @param \FreeFW\Core\StorageModel $p_model
     * @param \FreeFW\Model\Conditions  $p_conditions
     *
     * @return \FreeFW\Model\ResultSet
     */
    public function count(
        \FreeFW\Core\StorageModel &$p_model,
        \FreeFW\Model\Conditions $p_conditions = null,
        array $p_relations = [],
        int $p_from = 0,
        int $p_length = 0,
        array $p_sort = []
    ) {
        $result = $this->select(
            $p_model,
            $p_conditions,
            $p_relations,
            $p_from,
            $p_length,
            $p_sort,
            'COUNT(*) AS MONTOT'
        );
        var_dump($result);
        die;
    }

    /**
     * Remove the model
     *
     * @param \FreeFW\Core\StorageModel $p_model
     * @param array                     $p_conditions
     *
     * @return boolean
     */
    public function delete(
        \FreeFW\Core\StorageModel &$p_model,
        \FreeFW\Model\Conditions $p_conditions = null
    ) {
        $source     = $p_model::getSource();
        $properties = $p_model::getProperties();
        $values     = [];
        $ok         = false;
        /**
         * @var \FreeFW\Model\Condition $oneCondition
         */
        $parts  = $this->renderConditions($p_conditions, $p_model);
        $where  = $parts['sql'];
        $values = $parts['values'];
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
     * Render conditions
     *
     * @param \FreeFW\Model\Conditions   $p_conditions
     * @param  \FreeFW\Core\StorageModel $p_model
     */
    protected function renderConditions(
        \FreeFW\Model\Conditions $p_conditions,
        \FreeFW\Core\StorageModel $p_model,
        array $p_aliases = [],
        $p_crtAlias = '@'
    ) {
        $result = [
            'sql'    => '',
            'type'   => false,
            'values' => []
        ];
        $oper = ' AND ';
        if ($p_conditions->getOperator() == \FreeFW\Storage\Storage::COND_OR) {
            $oper = ' OR ';
        }
        foreach ($p_conditions as $idx => $oneCondition) {
            if ($oneCondition instanceof \FreeFW\Model\SimpleCondition) {
                $parts = $this->renderCondition($oneCondition, $p_model, $p_aliases, $p_crtAlias);
                if ($result['sql'] == '') {
                    $result['sql']    = $parts['sql'];
                    $result['values'] = $parts['values'];
                } else {
                    $result['sql']    = $result['sql'] . $oper . $parts['sql'];
                    $result['values'] = array_merge($result['values'], $parts['values']);
                }
            } else {
                $parts = $this->renderConditions($oneCondition, $p_model, $p_aliases, $p_crtAlias);
                if ($result['sql'] == '') {
                    $result['sql']    = $parts['sql'];
                    $result['values'] = $parts['values'];
                } else {
                    $result['sql']    = $result['sql'] . $oper . $parts['sql'];
                    $result['values'] = array_merge($result['values'], $parts['values']);
                }
            }
        }
        return $result;
    }

    /**
     * Render a condition
     *
     * @param \FreeFW\Interfaces\ConditionInterface $p_field
     * @param \FreeFW\Core\StorageModel             $p_model
     *
     * @throws \FreeFW\Core\FreeFWStorageException
     *
     * @return array
     */
    protected function renderConditionField(
        \FreeFW\Interfaces\ConditionInterface $p_field,
        \FreeFW\Core\StorageModel $p_model,
        array $p_aliases = [], 
        $p_crtAlias = '@'
    ) {
        if ($p_field instanceof \FreeFW\Model\ConditionMember) {
            $field = $p_field->getValue();
            return $this->renderModelField($field, $p_model, $p_aliases, $p_crtAlias);
        } else {
            if ($p_field instanceof \FreeFW\Model\ConditionValue) {
                $value = $p_field->getValue();
                return $this->renderValueField($value, $p_model);
            } else {
                throw new \FreeFW\Core\FreeFWStorageException(
                    sprintf('Unknown condition object !')
                );
            }
        }
    }

    /**
     * Render a full condition
     *
     * @param \FreeFW\Model\SimpleCondition $p_condition
     * @param \FreeFW\Core\StorageModel     $p_model
     *
     * @throws \FreeFW\Core\FreeFWStorageException
     */
    protected function renderCondition(
        \FreeFW\Model\SimpleCondition $p_condition,
        \FreeFW\Core\StorageModel $p_model,
        array $p_aliases = [],
        $p_crtAlias = '@'
    ) {
        $result   = [];
        $left     = $p_condition->getLeftMember();
        $right    = $p_condition->getRightMember();
        $oper     = $p_condition->getOperator();
        $addL     = '';
        $addR     = '';
        $realOper = '=';
        $nullable = false;
        switch ($oper) {
            case \FreeFW\Storage\Storage::COND_LOWER:
                $realOper = '<';
                break;
            case \FreeFW\Storage\Storage::COND_LOWER_EQUAL:
                $realOper = '<=';
                break;
            case \FreeFW\Storage\Storage::COND_GREATER:
                $realOper = '>';
                break;
            case \FreeFW\Storage\Storage::COND_GREATER_EQUAL:
                $realOper = '>=';
                break;
            case \FreeFW\Storage\Storage::COND_EQUAL:
                $realOper = '=';
                break;
            case \FreeFW\Storage\Storage::COND_LIKE:
                $realOper = 'like';
                $addL     = '%';
                $addR     = '%';
                break;
            case \FreeFW\Storage\Storage::COND_EQUAL_OR_NULL:
                $realOper = '=';
                $nullable = true;
                break;
        }
        if ($left !== null && $right !== null) {
            $leftDatas  = $this->renderConditionField($left, $p_model, $p_aliases, $p_crtAlias);
            $rightDatas = $this->renderConditionField($right, $p_model, $p_aliases, $p_crtAlias);
            $result     = [
                'values' => [],
                'type'   => false
            ];
            if ($nullable) {
                $result['sql'] = '(' .
                    $leftDatas['id'] . $realOper . $rightDatas['id'] . ' OR ' .
                    $leftDatas['id'] . ' IS NULL)';
            } else {
                $result['sql'] = $leftDatas['id'] . ' ' . $realOper . ' ' . $rightDatas['id'];
            }
            if ($leftDatas['type'] === false) {
                $result['values'][$leftDatas['id']] = $leftDatas['value'];
            } else {
                $result['type'] = $leftDatas['type'];
            }
            if ($rightDatas['type'] === false) {
                $result['values'][$rightDatas['id']] = $addL . $rightDatas['value'] . $addR;
            } else {
                $result['type'] = $rightDatas['type'];
            }
        } else {
            throw new \FreeFW\Core\FreeFWStorageException(
                sprintf('Wrong fields for %s condition', $oper)
            );
        }
        return $result;
    }

    /**
     * Render a model field
     *
     * @param string                    $p_field
     * @param \FreeFW\Core\StorageModel $p_model
     *
     * @throws \FreeFW\Core\FreeFWStorageException
     */
    protected function renderModelField(
        string $p_field,
        \FreeFW\Core\StorageModel $p_model,
        array $p_aliases = [],
        $p_crtAlias = '@'
    ) {
        $parts = explode('.', $p_field);
        if (count($parts) > 1) {
            $class = $parts[0];
            $field = $parts[1];
            if (!array_key_exists($class, self::$models)) {
                self::$models[$class] = \FreeFW\DI\DI::get($class);
            }
            $model      = self::$models[$class];
            $source     = $model::getSource();
            $properties = $model::getProperties();
        } else {
            $source     = $p_model::getSource();
            $properties = $p_model::getProperties();
            $field      = $parts[0];
        }
        if (array_key_exists($p_crtAlias, $p_aliases)) {
            $alias = $p_aliases[$p_crtAlias];
        } else {
            $alias = $source;
        }
        $type  = \FreeFW\Constants::TYPE_STRING;
        if (array_key_exists($field, $properties)) {
            $real = $alias . '.' . $properties[$field][FFCST::PROPERTY_PRIVATE];
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
     * @param mixed                     $p_value
     * @param \FreeFW\Core\StorageModel $p_model
     *
     * @return []
     */
    protected function renderValueField($p_value, \FreeFW\Core\StorageModel $p_model)
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

    /**
     * Get provider
     * 
     * @return \FreeFW\Interfaces\StorageProviderInterface
     */
    public function getProvider()
    {
        return $this->provider;
    }
}
