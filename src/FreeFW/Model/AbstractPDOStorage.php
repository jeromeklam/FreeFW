<?php
namespace FreeFW\Model;

use \FreeFW\Tools\PBXString;
use \FreeFW\Interfaces\StorageModel as ModelInterface;
use \FreeFW\ResourceDi as Singleton;

/**
 * Classe de base de gestion avec un SGBD
 *
 * @author jeromeklam
 * @package Storage
 * @package Abstract
 */
abstract class AbstractPDOStorage extends \FreeFW\Model\AbstractStorage
{

    /**
     * Error
     * @var mixed
     */
    protected $pdo_error = false;

    /**
     * Retourne l'erreur PDO
     *
     * @return mixed
     */
    public function getPdoError($p_text = true)
    {
        if ($p_text && is_array($this->pdo_error) && count($this->pdo_error) > 2) {
            return $this->pdo_error[2];
        }
        return print_r($this->pdo_error, true);
    }

    /**
     * Exécution de la requête
     *
     * @param string $p_query
     * @param array  $p_params
     *
     * @return mixed
     */
    public static function execute($p_query, $p_params)
    {
        $ret = false;
        $cnx = self::getDIConnexion(static::getCnxName());
        $sth = $cnx->prepare($p_query);
        if ($sth->execute($p_params)) {
            $ret = array(
                'statement' => $sth,
                'count'     => $sth->rowCount()
            );
            if (strpos($p_query, 'SQL_CALC_FOUND_ROWS') !== false) {
                $rs1          = $cnx->query('SELECT FOUND_ROWS()');
                $ret['count'] = (int) $rs1->fetchColumn();
            }
        } else {
            $sth->debugDumpParams();
            die;
        }
        return $ret;
    }

