<?php
/**
 * Classe de gestion de la partie Where
 *
 * @author jeromeklam
 * @package SQL
 */
namespace FreeFW\Model;

/**
 * Gestion de la partie where d'une requête
 * @author jeromeklam
 */
class Where
{

    /**
     * Comportements
     */
    use \FreeFW\Behaviour\DI;
    use \FreeFW\Behaviour\LoggerAwareTrait;

    /**
     * Opérateurs
     * @var string
     */
    const OPER_AND = 'AND';
    const OPER_OR  = 'OR';

    /**
     * Ordres de tri
     * @var string
     */
    const SORT_ASC  = 'ASC';
    const SORT_DESC = 'DESC';

    /**
     * Types de requête
     * @var string
     */
    const SELECT_ALL      = 'ALL';
    const SELECT_DISTINCT = 'DISTINCT';
    const DELETE          = 'DELETE';

    /**
     * Liste des clauses
     * @var string
     */
    const CLAUSE_EMPTY_FIELD     = 'ISNULL';
    const CLAUSE_NOT_EMPTY_FIELD = 'NOTNULL';
    const CLAUSE_FIELD_EQUAL     = '=';
    const CLAUSE_FIELD_JEQUAL    = 'J=';
    const CLAUSE_FIELD_NOT_EQUAL = '!=';
    const CLAUSE_LOWER_EQUAL     = '<=';
    const CLAUSE_GREATER_EQUAL   = '>=';
    const CLAUSE_LOWER           = '<';
    const CLAUSE_GREATER         = '>';
    const CLAUSE_BEGIN_WITH      = 'BEGIN';
    const CLAUSE_END_WITH        = 'END';
    const CLAUSE_LIKE            = 'LIKE';
    const CLAUSE_LIKES           = 'LIKES';
    const CLAUSE_HAS_DEC_PART    = 'DECP';
    const CLAUSE_IN              = 'IN';
    const CLAUSE_NOT_IN          = 'NIN';

    /**
     * Types de jointure
     *
     * @var string
     */
    const JOIN_INNER  = 'INNER JOIN';
    const JOIN_LEFT   = 'LEFT JOIN';
    const JOIN_RIGHT  = 'RIGHT JOIN';

    /**
     * Opérateur principal
     *
     * @var string
     */
    protected $main_oper = self::OPER_AND;

    /**
     * Parties du where
     *
     * @var array
     */
    protected $clauses = array();

    /**
     * Nom de la classe à retourner
     *
     * @var string
     */
    protected $class_name = null;

    /**
     * Les autres classes à ajouter dans le select
     * @var array
     */
    protected $others = false;

    /**
     * cache
     *
     * @var array
     */
    protected static $class_caches = array();

    /**
     * Constructeur
     *
     * @param string $p_classname
     * @param string $p_type
     */
    protected function __construct($p_classname, $p_type = self::SELECT_ALL, $p_others = false)
    {
        $this->class_name = $p_classname;
        $this->type       = $p_type;
        $this->others     = $p_others;
    }

    /**
     * Retourne une instance de classe
     *
     * @param string  $p_name
     * @param boolean $p_new
     *
     * @return Model
     */
    protected function getModelClass($p_name = null, $p_new = true)
    {
        $orig = $p_name;
        if ($p_name == null) {
            $p_name = $this->class_name;
        }
        if (strpos($p_name, '::') !== false) {
            $model1 = explode('::', $p_name);
            if (strpos($model1[0], '.') !== false) {
                $ns = implode('\\', explode('.', $model1[0]));
            } else {
                $ns = $model1[0];
            }
            $p_name = '\\' . $ns . '\\Model\\' . $model1[1];
        }
        if (class_exists($p_name)) {
            if ($p_new) {
                return new $p_name();
            } else {
                return $p_name;
            }
        } else {
            throw new \Exception(sprintf('Class %s doesn\'t exists %s !', $p_name, $orig));
        }
        return false;
    }

    /**
     * Get bindName
     *
     * @return string
     */
    protected function getBindName()
    {
        $letter = chr(65+rand(0,25));
        return $letter . '_' . uniqid();
    }

    /**
     * Affectation de l'opérateur principal
     *
     * @param string $p_oper
     *
     * @return \FreeFW\Model\Where
     */
    public function setMainOper($p_oper)
    {
        $this->main_oper = $p_oper;
        return $this;
    }

    /**
     * Retourne l'opérateur principal
     *
     * @return string
     */
    public function getMainOper()
    {
        return $this->main_oper;
    }

