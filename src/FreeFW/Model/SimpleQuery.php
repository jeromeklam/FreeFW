<?php
/**
 * Requête simplifiée
 *
 * @author jeromeklam
 * @package SQL
 */
namespace FreeFW\Model;

/**
 * Requête simplifiée
 * @author jeromeklam
 */
class SimpleQuery extends \FreeFW\Model\Where
{

    /**
     * Champs de tri
     *
     * @var array
     */
    protected $sorted = array();

    /**
     * jointures
     * @var array
     */
    protected $joins = array();

    /**
     * Instance
     * @var \FreeFW\Model\SimpleQuery
     */
    protected static $instance = null;

    /**
     * Limitation du nombre de lignes.
     * @var number
     */
    protected $limit = 0;

    /**
     * Champ selects manuels
     * @var boolean
     */
    protected $select_fields = false;

    /**
     * Début
     * @var number
     */
    protected $start = false;

    /**
     * Récupération d'une connexion
     *
     * @throws \PDOException
     * @throws \UnexpectedValueException
     *
     * @return null
     */
    public static function getConnexion($p_cnx_name)
    {
        $di = \FreeFW\ResourceDi::getInstance();
        return $di->getConnexion($p_cnx_name);
    }

    /**
     * Retourne une instance
     *
     * @param string $p_classname
     * @param string $p_type
     * @param array  $p_others
     *
     * @return \FreeFW\Model\SimpleQuery
     */
    public static function getInstance($p_classname, $p_type = self::SELECT_ALL, $p_others = false)
    {
        return new static($p_classname, $p_type, $p_others);
    }

    /**
     * Ajout d'un champ de tri
     *
     * @param string $p_field
     * @param string $p_order
     *
     * @return \FreeFW\Model\SimpleQuery
     */
    public function addSortField($p_field, $p_order = self::SORT_ASC)
    {
        $this->sorted[] = array(
            'field' => $p_field,
            'order' => $p_order
        );
        return $this;
    }

    /**
     * Ajout d'une jointure entre modèles
     *
     * @param string $p_model1
     * @param string $p_model2
     * @param string $p_type
     *
     * @return \FreeFW\Model\SimpleQuery
     */
    public function joinModel($p_model1, $p_model2, $p_type = self::JOIN_INNER)
    {
        $this->joins[] = array(
            'model1' => $p_model1,
            'model2' => $p_model2,
            'type'   => $p_type
        );
        return $this;
    }