    /**
     * Recherche
     *
     * @param array  $p_filters   // Tableau de propertyName => value
     * @param array  $p_sortCols  // Tableau de propertyName => ASC/DESC
     * @param array  $p_groupCols // Tableau de propertyName
     * @param number $p_from      // Indice de départ, commence à 0
     * @param number $p_len       // Longueur de recherche, 0 pour illimité
     * @param string $p_fulltext  // Chaine à rechercher dans les champs type fulltext
     * @param string $p_included  // Liste des éléments à inclure
     * @param string $p_fields    // Pour limiter une liste de champs
     * @param string $p_mode      // Mode de recherche : EQUAL / LIKE
     * @param string $p_andor     // Clauses : AND / OR
     *
     * @return \Iterable
     */
    public static function find(
        $p_filters = array(),
        $p_sortCols = array(),
        $p_groupCols = array(),
        $p_from = 0,
        $p_len = 0,
        $p_fulltext = null,
        $p_included = "",
        $p_fields = "",
        $p_mode = "EQUAL",
        $p_andor = "AND"
    ) {
        $resultSet = new \FreeFW\Model\ResultSet();
        $class     = self::getModelClass();
        if ($class !== false) {
            $data    = $class::getSource();
            $cols    = array_flip($class::columnMap());
            $where   = '';
            $fields  = array();
            foreach ($p_filters as $property => $value) {
                $realvalue = $value;
                $oper      = self::FIND_EQUAL;
                if (is_array($value)) {
                    foreach ($value as $oper => $realvalue) {
                        break;
                    }
                }
                if ($where == '') {
                    $where = ' WHERE ';
                } else {
                    $where .= ' AND ';
                }
                switch ($oper) {
                    case \FreeFW\Model\AbstractStorage::FIND_LIKE:
                        $where    .= ' ' . $cols[$property] . ' LIKE :' . $property;
                        $realvalue = '%' . $realvalue . '%';
                        //
                        $fields[':' . $property] = $realvalue;
                        break;
                    case \FreeFW\Model\AbstractStorage::FIND_NOT_EQUAL:
                        $where    .= ' ' . $cols[$property] . ' <> :' . $property;
                        //
                        $fields[':' . $property] = $realvalue;
                        break;
                    case \FreeFW\Model\AbstractStorage::FIND_GREATER:
                    case \FreeFW\Model\AbstractStorage::FIND_LOWER:
                    case \FreeFW\Model\AbstractStorage::FIND_GREATER_EQUAL:
                    case \FreeFW\Model\AbstractStorage::FIND_LOWER_EQUAL:
                        $where    .= ' ' . $cols[$property] . ' ' . $oper . ' :' . $property;
                        //
                        $fields[':' . $property] = $realvalue;
                        break;
                    case \FreeFW\Model\AbstractStorage::FIND_EMPTY:
                        $where    .= ' ( ' . $cols[$property] . ' = \'\' OR ' . $cols[$property] . ' IS NULL )';
                        break;
                    case \FreeFW\Model\AbstractStorage::FIND_NOT_EMPTY:
                        $where    .= ' ( ' . $cols[$property] . ' != \'\' AND ' . $cols[$property] . ' IS NOT NULL )';
                        break;
                    case \FreeFW\Model\AbstractStorage::BEGIN_WITH:
                        $where    .= ' ' . $cols[$property] . ' LIKE :' . $property;
                        $realvalue = $realvalue . '%';
                        //
                        $fields[':' . $property] = $realvalue;
                        break;
                    case \FreeFW\Model\AbstractStorage::FIND_IN:
                        $properties = null;
                        $cptr       = 1;
                        foreach ($realvalue as $tmpVal) {
                            $fields[':' . $property . $cptr] = $tmpVal;
                            if ($properties === null) {
                                $properties = ':' . $property . $cptr;
                            } else {
                                $properties .= ', :' . $property . $cptr;
                            }
                            $cptr++;
                        }
                        $where    .= ' ' . $cols[$property] . ' IN (' . $properties . ')';
                        break;
                    case \FreeFW\Model\AbstractStorage::FIND_NOT_IN:
                        $properties = null;
                        $cptr       = 1;
                        foreach ($realvalue as $tmpVal) {
                            $fields[':' . $property . $cptr] = $tmpVal;
                            if ($properties === null) {
                                $properties = ':' . $property . $cptr;
                            } else {
                                $properties .= ', :' . $property . $cptr;
                            }
                            $cptr++;
                        }
                        $where    .= ' ' . $cols[$property] . ' NOT IN (' . $properties . ')';
                        break;
                    default:
                        $where .= ' ' . $cols[$property] . ' = :' . $property;
                        //
                        $fields[':' . $property] = $realvalue;
                        break;
                }
            }
            if ($p_fulltext != null) {
                $fulltext = $class::columnFulltext();
                if (count($fulltext) > 0) {
                    if ($where == '') {
                        $where = ' WHERE ( ';
                    } else {
                        $where = ' AND (';
                    }
                    $first = true;
                    $fields[':fulltextsearch'] = '%' . $p_fulltext . '%';
                    foreach ($fulltext as $property) {
                        if ($first) {
                            $first = false;
                            $where .= $cols[$property] . ' LIKE :fulltextsearch';
                        } else {
                            $where .= ' OR ' . $cols[$property] . ' LIKE :fulltextsearch';
                        }
                    }
                    $where .= ' ) ';
                }
            }
            // Partie GROUP BY
            $group = '';
            foreach ($p_groupCols as $field) {
                if ($group == '') {
                    $group = 'GROUP BY ' . $cols[$field];
                } else {
                    $group .= ', ' . $cols[$field];
                }
            }
            // Partie ORDER BY
            $order = '';
            foreach ($p_sortCols as $field => $sortOrder) {
                $o = '';
                switch (strtoupper($sortOrder)) {
                    case \FreeFW\Model\AbstractStorage::SORT_ASC:
                        $o = 'ASC';
                        break;
                    case \FreeFW\Model\AbstractStorage::SORT_DESC:
                        $o = 'DESC';
                        break;
                    default:
                        if (substr($sortOrder, 0, 1) == '-') {
                            $o     = 'DESC';
                            $field = substr($sortOrder, 1);
                        } else {
                            if (substr($sortOrder, 0, 1) == '+') {
                                $o     = 'ASC';
                                $field = substr($sortOrder, 1);
                            } else {
                                $o     = 'ASC';
                                $field = $sortOrder;
                            }
                        }
                }
                if ($order == '') {
                    $order = 'ORDER BY ' . $cols[$field] . ' ' . $o;
                } else {
                    $order .= ', ' . $cols[$field] . ' ' . $o;
                }
            }
            // Consitution de la requête
            $pdo = self::getDIConnexion($class::getCnxName());
            // @todo -- SQL_CALC_FOUND_ROWS : mysql !!!!
            if ($pdo->hasSqlCalcFoundRows()) {
                $sql = 'SELECT SQL_CALC_FOUND_ROWS * FROM ' . $data . ' ' . $where . ' ' . $group . ' ' . $order;
            } else {
                $sql = 'SELECT * FROM ' . $data . ' ' . $where . ' ' . $group . ' ' . $order;
            }

            $lim = false;
            if ($p_from >= 0 && $p_len > 0) {
                if (!is_int($p_from)) {
                    $p_from = 0;
                }
                if (!is_int($p_len)) {
                    $p_len = 10;
                }
                $sql = $sql . ' LIMIT ' . $p_from . ', ' . $p_len;
                $lim = true;
            }
            $fields = self::checkFields($fields);
            $count  = 0;
            $query  = $pdo->prepare($sql, array(\PDO::ATTR_CURSOR => \PDO::CURSOR_FWDONLY));
            $pdores = $query->execute($fields);
            if (self::isDebugLevel()) {
                self::debug('PDO.find.sql : ' . $sql);
                self::debug('PDO.find.fields : ' . json_encode($fields));
            }
            if ($pdores !== false) {
                $getter = false;
                $ids    = $class::columnId();
                if (count($ids) == 1) {
                    foreach ($ids as $prp => $val) {
                        if (is_array($val)) {
                            $getter = 'get' . \FreeFW\Tools\PBXString::toCamelCase($prp, true);
                        } else {
                            $getter = 'get' . \FreeFW\Tools\PBXString::toCamelCase($val, true);
                        }
                        break;
                    }
                }
                while ($row = $query->fetch(\PDO::FETCH_OBJ)) {
                    $count  += 1;
                    $record  = $class::getInstance($row);
                    if ($getter !== false) {
                        $resultSet[$record->{$getter}()] = $record;
                    } else {
                        $resultSet[] = $record;
                    }
                }
                if ($lim === true) {
                    // Récupération du total général
                    $count_result = $pdo->prepare("SELECT FOUND_ROWS() as FOUND_ROWS");
                    $count_result->execute();
                    $count = intval($count_result->fetch(\PDO::FETCH_OBJ)->FOUND_ROWS);
                }
                $resultSet->setTotalCount($count);
            } else {
                $resultSet->setError();
            }
        }

        return $resultSet;
    }