    /**
     * Ajout d'une clause
     *
     * @param string  $p_type
     * @param string  $p_field1
     * @param mixed   $p_value1
     * @param boolean $p_allowNull
     *
     * @return \FreeFW\Model\Where
     */
    public function addClause($p_type, $p_field1 = null, $p_value1 = null, $p_allowNull = false)
    {
        $this->clauses[] = array(
            'type'   => $p_type,
            'field1' => $p_field1,
            'value1' => $p_value1,
            'null'   => $p_allowNull
        );
        return $this;
    }

    /**
     * Retourne le nom du vrai champ....
     *
     * @param string $p_field
     *
     * @return string
     */
    protected function getRealField($p_field)
    {
        $parts = explode('::', $p_field);
        if (count($parts) == 2) {
            if (strpos($parts[0], '.') !== false) {
                $ns = implode('\\', explode('.', $parts[0]));
            } else {
                $ns = $parts[0];
            }
            $parts = explode('.', $parts[1]);
            $model = $parts[0];
            $field = $parts[1];
            $class = '\\' . $ns . '\\Model\\' . $model;
        } else {
            $class = $this->getModelClass(null, false);
            if ($class === false) {
                // @todo
                throw new \Exception('SQL Where : wrong parameters...');
            }
            $field = $p_field;
        }
        if (!array_key_exists($class, self::$class_caches)) {
            if (class_exists($class)) {
                $cls = new $class();
                $src = $cls->getSource();
                if (method_exists($class, 'getSourceAlias')) {
                    $src = $class::getSourceAlias();
                }
                self::$class_caches[$class] = array(
                    'src' => $src,
                    'map' => $cls::columnMap()
                );
            } else {
                //var_dump($class);die('error');
            }
        }
        $tab = self::$class_caches[$class]['src'];
        $map = self::$class_caches[$class]['map'];
        $map = array_flip($map);
        if (array_key_exists($field, $map)) {
            if (strpos($map[$field], '::') === false) {
                return $tab . '.' . $map[$field];
            } else {
                return $this->getRealField($map[$field]);
            }
        }
        return false;
    }

    /**
     * Ajout au sql
     *
     * @param string $p_sql
     * @param string $p_content
     *
     * @returns string
     */
    protected function addToSql($p_sql, $p_content)
    {
        $p_sql = trim($p_sql);
        if ($p_sql == '') {
            $p_sql = $p_content;
        } else {
            $p_sql .= ' ' . $this->main_oper . ' ' . $p_content;
        }
        return $p_sql;
    }

    /**
     * Indication d'un champ devant être null ou vide
     *
     * @param string $p_property
     *
     * @return \FreeFW\Model\Where
     */
    public function fieldEmpty($p_property)
    {
        $this->addClause(self::CLAUSE_EMPTY_FIELD, $p_property);
        return $this;
    }

    /**
     * Indication d'un champ ne devant pas être null ou vide
     *
     * @param string $p_property
     *
     * @return \FreeFW\Model\Where
     */
    public function fieldNotEmpty($p_property)
    {
        $this->addClause(self::CLAUSE_NOT_EMPTY_FIELD, $p_property);
        return $this;
    }

    /**
     * Egalité
     *
     * @param string  $p_property
     * @param mixed   $p_value
     * @param boolean $p_allowNull
     *
     * @return \FreeFW\Model\Where
     */
    public function fieldEqual($p_property, $p_value, $p_allowNull = false)
    {
        $this->addClause(self::CLAUSE_FIELD_EQUAL, $p_property, $p_value, $p_allowNull);
        return $this;
    }

    /**
     * Egalité dans un join
     *
     * @param string  $p_property
     * @param mixed   $p_value
     * @param boolean $p_allowNull
     *
     * @return \FreeFW\Model\Where
     */
    public function fieldJoinEqual($p_property, $p_value, $p_allowNull = false)
    {
        $this->addClause(self::CLAUSE_FIELD_JEQUAL, $p_property, $p_value, $p_allowNull);
        return $this;
    }

    /**
     * Non égalité
     *
     * @param string  $p_property
     * @param mixed   $p_value
     * @param boolean $p_allowNull
     *
     * @return \FreeFW\Model\Where
     */
    public function fieldNotEqual($p_property, $p_value, $p_allowNull = false)
    {
        $this->addClause(self::CLAUSE_FIELD_NOT_EQUAL, $p_property, $p_value, $p_allowNull);
        return $this;
    }

    /**
     * Egalité
     *
     * @param string  $p_property
     *
     * @return \FreeFW\Model\Where
     */
    public function fieldHasDecimal($p_property)
    {
        $this->addClause(self::CLAUSE_HAS_DEC_PART, $p_property, null, false);
        return $this;
    }