    /**
     * Retourne la clause from
     *
     * @return string
     */
    protected function getFrom()
    {
        $from  = '';
        $class = $this->getModelClass();
        $data  = '';
        $query = false;
        if ($class !== false) {
            $alias = false;
            if (method_exists($class, 'getSourceAlias')) {
                $alias = $class::getSourceAlias();
            }
            if (method_exists($class, 'isQuery')) {
                $query = $class::isQuery();
            }
            if (method_exists($class, 'getSource')) {
                $data = $class::getSource();
            }
            if (!$query && $data != '') {
                $from = 'FROM ' . $data;
                if ($alias !== false) {
                    $from .= ' ' . $alias;
                }
            } else {
                if (method_exists($class, 'columnJoin')) {
                    $from  = 'FROM';
                    $first = true;
                    foreach ($class::columnJoin() as $idx => $join) {
                        $model1 = explode('::', $join['from']);
                        $model2 = explode('::', $join['to']);
                        $ns1    = implode('\\', explode('.', $model1[0]));
                        $ns2    = implode('\\', explode('.', $model2[0]));
                        $parts1 = explode('.', $model1[1]);
                        $parts2 = explode('.', $model2[1]);
                        $clause = array();
                        if (array_key_exists('clause', $join)) {
                            $clause = $join['clause'];
                        }
                        $cls1 = $this->getModelClass('\\' . $ns1 . '\\Model\\' . $parts1[0]);
                        $cls2 = $this->getModelClass('\\' . $ns2 . '\\Model\\' . $parts2[0]);
                        $tab1 = $cls1->getSource();
                        $tab2 = $cls2->getSource();
                        $ali1 = false;
                        $ali2 = false;
                        if (method_exists($cls1, 'getSourceAlias')) {
                            $ali1 = $cls1->getSourceAlias();
                        }
                        if (method_exists($cls2, 'getSourceAlias')) {
                            $ali2 = $cls2->getSourceAlias();
                        }
                        if (method_exists($cls2, 'columnFilter')) {
                            foreach ($cls2::columnFilter() as $prop => $filter) {
                                if (is_array($filter)) {
                                    foreach ($filter as $clause1 => $value1) {
                                        $clause[$join['to'] . '.' . $prop] = $value1;
                                    }
                                } else {
                                    $clause[$join['to'] . '.' . $prop] = $filter;
                                }
                            }
                        }
                        $flds1 = explode(',', $parts1[1]);
                        $flds2 = explode(',', $parts2[1]);
                        if (array_key_exists('type', $join)) {
                            $type = $join['type'];
                        } else {
                            $type = 'INNER JOIN';
                        }
                        if ($first) {
                            $from .= ' ' . $tab1;
                            if ($ali1 != false) {
                                $from .= ' ' . $ali1;
                            }
                            $first = false;
                        }
                        $from .= ' ' . $type . ' ' . $tab2;
                        if ($ali2 != false) {
                            $from .= ' ' . $ali2;
                        }
                        $from .= ' ON ';
                        foreach ($flds1 as $idx => $fld1) {
                            if ($idx > 0) {
                                $from .= ' AND ';
                            }
                            $fld2 = $flds2[$idx];
                            $fld1 = $cls1->getRealFieldName($fld1);
                            $fld2 = $cls2->getRealFieldName($fld2);
                            if ($ali1 != false) {
                                $from .= $ali1;
                            } else {
                                $from .= $tab1;
                            }
                            $from .= '.' . $fld1 . ' = ';
                            if ($ali2 != false) {
                                $from .= $ali2;
                            } else {
                                $from .= $tab2;
                            }
                            $from .= '.' . $fld2;
                        }
                        foreach ($clause as $field => $value) {
                            $model3 = explode('::', $field);
                            $parts3 = explode('.', $model3[1]);
                            $cls3 = $this->getModelClass('\\' . $model3[0] . '\\Model\\' . $parts3[0]);
                            $tab3 = $cls3::getSource();
                            $ali3 = false;
                            $fld3 = $cls3->getRealFieldName($parts3[1]);
                            if (method_exists($cls3, 'getSourceAlias')) {
                                $ali3 = $cls3::getSourceAlias();
                            }
                            $from .= ' AND ';
                            if ($ali3 != false) {
                                $from .= $ali3;
                            } else {
                                $from .= $tab3;
                            }
                            if (is_array($value)) {
                                $subQuery = '';
                                foreach ($value as $field4 => $params4) {
                                    $model4 = explode('::', $field4);
                                    $parts4 = explode('.', $model4[1]);
                                    $cls4 = $this->getModelClass('\\' . $model4[0] . '\\Model\\' . $parts4[0]);
                                    $tab4 = $cls4::getSource();
                                    $ali4 = false;
                                    $fld4 = $cls4->getRealFieldName($parts3[1]);
                                    if (method_exists($cls4, 'getSourceAlias')) {
                                        $ali4 = $cls4::getSourceAlias();
                                    }
                                    $query = false;
                                    if (method_exists($cls4, 'isQuery')) {
                                        $query = $cls4::isQuery();
                                    }
                                    if ($query) {
                                        $query4 = $cls4::getSimpleQuery();
                                        $query4->setLimit(1);
                                        foreach ($params4 as $field5 => $field6) {
                                            $query4->fieldEqual($field5, $field6);
                                        }
                                        $sqlArr   = $query4->getSQLQuery(array($fld3));
                                        $subQuery = $sqlArr['sql'];
                                    }
                                    break;
                                }
                                $from .= '.' . $fld3 . ' = (' . $subQuery . ')';
                            } else {
                                $from .= '.' . $fld3 . ' = \'' . $value . '\'';
                            }
                        }
                    }
                } else {
                    $from = 'FROM ' . $data;
                    if ($alias) {
                        $from = $from . ' ' . $alias;
                    }
                }
            }
        }
        //var_dump($from);die;
        $tbStart  = $data;
        $continue = true;
        $tables   = [];
        $tables[] = $data;
        foreach ($this->joins as $idx => $join) {
            $model1 = explode('::', $join['model1']);
            $model2 = explode('::', $join['model2']);
            if (strpos($model1[0], '.') !== false) {
                $ns1 = implode('\\', explode('.', $model1[0]));
            } else {
                $ns1 = $model1[0];
            }
            if (strpos($model2[0], '.') !== false) {
                $ns2 = implode('\\', explode('.', $model2[0]));
            } else {
                $ns2 = $model2[0];
            }
            $parts1 = explode('.', $model1[1]);
            $parts2 = explode('.', $model2[1]);
            if (!in_array($parts1[0], $tables)) {
                $tables[] = $parts1[0];
            }
            if (!in_array($parts2[0], $tables)) {
                $tables[] = $parts2[0];
            }
        }
        foreach ($tables as $idx => $tbStart) {
            foreach ($this->joins as $idx => $join) {
                $model1 = explode('::', $join['model1']);
                $model2 = explode('::', $join['model2']);
                if (strpos($model1[0], '.') !== false) {
                    $ns1 = implode('\\', explode('.', $model1[0]));
                } else {
                    $ns1 = $model1[0];
                }
                if (strpos($model2[0], '.') !== false) {
                    $ns2 = implode('\\', explode('.', $model2[0]));
                } else {
                    $ns2 = $model2[0];
                }
                $parts1 = explode('.', $model1[1]);
                $parts2 = explode('.', $model2[1]);
                $cls1 = $this->getModelClass('\\' . $ns1 . '\\Model\\' . $parts1[0]);
                $cls2 = $this->getModelClass('\\' . $ns2 . '\\Model\\' . $parts2[0]);
                $tab1 = $cls1->getSource();
                $tab2 = $cls2->getSource();
                $fld1 = $cls1->getRealFieldName($parts1[1]);
                $fld2 = $cls2->getRealFieldName($parts2[1]);
                $ali1 = '';
                $ali2 = '';
                if (method_exists($cls1, 'getSourceAlias')) {
                    $ali1 = $tab1;
                    $tab1 = $cls1->getSourceAlias();
                }
                if (method_exists($cls2, 'getSourceAlias')) {
                    $ali2 = $tab2;
                    $tab2 = $cls2->getSourceAlias();
                }
                if ($parts1[0] == $tbStart) {
                    $from .= ' ' . $join['type'] . ' ' . $ali2 . ' ' . $tab2 .
                             ' ON ' . $ali1 . ' '. $tab1 . '.' . $fld1 . ' = ' . $tab2 . '.' . $fld2;
                    foreach ($this->clauses as $idx => $clause) {
                        switch ($clause['type']) {
                            case self::CLAUSE_FIELD_JEQUAL:
                                $model3 = explode('::', $clause['field1']);
                                if (strpos($model3[0], '.') !== false) {
                                    $ns3 = implode('\\', explode('.', $model3[0]));
                                } else {
                                    $ns3 = $model3[0];
                                }
                                $parts3 = explode('.', $model3[1]);
                                if (($ns1 == $ns3 && $parts1[0] == $parts3[0]) ||
                                    ($ns2 == $ns3 && $parts2[0] == $parts3[0])) {
                                    $fld = $this->getRealField($clause['field1']);
                                    $from .= ' AND ' . $fld . ' = \'' . $clause['value1'] . '\'';
                                    $this->clauses[$idx]['type'] = '@@11@@';
                                }
                                break;
                        }
                    }
                }
            }
        }
        return $from;
    }