    /**
     * Recherche le premier
     *
     * @param array $p_filters   // Tableau de propertyName => value
     * @param array $p_sortCols  // Tableau de propertyName => ASC/DESC
     * @param array $p_groupCols // Tableau de propertyName
     *
     * @return \Iterator
     */
    public static function findFirst($p_filters = array(), $p_sortCols = array(), $p_groupCols = array())
    {
        return self::find($p_filters, $p_sortCols, $p_groupCols, 0, 1);
    }

    /**
     * Retourne le premier
     *
     * @param array $p_filters   // Tableau de propertyName => value
     * @param array $p_sortCols  // Tableau de propertyName => ASC/DESC
     * @param array $p_groupCols // Tableau de propertyName
     * @param mixed $p_default
     *
     * @return object
     */
    public static function getFirst(
        $p_filters = array(),
        $p_sortCols = array(),
        $p_groupCols = array(),
        $p_default = false
    ) {
        $list = static::find($p_filters, $p_sortCols, $p_groupCols, 0, 1);
        if (count($list) >= 1) {
            $list->rewind();
            return $list->current();
        }
        return $p_default;
    }

    /**
     * Retourne un enregistrement en fonction de son identifiant(s)
     *
     * @param array $p_values
     * @param mixed $p_default
     *
     * @return object
     */
    public static function getById($p_values = array(), $p_default = false)
    {
        if (!is_array($p_values)) {
            $ids      = static::columnId();
            $p_values = array($ids[0] => $p_values);
        }
        $list = self::findById($p_values);
        if (count($list) == 1) {
            $list->rewind();
            return $list->current();
        }
        return $p_default;
    }