    /**
     * Commence par
     *
     * @param string  $p_property
     * @param mixed   $p_value
     * @param boolean $p_allowNull
     *
     * @return \FreeFW\Model\Where
     */
    public function fieldBeginWith($p_property, $p_value)
    {
        $this->addClause(self::CLAUSE_BEGIN_WITH, $p_property, $p_value, false);
        return $this;
    }

    /**
     * Termine par
     *
     * @param string  $p_property
     * @param mixed   $p_value
     * @param boolean $p_allowNull
     *
     * @return \FreeFW\Model\Where
     */
    public function fieldEndWith($p_property, $p_value)
    {
        $this->addClause(self::CLAUSE_END_WITH, $p_property, $p_value, false);
        return $this;
    }

    /**
     * Ressemble
     *
     * @param string  $p_property
     * @param mixed   $p_value
     * @param boolean $p_allowNull
     *
     * @return \FreeFW\Model\Where
     */
    public function fieldLike($p_property, $p_value)
    {
        $this->addClause(self::CLAUSE_LIKE, $p_property, $p_value, false);
        return $this;
    }

    /**
     * Ressemble
     *
     * @param array   $p_property
     * @param mixed   $p_value
     * @param boolean $p_allowNull
     *
     * @return \FreeFW\Model\Where
     */
    public function fieldsLike($p_property, $p_value)
    {
        $this->addClause(self::CLAUSE_LIKES, $p_property, $p_value, false);
        return $this;
    }

    /**
     * <=
     *
     * @param string  $p_property
     * @param mixed   $p_value
     * @param boolean $p_allowNull
     *
     * @return \FreeFW\Model\Where
     */
    public function fieldLowerEqual($p_property, $p_value, $p_allowNull = false)
    {
        $this->addClause(self::CLAUSE_LOWER_EQUAL, $p_property, $p_value, $p_allowNull);
        return $this;
    }

    /**
     * >=
     *
     * @param string  $p_property
     * @param mixed   $p_value
     * @param boolean $p_allowNull
     *
     * @return \FreeFW\Model\Where
     */
    public function fieldGreaterEqual($p_property, $p_value, $p_allowNull = false)
    {
        $this->addClause(self::CLAUSE_GREATER_EQUAL, $p_property, $p_value, $p_allowNull);
        return $this;
    }

    /**
     * <
     *
     * @param string  $p_property
     * @param mixed   $p_value
     * @param boolean $p_allowNull
     *
     * @return \FreeFW\Model\Where
     */
    public function fieldLower($p_property, $p_value, $p_allowNull = false)
    {
        $this->addClause(self::CLAUSE_LOWER, $p_property, $p_value, $p_allowNull);
        return $this;
    }

    /**
     * >=
     *
     * @param string  $p_property
     * @param mixed   $p_value
     * @param boolean $p_allowNull
     *
     * @return \FreeFW\Model\Where
     */
    public function fieldGreater($p_property, $p_value, $p_allowNull = false)
    {
        $this->addClause(self::CLAUSE_GREATER, $p_property, $p_value, $p_allowNull);
        return $this;
    }

    /**
     * In
     *
     * @param array   $p_property
     * @param mixed   $p_value
     *
     * @return \FreeFW\Model\Where
     */
    public function fieldIn($p_property, $p_value)
    {
        $this->addClause(self::CLAUSE_IN, $p_property, $p_value, false);
        return $this;
    }