    /**
     * Limitation du nombre de résultats retourné
     *
     * @param number $p_limit
     * @param number $p_start
     *
     * @return \FreeFW\Model\SimpleQuery
     */
    public function setLimit($p_limit, $p_start = false)
    {
        $this->limit = $p_limit;
        $this->start = $p_start;
        return $this;
    }

    /**
     * On sélectionne une liste de champ, pas un *
     *
     * @param boolean $p_select
     *
     * @return \FreeFW\Model\SimpleQuery
     */
    public function setSelectFields($p_select)
    {
        $this->select_fields = $p_select;
        return $this;
    }

    /**
     * Retourne la partie SELECT
     *
     * @param boolean $p_with_prefix
     *
     * @return string
     */
    protected function getSelectFields($p_with_prefix = false)
    {
        $fields = '';
        $first  = true;
        $tab    = [];
        $tab[]  = $this->getModelClass();
        if (is_array($this->others)) {
            foreach ($this->others as $idx => $other) {
                $tab[] = $this->getModelClass($other);
            }
        }
        foreach ($tab as $idx => $class) {
            if ($class !== false) {
                if (method_exists($class, 'columnMap')) {
                    $map = $class::columnMap();
                    foreach ($map as $field => $prop) {
                        $parts = explode('::', $field);
                        if (count($parts) > 1) {
                            $ns    = implode('\\', explode('.', $parts[0]));
                            $fld   = $parts[1];
                            $parts = explode('.', $fld);
                            $fld   = $parts[1];
                            $cls   = $ns . '\\Model\\' . $parts[0];
                            $tab   = $cls::getSource();
                            if (method_exists($cls, 'getSourceAlias')) {
                                $tab = $cls::getSourceAlias();
                            }
                            if ($first) {
                                $first = false;
                            } else {
                                $fields .= ', ';
                            }
                            if ($p_with_prefix) {
                                $fields .= $tab . '.' . $fld . ' AS ' . $tab . '_' . $field;
                            } else {
                                $fields .= $tab . '.' . $fld . ' AS ' . $field;
                            }
                        } else {
                            $tab = $class::getSource();
                            if (method_exists($class, 'getSourceAlias')) {
                                $tab = $class::getSourceAlias();
                            }
                            $fld = $field;
                            if ($first) {
                                $first = false;
                            } else {
                                $fields .= ', ';
                            }
                            if ($p_with_prefix) {
                                $fields .= $tab . '.' . $fld . ' AS ' . $tab . '_' . $field;
                            } else {
                                $fields .= $tab . '.' . $fld . ' AS ' . $field;
                            }
                        }
                    }
                }
            }
        }
        return $fields;
    }