    /**
     * Retourne un enregistrement en fonction de son identifiant(s)
     *
     * @param array $p_values
     *
     * @return object
     */
    public static function findById($p_values = array())
    {
        if (!is_array($p_values)) {
            $p_values = array('id' => $p_values);
        }
        $resultSet = new \FreeFW\Model\ResultSet();
        $class     = self::getModelClass();
        if ($class !== false) {
            $data   = $class::getSource();
            $cols   = array_flip($class::columnMap());
            $ids    = $class::columnId();
            $where  = '';
            $fields = array();
            foreach ($p_values as $field => $value) {
                if (count($ids) > 1) {
                    // Identifiant multi colonnes
                    $parts = explode('__', $value);
                    $idx   = 0;
                    foreach ($idFlds as $idF) {
                        if ($where == '') {
                            $where = ' WHERE ' . $cols[$idF] . ' = :' . $idF;
                        } else {
                            $where .= ' AND ' . $cols[$idF] . ' = :' . $idF;
                        }
                        $fields[':' . $idF] = $parts[$idx];
                        $idx++;
                    }
                } else {
                    if ($where == '') {
                        $where = ' WHERE ' . $cols[$field] . ' = :' . $field;
                    } else {
                        $where .= ' AND ' . $cols[$field] . ' = :' . $field;
                    }
                    $fields[':' . $field] = $value;
                }
            }
            $sql   = 'SELECT * FROM ' . $data . $where;
            $pdo   = self::getDIConnexion(static::getCnxName());
            $query = $pdo->prepare($sql, array(\PDO::ATTR_CURSOR => \PDO::CURSOR_FWDONLY));
            $query->execute($fields);
            while ($row = $query->fetch(\PDO::FETCH_OBJ)) {
                $record      = $class::getInstance($row);
                $resultSet[] = $record;
            }
        }
        return $resultSet;
    }

    /**
     * Enregistrement du résultat : update
     *
     * @param boolean $p_raw
     * @param boolean $p_withTransaction
     *
     * @return boolean
     */
    public function save($p_raw = false, $p_withTransaction = true)
    {
        $class  = self::getModelClass();
        $pdo    = self::getDIConnexion(static::getCnxName());
        $result = false;
        if ($class !== false) {
            if ($p_withTransaction) {
                $pdo->startTransaction();
            }
            $next = true;
            if (method_exists($this, 'beforeSave') && $p_raw === false) {
                $next = $this->beforeSave();
            }
            if ($next) {
                $data    = $class::getSource();
                $cols    = array_flip($class::columnMap());
                $ids     = $class::columnId();
                $set     = '';
                $where   = '';
                $content = '';
                $fields  = array();
                foreach ($cols as $property => $field) {
                    $getter = 'get' . \FreeFW\Tools\PBXString::toCamelCase($property, true);
                    if (in_array($property, $ids)) {
                        if ($where == '') {
                            $where = ' WHERE ' . $cols[$property] . ' = :' . $property;
                        } else {
                            $where .= ' AND ' . $cols[$property] . ' = :' . $property;
                        }
                        $fields[':' . $property] = $this->$getter();
                    } else {
                        if ($set == '') {
                            $set = ' SET ' . $cols[$property] . ' = :' . $property;
                        } else {
                            $set .= ', ' . $cols[$property] . ' = :' . $property;
                        }
                        $fields[':' . $property] = $this->$getter();
                    }
                }
                if ($where == '') {
                    // Logiquement ne doit pas arriver...
                    foreach ($ids as $key => $idFlds) {
                        if (is_array($idFlds) && count($idFlds) > 0) {
                            // Identifiant multi colonnes
                            foreach ($idFlds as $idF) {
                                $getter = 'get' . \FreeFW\Tools\PBXString::toCamelCase($idF, true);
                                $value  = $this->$getter();
                                if ($where == '') {
                                    $where = ' WHERE ' . $cols[$idF] . ' = :' . $idF;
                                } else {
                                    $where .= ' AND ' . $cols[$idF] . ' = :' . $idF;
                                }
                                $fields[':' . $idF] = $value;
                            }
                        }
                    }
                }
                $sql = 'UPDATE ' . $data . $set . $where;
                if (self::isDebugLevel()) {
                    self::debug('PDO.save.sql : ' . $sql);
                    self::debug('PDO.save.fields : ' . json_encode($fields));
                }
                $query  = $pdo->prepare($sql);
                $result = $query->execute($fields);
                if (method_exists($this, 'afterSave')) {
                    $result = $this->afterSave();
                }
            }
            if ($p_withTransaction) {
                if ($result) {
                    $pdo->commitTransaction();
                } else {
                    $pdo->rollbackTransaction();
                }
            }
        }
        return $result;
    }

