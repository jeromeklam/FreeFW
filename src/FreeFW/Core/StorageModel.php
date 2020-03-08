<?php
namespace FreeFW\Core;

use \FreeFW\Constants as FFCST;

/**
 * Storage model
 *
 * @author jeromeklam
 */
abstract class StorageModel extends \FreeFW\Core\Model implements
    \FreeFW\Interfaces\StorageStrategyInterface,
    \FreeFW\Interfaces\DirectStorageInterface
{

    /**
     * Storage strategy
     * @var \FreeFW\Interfaces\StorageInterface
     */
    protected $stategy = null;

    /**
     * Main broker id
     * @var number
     */
    protected $main_broker = null;
    
    /**
     * Constructor
     *
     * @param \FreeFW\Interfaces\StorageInterface $p_strategy
     */
    public function __construct(\FreeFW\Interfaces\StorageInterface $p_strategy = null)
    {
        $this->strategy = $p_strategy;
    }

    /**
     * Set main broker
     * 
     * @param number $p_id
     * 
     * @return \FreeFW\Core\StorageModel
     */
    public function setMainBroker($p_id)
    {
        $this->main_broker = $p_id;
        return $this;
    }
    
    /**
     * Get main broker
     * 
     * @return number
     */
    public function getMainBroker()
    {
        return $this->main_broker;
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
     *
     * {@inheritDoc}
     * @see \FreeFW\Interfaces\StorageStrategyInterface::create()
     */
    public function create()
    {
        if ($this->isValid()) {
            return $this->strategy->create($this);
        }
        return false;
    }

    /**
     *
     * {@inheritDoc}
     * @see \FreeFW\Interfaces\StorageStrategyInterface::save()
     */
    public function save()
    {
        if ($this->isValid()) {
            return $this->strategy->save($this);
        }
        return false;
    }

    /**
     *
     * {@inheritDoc}
     * @see \FreeFW\Interfaces\StorageStrategyInterface::findFirst()
     */
    public static function findFirst(array $p_filters = [])
    {
        $cls   = get_called_class();
        $cls   = rtrim(ltrim($cls, '\\'), '\\');
        /**
         * @var \FreeFW\Model\Query $query
         */
        $query = \FreeFW\DI\DI::get('FreeFW::Model::Query');
        $query
            ->setType(\FreeFW\Model\Query::QUERY_SELECT)
            ->setMainModel(str_replace('\\', '::', $cls))
            ->addFromFilters($p_filters)
        ;
        $model = false;
        if ($query->execute()) {
            /**
             * @var \FreeFW\Model\ResultSet $result
             */
            $result = $query->getResult();
            if (!$result->isEmpty()) {
                $model = $result[0];
            }
        }
        return $model;
    }

    /**
     *
     * {@inheritDoc}
     * @see \FreeFW\Interfaces\StorageStrategyInterface::find()
     */
    public static function find(array $p_filters = [])
    {
        $cls   = get_called_class();
        $cls   = rtrim(ltrim($cls, '\\'), '\\');
        /**
         * @var \FreeFW\Model\Query $query
         */
        $query = \FreeFW\DI\DI::get('FreeFW::Model::Query');
        $query
            ->setType(\FreeFW\Model\Query::QUERY_SELECT)
            ->setMainModel(str_replace('\\', '::', $cls))
            ->addFromFilters($p_filters)
        ;
        $model = new \FreeFW\Model\ResultSet();
        if ($query->execute()) {
            /**
             * @var \FreeFW\Model\ResultSet $result
             */
            $model = $query->getResult();
        }
        return $model;
    }

    /**
     *
     * {@inheritDoc}
     * @see \FreeFW\Interfaces\StorageStrategyInterface::update()
     */
    public static function update(array $p_fields, array $p_filters = [])
    {
        $cls   = get_called_class();
        $cls   = rtrim(ltrim($cls, '\\'), '\\');
        /**
         * @var \FreeFW\Model\Query $query
         */
        $query = \FreeFW\DI\DI::get('FreeFW::Model::Query');
        $query
            ->setType(\FreeFW\Model\Query::QUERY_UPDATE)
            ->setMainModel(str_replace('\\', '::', $cls))
            ->addFromFilters($p_filters)
        ;
        return $query->execute($p_fields);
    }

    /**
     *
     * {@inheritDoc}
     * @see \FreeFW\Interfaces\StorageStrategyInterface::delete()
     */
    public static function delete(array $p_filters = [])
    {
        $cls   = get_called_class();
        $cls   = rtrim(ltrim($cls, '\\'), '\\');
        /**
         * @var \FreeFW\Model\Query $query
         */
        $query = \FreeFW\DI\DI::get('FreeFW::Model::Query');
        $query
            ->setType(\FreeFW\Model\Query::QUERY_DELETE)
            ->setMainModel(str_replace('\\', '::', $cls))
            ->addFromFilters($p_filters)
        ;
        return $query->execute();
    }

    /**
     *
     * {@inheritDoc}
     * @see \FreeFW\Interfaces\StorageStrategyInterface::remove()
     */
    public function remove()
    {
        return $this->strategy->remove($this);
    }

    /**
     * Set from array
     *
     * @param array $p_array
     *
     * @return \FreeFW\Core\Model
     */
    public function setFromArray($p_array, array $p_aliases = [], $p_crtAlias = '@')
    {
        if ($p_array instanceof \stdClass) {
            $p_array = (array)$p_array;
        }
        if (is_array($p_array)) {
            $properties = $this->getProperties();
            $fields     = [];
            $relations  = [];
            foreach ($properties as $key => $prop) {
                $fields[$prop[FFCST::PROPERTY_PRIVATE]] = $key;
                if (array_key_exists(FFCST::PROPERTY_OPTIONS, $prop) && in_array(FFCST::OPTION_FK, $prop[FFCST::PROPERTY_OPTIONS])) {
                    $relations[$key] = $prop;
                }
            }
            $alias = '';
            $subst = 0;
            if (array_key_exists($p_crtAlias, $p_aliases)) {
                $alias = $p_aliases[$p_crtAlias];
                $subst = strlen($alias) + 1;
            }
            foreach ($p_array as $field => $value) {
                if ($subst > 0) {
                    if (strpos($field, $alias) !== 0) {
                        continue;
                    }
                    $field = substr($field, $subst);
                }
                if (array_key_exists($field, $fields)) {
                    $property = $fields[$field];
                    $setter   = 'set' . \FreeFW\Tools\PBXString::toCamelCase($property, true);
                    switch ($properties[$property][FFCST::PROPERTY_TYPE]) {
                        case FFCST::TYPE_BIGINT:
                        case FFCST::TYPE_INTEGER:
                            if ($value !== null) {
                                $this->$setter(intval($value));
                            }
                            break;
                        case FFCST::TYPE_BOOLEAN:
                            if (intval($value) > 0) {
                                $this->$setter(true);
                            } else {
                                $this->$setter(false);
                            }
                            break;
                        case FFCST::TYPE_DATETIMETZ:
                            if ($value != '') {
                                $this->$setter(\FreeFW\Tools\Date::stringToISO8601($value));
                            } else {
                                $this->$setter(null);
                            }
                            break;
                        default:
                            $this->$setter($value);
                            break;
                    }
                }
            }
            $newAliases = [];
            foreach ($p_aliases as $kA => $vA) {
                if (strpos($kA, $p_crtAlias . '.') == 0) {
                    $newAliases[substr($kA, strlen($p_crtAlias . '.'))] = $vA;
                }
            }
            if (count($newAliases) > 0) {
                foreach ($relations as $key => $prop) {
                    foreach ($prop[FFCST::PROPERTY_FK] as $fk => $pfk) {
                        $fieldFK = $pfk['field'];
                        $modelFK = $pfk['model'];
                        if (array_key_exists($fk, $newAliases)) {
                            $newModel = \FreeFW\DI\DI::get($modelFK);
                            $newModel->setFromArray($p_array, $newAliases, $fk);
                            $setter = 'set' . \FreeFW\Tools\PBXString::toCamelCase($fk, true);
                            $this->$setter($newModel);
                        }
                    }
                }
            }
        }
    }

    /**
     * Get new Query Model
     *
     * @param string $p_type
     *
     * @return \FreeFW\Model\Query
     */
    public static function getQuery(string $p_type = \FreeFW\Model\Query::QUERY_SELECT)
    {
        $cls   = get_called_class();
        $cls   = rtrim(ltrim($cls, '\\'), '\\');
        $query = \FreeFW\DI\DI::get('FreeFW::Model::Query');
        $query
            ->setType($p_type)
            ->setMainModel(str_replace('\\', '::', $cls))
        ;
        return $query;
    }

    /**
     *
     * {@inheritDoc}
     * @see \Serializable::serialize()
     */
    public function serialize()
    {
        $serializable = get_object_vars($this);
        unset($serializable['strategy']);
        return serialize($serializable);
    }

    /**
     * 
     * @return array
     */
    public function __toArray()
    {
        $serializable = get_object_vars($this);
        unset($serializable['strategy']);
        return $serializable;
    }

    /**
     *
     * {@inheritDoc}
     * @see \Serializable::unserialize()
     */
    public function unserialize($data)
    {
        $unserialized = unserialize($data);
        if (is_array($unserialized) === true) {
            // @todo : add strategy... from DI ?
            foreach ($unserialized as $property => $value) {
                $this->{$property} = $value;
            }
        }
    }

    /**
     * Return pk field getter function
     * 
     * @return string
     */
    public function getPkGetter() : string
    {
        foreach ($this->getProperties() as $name => $property) {
            if (array_key_exists(FFCST::PROPERTY_OPTIONS, $property)) {
                if (in_array(FFCST::OPTION_PK, $property[FFCST::PROPERTY_OPTIONS])) {
                    $getter = 'get' . \FreeFW\Tools\PBXString::toCamelCase($name, true);
                    return $getter;
                }
            }
        }
        return '';
    }

    /**
     * Return pk field name
     *
     * @return string
     */
    public function getPkField() : string
    {
        foreach ($this->getProperties() as $name => $property) {
            if (array_key_exists(FFCST::PROPERTY_OPTIONS, $property)) {
                if (in_array(FFCST::OPTION_PK, $property[FFCST::PROPERTY_OPTIONS])) {
                    return $name;
                }
            }
        }
        return '';
    }

    /**
     * Return all fields for a select clause
     *
     * @return string
     */
    public function getFieldsForSelect(string $p_alias = '') : string
    {
        $select = '';
        foreach ($this->getProperties() as $name => $property) {
            if ($p_alias == '') {
                $add = $name;
            } else {
                $add = $p_alias . '.' . $name . ' AS ' . $p_alias . '_' . $name;
            }
            if ($select == '') {
                $select = $add;
            } else {
                $select = $select . ', ' . $add;
            }
        }
        return $select;
    }

    /**
     * Count
     *
     * @param array $p_filters
     *
     * @return number
     */
    public static function count(array $p_filters = [])
    {
        $cls   = get_called_class();
        $cls   = rtrim(ltrim($cls, '\\'), '\\');
        /**
         * @var \FreeFW\Model\Query $query
         */
        $query = \FreeFW\DI\DI::get('FreeFW::Model::Query');
        $query
            ->setType(\FreeFW\Model\Query::QUERY_COUNT)
            ->setMainModel(str_replace('\\', '::', $cls))
            ->addFromFilters($p_filters)
        ;
        $total = 0;
        if ($query->execute()) {
            /**
             * @var \FreeFW\Model\ResultSet $result
             */
            $results = $query->getResult();
            var_export($results);die;
        }
        return $total;
    }

    /**
     * Return object source
     *
     * @return string
     */
    abstract public static function getSource();
}