    /**
     * Retourne la requête SQL
     *
     * @param array   $p_fields
     * @param boolean $p_with_prefix
     *
     * @return string|boolean
     */
    public function getSQLQuery($p_fields = [], $p_with_prefix = false, $p_has_calc_sql_found_rows = true)
    {
        $class = $this->getModelClass();
        if ($class !== false) {
            $data   = $class::getSource();
            $cols   = array_flip($class::columnMap());
            $lim    = false;
            $clause = $this->getClause();
            $where  = ' WHERE ' . $clause['sql'];
            $fields = $clause['fields'];
            $limit  = '';
            $order  = '';
            // Tri ??
            if (count($this->sorted) > 0) {
                foreach ($this->sorted as $idx => $sort) {
                    switch (strtoupper($sort['order'])) {
                        case '':
                        case 'ASC':
                            $tmporder = $this->getRealField($sort['field']) . ' ' . $sort['order'];
                            break;
                        case 'DESC':
                            $tmporder = $this->getRealField($sort['field']) . ' ' . $sort['order'];
                            break;
                        default:
                            if (substr($sort['order'], 0, 1) == '-') {
                                $field = substr($sort['order'], 1);
                                $tmporder = $this->getRealField($field) . ' DESC';
                            } else {
                                if (substr($sort['order'], 0, 1) == '+') {
                                    $field = substr($sort['order'], 1);
                                    $tmporder = $this->getRealField($field) . ' ASC';
                                } else {
                                    $field = $sort['order'];
                                    $tmporder = $this->getRealField($field) . ' ASC';
                                }
                            }
                            break;
                    }
                    if ($order == '') {
                        $order = ' ORDER BY ' . $tmporder;
                    } else {
                        $order .= ', ' . $tmporder;
                    }
                }
            }
            // Limite ??
            if ($p_has_calc_sql_found_rows && $this->limit > 0) {
                $lim   = true;
                if ($this->start !== false) {
                    $limit = ' LIMIT ' . $this->start . ', ' . $this->limit;
                } else {
                    $limit = ' LIMIT ' . $this->limit;
                }
            }
            //
            $from = $this->getFrom();
            // Go pour la requête SQL
            if (is_array($p_fields) && count($p_fields)>0) {
                $first = true;
                $sel   = '';
                foreach ($p_fields as $fld) {
                    if ($first) {
                        $first = false;
                    } else {
                        $sel .= ', ';
                    }
                    $sel .= $fld;
                }
                $sql = 'SELECT ' . $sel . ' ' . $from . ' ' . $where . ' ' .
                       $order . ' ' . $limit;
            } else {
                switch ($this->type) {
                    case self::DELETE:
                        $sql = 'DELETE ' . $from . ' ' . $where;
                        break;
                    case self::SELECT_DISTINCT:
                        if ($this->select_fields) {
                            $sel = $this->getSelectFields($p_with_prefix);
                        } else {
                            $sel = $data . '.*';
                        }
                        $data = $class::getSource();
                        if ($p_has_calc_sql_found_rows) {
                            $sql  = 'SELECT SQL_CALC_FOUND_ROWS DISTINCT ' . $sel . ' ' .
                                    $from . ' ' . $where . ' ' . $order . ' ' . $limit;
                        } else {
                            $sql  = 'SELECT DISTINCT ' . $sel . ' ' .
                                $from . ' ' . $where . ' ' . $order . ' ' . $limit;
                        }
                        break;
                    default:
                        if ($this->select_fields) {
                            $sel = $this->getSelectFields($p_with_prefix);
                        } else {
                            $sel = $data . '.*';
                        }
                        if ($p_has_calc_sql_found_rows) {
                            $sql = 'SELECT SQL_CALC_FOUND_ROWS ' . $sel . ' ' . $from . ' ' . $where . ' ' .
                                   $order . ' ' . $limit;
                        } else {
                            $sql = 'SELECT ' . $sel . ' ' . $from . ' ' . $where . ' ' .
                                $order . ' ' . $limit;
                        }
                        break;
                }
            }
            //var_dump($sql, $fields);
            return array('sql' => $sql, 'fields' => $fields, 'limit' => $lim);
        }
        return false;
    }