    /**
     * Enregistrement du résultat : insert
     *
     * @param boolean $p_withTransaction
     * @param boolean $p_initId
     *
     * @return boolean
     */
    public function create($p_withTransaction = true, $p_initId = true)
    {
        $this->pdo_error = false;
        $result          = false;
        $class           = self::getModelClass();
        $pdo             = self::getDIConnexion(static::getCnxName());
        if ($class !== false) {
            if ($p_withTransaction) {
                $pdo->startTransaction();
            }
            $next = true;
            if (method_exists($this, 'beforeSave')) {
                $next = $this->beforeSave();
            }
            if ($next) {
                $data    = $class::getSource();
                $cols    = array_flip($class::columnMap());
                $ids     = $class::columnId();
                $set     = '';
                $values  = '';
                $content = '';
                $fields  = array();
                $where   = '';
                $idPrp   = false;
                foreach ($cols as $property => $field) {
                    $getter = 'get' . \FreeFW\Tools\PBXString::toCamelCase($property, true);
                    if (!in_array($property, $ids) || $p_initId === false) {
                        if ($content == '') {
                            $content = ':' . $property;
                            $values  = $cols[$property];
                        } else {
                            $content .= ', :' . $property;
                            $values  .= ', ' . $cols[$property];
                        }
                        $fields[":" . $property] = $this->$getter();
                    }
                    if (in_array($property, $ids)) {
                        if ($where == '') {
                            $where = ' WHERE ' . $cols[$property] . ' = :' . $property;
                            $idPrp = array(":" . $property => $this->$getter());
                        } else {
                            $where = $where . ' AND ' . $cols[$property] . ' = :' . $property;
                            $idPrp[":" . $property] = $this->$getter();
                        }
                        if (count($ids) > 1) {
                            if ($content == '') {
                                $content = ':' . $property;
                                $values  = $cols[$property];
                            } else {
                                $content .= ', :' . $property;
                                $values  .= ', ' . $cols[$property];
                            }
                            $fields[":" . $property] = $this->$getter();
                        }
                    }
                }
                $sql = 'INSERT INTO ' . $data . ' (' . $values . ') VALUES (' . $content . ')';
                if (self::isDebugLevel()) {
                    self::debug('PDO.create.sql : ' . $sql);
                    self::debug('PDO.create.fields : ' . json_encode($fields));
                }
                $query  = $pdo->prepare($sql, array(\PDO::ATTR_CURSOR => \PDO::CURSOR_FWDONLY));
                if ($query->execute($fields)) {
                    // Récupération de l'identifiant
                    if (count($ids) == 1) {
                        foreach ($ids as $prp => $val) {
                            if (is_array($val)) {
                                $setter = 'set' . \FreeFW\Tools\PBXString::toCamelCase($prp, true);
                            } else {
                                $setter = 'set' . \FreeFW\Tools\PBXString::toCamelCase($val, true);
                            }
                            break;
                        }
                        $lastId = $pdo->lastInsertId();
                        $this->$setter($lastId);
                        // Relecture de l'enregistrement si règle bdd...
                        $sql   = 'SELECT * FROM ' . $data . $where;
                        // var_dump($sql);
                        $query = $pdo->prepare($sql, array(\PDO::ATTR_CURSOR => \PDO::CURSOR_FWDONLY));
                        $query->execute($idPrp);
                        while ($row = $query->fetch(\PDO::FETCH_OBJ)) {
                            $record = $this->getFromRecord($row);
                            break;
                        }
                    } else {
                        // @todo...
                    }
                    if (method_exists($this, 'afterCreate')) {
                        $result = $this->afterCreate();
                    } else {
                        $result = true;
                    }
                } else {
                    self::debug('PDO.create.error : ' . print_r($query->errorInfo(), true));
                    $this->pdo_error = $query->errorInfo();
                }
            }
            if ($p_withTransaction) {
                if ($result) {
                    $pdo->commitTransaction();
                } else {
                    $ids    = $class::columnId();
                    $setter = 'set' . \FreeFW\Tools\PBXString::toCamelCase($ids[0], true);
                    $this->$setter(null);
                    $pdo->rollbackTransaction();
                }
            }
        }
        return $result;
    }