    /**
     * Not in
     *
     * @param array   $p_property
     * @param mixed   $p_value
     *
     * @return \FreeFW\Model\Where
     */
    public function fieldNotIn($p_property, $p_value)
    {
        $this->addClause(self::CLAUSE_NOT_IN, $p_property, $p_value, false);
        return $this;
    }
    /**
     * Retourne la clase (sql + champs)
     *
     * @return array
     */
    public function getClause()
    {
        $sql    = '';
        $fields = array();
        $class  = $this->getModelClass();
        if (method_exists($class, 'columnFilter')) {
            foreach ($class::columnFilter() as $prop => $filter) {
                if (is_array($filter)) {
                    foreach ($filter as $clause1 => $value1) {
                        $this->addClause($clause1, $prop, $value1);
                    }
                } else {
                    $this->addClause(self::CLAUSE_FIELD_EQUAL, $prop, $filter);
                }
            }
        }
        foreach ($this->clauses as $idx => $clause) {
            switch ($clause['type']) {
                case self::CLAUSE_EMPTY_FIELD:
                    $fld = $this->getRealField($clause['field1']);
                    if ($fld) {
                        $sql = $this->addToSql($sql, '(' . $fld . ' IS NULL OR ' . $fld . ' = \'\')');
                    }
                    break;
                case self::CLAUSE_NOT_EMPTY_FIELD:
                    $fld = $this->getRealField($clause['field1']);
                    if ($fld) {
                        $sql = $this->addToSql($sql, '(' . $fld . ' IS NOT NULL AND ' . $fld . ' != \'\')');
                    }
                    break;
                case self::CLAUSE_BEGIN_WITH:
                    $fld            = $this->getRealField($clause['field1']);
                    if ($fld) {
                        $replacePattern = $this->getBindName();
                        $sql = $this->addToSql($sql, $fld . ' LIKE :' . $replacePattern);
                        $fields[':' . $replacePattern] = $clause['value1'] . '%';
                    }
                    break;
                case self::CLAUSE_END_WITH:
                    $fld            = $this->getRealField($clause['field1']);
                    if ($fld) {
                        $replacePattern = $this->getBindName();
                        $sql = $this->addToSql($sql, $fld . ' LIKE :' . $replacePattern);
                        $fields[':' . $replacePattern] = '%' . $clause['value1'];
                    }
                    break;
                case self::CLAUSE_LIKE:
                    $fld            = $this->getRealField($clause['field1']);
                    if ($fld) {
                        $replacePattern = $this->getBindName();
                        $sql = $this->addToSql($sql, $fld . ' LIKE :' . $replacePattern);
                        $fields[':' . $replacePattern] = '%' . $clause['value1'] . '%';
                    }
                    break;
                case self::CLAUSE_LIKES:
                    $sql1           = '';
                    $replacePattern = $this->getBindName();
                    foreach ($clause['field1'] as $idx1 => $fld1) {
                        $fld = $this->getRealField($fld1);
                        if ($sql1 != '') {
                            $sql1 = $sql1 . ' OR ';
                        }
                        $sql1 = $sql1 . $fld . ' LIKE :' . $replacePattern;
                    }
                    $sql = $this->addToSql($sql, ' ( ' . $sql1 . ' ) ');
                    $fields[':' . $replacePattern] = '%' . $clause['value1'] . '%';
                    break;
                case self::CLAUSE_HAS_DEC_PART:
                    $fld = $this->getRealField($clause['field1']);
                    $sql = $this->addToSql($sql, ' (' . $fld . ' - FLOOR(' . $fld . ')) > 0');
                    break;
                case self::CLAUSE_NOT_IN:
                case self::CLAUSE_IN:
                    $fld  = $this->getRealField($clause['field1']);
                    $sql1 = '';
                    foreach ($clause['value1'] as $idx1 => $val1) {
                        $replacePattern = $this->getBindName();
                        $fields[':' . $replacePattern] = $val1;
                        if ($sql1 == '') {
                            $sql1 = ':' . $replacePattern;
                        } else {
                            $sql1 = $sql1 . ', :' . $replacePattern;
                        }
                    }
                    if ($clause['type'] == self::CLAUSE_IN) {
                        $sql = $this->addToSql($sql, $fld . ' IN (' . $sql1 . ')');
                    } else {
                        $sql = $this->addToSql($sql, $fld . ' NOT IN (' . $sql1 . ')');
                    }
                    break;
                case self::CLAUSE_FIELD_EQUAL:
                case self::CLAUSE_FIELD_NOT_EQUAL:
                case self::CLAUSE_GREATER:
                case self::CLAUSE_LOWER:
                case self::CLAUSE_LOWER_EQUAL:
                case self::CLAUSE_GREATER_EQUAL:
                    $fld  = $this->getRealField($clause['field1']);
                    $fld2 = false;
                    if (strpos($clause['value1'], '::') !== false) {
                        try {
                            $fld2 = $this->getRealField($clause['value1']);
                        } catch (\Exception $ex) {
                            $fld2 = false;
                        }
                    }
                    if ($fld2 == false) {
                        $replacePattern = $this->getBindName();
                        $sqlTmp         = $fld . ' ' . $clause['type'] . ' :' . $replacePattern;
                        if ($clause['null'] == true) {
                            $sqlTmp = '(' . $sqlTmp . ' OR ' . $fld . ' IS NULL)';
                        }
                        $sql = $this->addToSql($sql, $sqlTmp);
                        $fields[':' . $replacePattern] = $clause['value1'];
                    } else {
                        $sqlTmp = $fld . ' ' . $clause['type'] . ' ' . $fld2;
                        $sql    = $this->addToSql($sql, $sqlTmp);
                    }
                    break;
            }
        }
        if (trim($sql) != '') {
            $sql = ' ( ' . $sql . ' ) ';
        } else {
            $sql = ' ( 1=1 ) ';
        }
        return array(
            'sql'    => $sql,
            'fields' => $fields
        );
    }
}