    /**
     * Génération du résultat de la requête
     *
     * @return \FreeFW\Model\ResultSet
     */
    public function getResult()
    {
        $class     = $this->getModelClass();
        $resultSet = new \FreeFW\Model\ResultSet();
        $pdo       = self::getConnexion($class::getCnxName());
        $sqlQuery  = $this->getSQLQuery([], false, $pdo->hasSqlCalcFoundRows());
        //var_dump($sqlQuery);
        if ($sqlQuery !== false) {
            $count = 0;
            $query = $pdo->prepare($sqlQuery['sql'], array(\PDO::ATTR_CURSOR => \PDO::CURSOR_FWDONLY));
            try {
                self::debug('simpleQuery.sql : ' . $sqlQuery['sql']);
                self::debug('simpleQuery.sql : ' . json_encode($sqlQuery['fields']));
                $pdores = $query->execute($sqlQuery['fields']);
            } catch (\Exception $ex) {
                // @todo
                //var_dump($ex);
                //die('ici');
            }
            if ($pdores !== false) {
                while ($row = $query->fetch(\PDO::FETCH_OBJ)) {
                    $count       += 1;
                    $record      = $class::getInstance($row);
                    $resultSet[] = $record;
                }
                if ($sqlQuery['limit'] === true) {
                    // Récupération du total général
                    $count_result = $pdo->prepare("SELECT FOUND_ROWS() as FOUND_ROWS");
                    $count_result->execute();
                    $count = intval($count_result->fetch(\PDO::FETCH_OBJ)->FOUND_ROWS);
                }
                $resultSet->setTotalCount($count);
            } else {
                self::error(print_r($sqlQuery, true));
                $resultSet->setError();
            }
        }
        return $resultSet;
    }

    /**
     * Exécution de la requête
     *
     * @return number
     */
    public function execute()
    {
        $class     = $this->getModelClass();
        $resultSet = new \FreeFW\Model\ResultSet();
        $pdo       = self::getConnexion();
        $sqlQuery  = $this->getSQLQuery([], false, $pdo->hasSqlCalcFoundRows());
        if ($sqlQuery !== false) {
            $count  = 0;

            $query  = $pdo->prepare($sqlQuery['sql'], array(\PDO::ATTR_CURSOR => \PDO::CURSOR_FWDONLY));
            try {
                self::debug('simpleQuery.sql : ' . $sqlQuery['sql']);
                self::debug('simpleQuery.sql : ' . json_encode($sqlQuery['fields']));
                $pdores = $query->execute($sqlQuery['fields']);
            } catch (\Exception $ex) {
                // @todo
                var_dump($ex);
                die('ici');
            }
            return $pdores;
        }
        return false;
    }
}