    /**
     * Recherche
     *
     * @param array $p_clauses
     * @param array $p_filters // Tableau de propertyName => value
     *
     * @return \Iterable
     */
    public static function update($p_clauses, $p_filters = array())
    {
        $resultSet = array();
        $class     = self::getModelClass();
        if ($class !== false) {
            $data      = $class::getSource();
            $cols      = array_flip($class::columnMap());
            $where     = '';
            $clause    = '';
            $property  = array();
            foreach ($p_filters as $property => $value) {
                $realvalue = $value;
                $oper      = self::FIND_EQUAL;
                if (is_array($value)) {
                    foreach ($value as $oper => $realvalue) {
                        break;
                    }
                }
                if ($where == '') {
                    $where = ' WHERE ';
                } else {
                    $where .= ' AND ';
                }
                switch ($oper) {
                    case \FreeFW\Model\AbstractStorage::FIND_LOWER:
                    case \FreeFW\Model\AbstractStorage::FIND_GREATER:
                    case \FreeFW\Model\AbstractStorage::FIND_LOWER_EQUAL:
                    case \FreeFW\Model\AbstractStorage::FIND_GREATER_EQUAL:
                        $where    .= ' ' . $cols[$property] . ' ' . $oper . ' :' . $property;
                        $realvalue = $realvalue;
                        //
                        $fields[':' . $property] = $realvalue;
                        break;
                    case \FreeFW\Model\AbstractStorage::FIND_LIKE:
                        $where    .= ' ' . $cols[$property] . ' LIKE :' . $property;
                        $realvalue = '%' . $realvalue . '%';
                        //
                        $fields[':' . $property] = $realvalue;
                        break;
                    case \FreeFW\Model\AbstractStorage::FIND_NOT_EQUAL:
                        $where    .= ' ' . $cols[$property] . ' != :' . $property;
                        //
                        $fields[':' . $property] = $realvalue;
                        break;
                    case \FreeFW\Model\AbstractStorage::BEGIN_WITH:
                        $where    .= ' ' . $cols[$property] . ' LIKE :' . $property;
                        $realvalue = $realvalue . '%';
                        //
                        $fields[':' . $property] = $realvalue;
                        break;
                    case \FreeFW\Model\AbstractStorage::FIND_IN:
                        $properties = null;
                        $cptr       = 1;
                        foreach ($realvalue as $tmpVal) {
                            $fields[':' . $property . $cptr] = $tmpVal;
                            if ($properties === null) {
                                $properties = ':' . $property . $cptr;
                            } else {
                                $properties .= ', :' . $property . $cptr;
                            }
                            $cptr++;
                        }
                        $where    .= ' ' . $cols[$property] . ' IN (' . $properties . ')';
                        break;
                    case \FreeFW\Model\AbstractStorage::FIND_NOT_IN:
                        $properties = null;
                        $cptr       = 1;
                        foreach ($realvalue as $tmpVal) {
                            $fields[':' . $property . $cptr] = $tmpVal;
                            if ($properties === null) {
                                $properties = ':' . $property . $cptr;
                            } else {
                                $properties .= ', :' . $property . $cptr;
                            }
                            $cptr++;
                        }
                        $where    .= ' ' . $cols[$property] . ' NOT IN (' . $properties . ')';
                        break;
                    default:
                        $where .= ' ' . $cols[$property] . ' = :' . $property;
                        //
                        $fields[':' . $property] = $realvalue;
                        break;
                }
            }
            foreach ($p_clauses as $property => $value) {
                if (is_array($value)) {
                    $oper = array_keys($value)[0];
                    switch ($oper) {
                        case self::UPDATE_ADD:
                        case self::UPDATE_REMOVE:
                            if ($clause == '') {
                                $clause .= $cols[$property] . ' = ' . $cols[$property] . ' ' . $oper .
                                ' :set' . $property;
                            } else {
                                $clause .= ', ' . $cols[$property] . ' = ' . $cols[$property] . ' ' . $oper .
                                ' :set' . $property;
                            }
                            $fields[':set' . $property] = $value[$oper];
                            break;
                        default:
                            throw new \Exception('Erreur SQL...');
                    }
                } else {
                    if ($clause == '') {
                        $clause .= $cols[$property] . ' = :set' . $property;
                    } else {
                        $clause .= ', ' . $cols[$property] . ' = :set' . $property;
                    }
                    $fields[':set' . $property] = $value;
                }
            }
            // Consitution de la requête
            $sql    = 'UPDATE ' . $data . ' SET ' . $clause . ' ' . $where;
            $fields = self::checkFields($fields);
            $pdo    = self::getDIConnexion(static::getCnxName());
            $query  = $pdo->prepare($sql, array(\PDO::ATTR_CURSOR => \PDO::CURSOR_FWDONLY));
            return $query->execute($fields);
        }
        return false;
    }

    /**
     * Recherche
     *
     * @param array $p_filters // Tableau de propertyName => value
     *
     * @return \Iterable
     */
    public static function delete($p_filters = array())
    {
        $resultSet = array();
        $class     = self::getModelClass();
        if ($class !== false) {
            $data    = $class::getSource();
            $cols    = array_flip($class::columnMap());
            $where   = '';
            $property  = array();
            foreach ($p_filters as $property => $value) {
                $realvalue = $value;
                $oper      = self::FIND_EQUAL;
                if (is_array($value)) {
                    foreach ($value as $oper => $realvalue) {
                        break;
                    }
                }
                if ($where == '') {
                    $where = ' WHERE ';
                } else {
                    $where .= ' AND ';
                }
                switch ($oper) {
                    case \FreeFW\Model\AbstractStorage::FIND_LOWER:
                    case \FreeFW\Model\AbstractStorage::FIND_GREATER:
                    case \FreeFW\Model\AbstractStorage::FIND_LOWER_EQUAL:
                    case \FreeFW\Model\AbstractStorage::FIND_GREATER_EQUAL:
                        $where    .= ' ' . $cols[$property] . ' ' . $oper . ' :' . $property;
                        $realvalue = $realvalue;
                        //
                        $fields[':' . $property] = $realvalue;
                        break;
                    case \FreeFW\Model\AbstractStorage::FIND_LIKE:
                        $where    .= ' ' . $cols[$property] . ' LIKE :' . $property;
                        $realvalue = '%' . $realvalue . '%';
                        //
                        $fields[':' . $property] = $realvalue;
                        break;
                    case \FreeFW\Model\AbstractStorage::FIND_NOT_EQUAL:
                        $where    .= ' ' . $cols[$property] . ' != :' . $property;
                        //
                        $fields[':' . $property] = $realvalue;
                        break;
                    case \FreeFW\Model\AbstractStorage::BEGIN_WITH:
                        $where    .= ' ' . $cols[$property] . ' LIKE :' . $property;
                        $realvalue = $realvalue . '%';
                        //
                        $fields[':' . $property] = $realvalue;
                        break;
                    case \FreeFW\Model\AbstractStorage::FIND_IN:
                        $properties = null;
                        $cptr       = 1;
                        foreach ($realvalue as $tmpVal) {
                            $fields[':' . $property . $cptr] = $tmpVal;
                            if ($properties === null) {
                                $properties = ':' . $property . $cptr;
                            } else {
                                $properties .= ', :' . $property . $cptr;
                            }
                            $cptr++;
                        }
                        $where    .= ' ' . $cols[$property] . ' IN (' . $properties . ')';
                        break;
                    case \FreeFW\Model\AbstractStorage::FIND_NOT_IN:
                        $properties = null;
                        $cptr       = 1;
                        foreach ($realvalue as $tmpVal) {
                            $fields[':' . $property . $cptr] = $tmpVal;
                            if ($properties === null) {
                                $properties = ':' . $property . $cptr;
                            } else {
                                $properties .= ', :' . $property . $cptr;
                            }
                            $cptr++;
                        }
                        $where    .= ' ' . $cols[$property] . ' NOT IN (' . $properties . ')';
                        break;
                    case \FreeFW\Model\AbstractStorage::FIND_BETWEEN:
                        $where .= ' ' . $cols[$property] . ' BETWEEN :start' . $property .
                                     ' AND :end' . $property;
                        //
                        $fields[':start' . $property] = $realvalue[0];
                        $fields[':end' . $property]   = $realvalue[1];
                        break;
                    default:
                        $where .= ' ' . $cols[$property] . ' = :' . $property;
                        //
                        $fields[':' . $property] = $realvalue;
                        break;
                }
            }
            // Consitution de la requête
            $sql    = 'DELETE FROM ' . $data . ' ' . $where;
            $fields = self::checkFields($fields);
            if (self::isDebugLevel()) {
                self::debug('PDO.find.sql : ' . $sql);
                self::debug('PDO.find.fields : ' . json_encode($fields));
            }
            $pdo    = self::getDIConnexion(static::getCnxName());
            $query  = $pdo->prepare($sql, array(\PDO::ATTR_CURSOR => \PDO::CURSOR_FWDONLY));
            return $query->execute($fields);
        }

        return false;
    }

    /**
     * suppression de l'enregistrement courant
     *
     * @param boolean $p_withTransaction
     *
     * @return boolean
     */
    public function remove($p_withTransaction = true)
    {
        $class  = self::getModelClass();
        $result = false;
        $pdo    = self::getDIConnexion(static::getCnxName());
        if ($class !== false) {
            if ($p_withTransaction) {
                $pdo->startTransaction();
            }
            $next = true;
            if (method_exists($this, 'beforeDelete')) {
                $next = $this->beforeDelete();
            }
            if ($next) {
                $data    = $class::getSource();
                $cols    = array_flip($class::columnMap());
                $ids     = $class::columnId();
                $where   = '';
                $content = '';
                $fields  = array();
                foreach ($cols as $property => $field) {
                    $getter = 'get' . \FreeFW\Tools\PBXString::toCamelCase($property, true);
                    if (in_array($property, $ids)) {
                        if ($where == '') {
                            $where = ' WHERE ' . $cols[$property] . ' = :' . $property;
                        } else {
                            $where .= ' AND ' . $cols[$property] . ' = :' . $property;
                        }
                        $fields[$property] = $this->$getter();
                    }
                }
                if ($where == '') {
                    // @todo error
                }
                $sql    = 'DELETE FROM ' . $data . ' ' . $where;
                $query  = $pdo->prepare($sql, array(\PDO::ATTR_CURSOR => \PDO::CURSOR_FWDONLY));
                $result = $query->execute($fields);
                if ($result && method_exists($this, 'afterDelete')) {
                    $result = $this->afterDelete();
                }
            }
            if ($p_withTransaction) {
                if ($result) {
                    $pdo->commitTransaction();
                } else {
                    $pdo->rollbackTransaction();
                }
            }
        }
        return $result;
    }

    /**
     * Enregistrement du résultat : delete
     *
     * gestion de suppression par id simple
     *
     * @return boolean
     */
    public static function deleteById($id)
    {
        //Recuperation du nom
        $class = self::getModelClass();
        $tabl  = $class::columnMap();
        foreach ($tabl as $key => $value) {
            if ($value == "id") {
                $nomIDBdd = $key;
            }
        }
        if ($class !== false) {
            $data  = $class::getSource();
            $sql   = 'DELETE FROM ' . $data . ' WHERE '.$nomIDBdd.' = '. $id;
            $pdo   = self::getDIConnexion(static::getCnxName());
            $query = $pdo->prepare($sql, array(\PDO::ATTR_CURSOR => \PDO::CURSOR_FWDONLY));
        }
        return $query->execute();
    }

    /**
     * Return as array id=>code
     *
     * @param mixed $p_id
     * @param mixed $p_code
     *
     * @return array
     */
    public static function getAsSimpleArray($p_id, $p_code)
    {
        $arr = array();
        $result = self::find(
            array(),
            array($p_code => self::SORT_ASC)
        );
        foreach ($result as $key => $obj) {
            $id   = 'get' . \FreeFW\Tools\PBXString::toCamelCase($p_id, true);
            $code = 'get' . \FreeFW\Tools\PBXString::toCamelCase($p_code, true);
            $arr[] = array(
                'key' => $obj->{$id}(),
                'val' => $obj->{$code}()
            );
        }
        return $arr;
    }

    /**
     * Création d'un tableau de filtres à partir de la requête
     *
     * @param \FreeFW\Interfaces\Request $p_request
     *
     * @return array
     */
    public static function bindFiltersFromRequest($p_request)
    {
        $filters = array();
        $params  = $p_request->getAttributes();
        $cols    = array_flip(static::columnMap());
        foreach ($cols as $name => $field) {
            if (array_key_exists($name, $params)) {
                $filters[$field] = array (
                    self::FIND_LIKE => $params[$name]
                );
            }
        }
        return $filters;
    }

    /**
     * Vérification des valeurs d'un tableau de paramètres
     *
     * @param array $p_fields
     *
     * @return array
     */
    protected static function checkFields($p_fields)
    {
        if (is_array($p_fields)) {
            foreach ($p_fields as $idx => $val) {
                if (is_string($val)) {
                    $p_fields[$idx] = urldecode($val);
                }
            }
        }
        return $p_fields;
    }

    /**
     * Return connexion name
     *
     * @return string
     */
    public static function getCnxName()
    {
        return 'default';
    }
}
