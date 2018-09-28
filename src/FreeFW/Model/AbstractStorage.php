<?php
namespace FreeFW\Model;

use \FreeFW\Tools\PBXString;
use \FreeFW\Interfaces\StorageModel as ModelInterface;

/**
 * Classe de base de gestion d'objets stockés
 *
 * @author jeromeklam
 * @package Storage
 * @package Abstract
 */
abstract class AbstractStorage extends \FreeFW\Model\AbstractNoStorage
{

    /**
     * Modes de recherche
     *
     * @var string
     */
    const FIND_EQUAL                 = '==';
    const FIND_EQUAL_OR_NULL         = '==NULL';
    const FIND_NOT_EQUAL             = '!=';
    const FIND_NOT_EQUAL_OR_NULL     = '!=NULL';
    const FIND_GREATER               = '>';
    const FIND_GREATER_OR_NULL       = '>NULL';
    const FIND_GREATER_EQUAL         = '>=';
    const FIND_GREATER_EQUAL_OR_NULL = '>=NULL';
    const FIND_LOWER                 = '<';
    const FIND_LOWER_OR_NULL         = '<NULL';
    const FIND_LOWER_EQUAL           = '<=';
    const FIND_LOWER_EQUAL_OR_NULL   = '<=NULL';
    const FIND_LIKE                  = '%*%';
    const FIND_IN                    = 'IN';
    const FIND_NOT_IN                = 'NIN';
    const FIND_EMPTY                 = 'EMPTY';
    const FIND_NOT_EMPTY             = 'NEMPTY';
    const FIND_BETWEEN               = 'BETWEEN';
    const BEGIN_WITH                 = '%*';
    const END_WITH                   = '*>';

    /**
     * Tri
     *
     * @var string
     */
    const SORT_ASC  = 'ASC';
    const SORT_DESC = 'DESC';

    /**
     * Retourne le nom de la classe
     *
     * @return string
     */
    protected static function getModelClass()
    {
        return get_called_class();
    }

    /**
     * Retourne le nom de la classe
     *
     * @param string $p_str
     *
     * @return string
     */
    public static function getRealModelClass($p_str)
    {
        $class = '';
        $field = '';
        $part1 = explode('::', $p_str);
        if (count($part1) == 2) {
            $part0 = explode('.', $part1[0]);
            if (count($part0) == 1) {
                $class = '\\' . $part0[0];
            } else {
                $class = '\\' . implode('\\', $part0);
            }
            $class .= '\\Model\\';
            $part2 = explode('.', $part1[1]);
            if (count($part2) >= 1) {
                $class .= $part2[0];
            }
            if (count($part2) > 1) {
                $field = $part2[1];
            }
            return array(
                'class' => $class,
                'field' => $field
            );
        }
        throw new \Exception(sprintf('Can\'t split %s in class.field !', $p_str));
    }

    /**
     * Retourne une nouvelle requête sur le model
     */
    public static function getSimpleQuery()
    {
        return \FreeFW\Model\SimpleQuery::getInstance(self::getModelClass());
    }

    /**
     * @See \FreeFW\Behaviour\Validation
     */
    protected function validate()
    {
        $check = true;
        if (method_exists($this, 'getColumnDescByField')) {
            $fields = $this->getColumnDescByField();
            foreach ($fields as $field => $desc) {
                if (array_key_exists('required', $desc) && $desc['required'] === true) {
                    $getter = $desc['getter'];
                    $value  = $this->$getter();
                    if ($value === null || $value == '') {
                        $check = false;
                        $this->addValidationError($field, '', 'form.field.required');
                        $this->addRequiredFieldError($field, 'form.field.required');
                    }
                }
                if (array_key_exists('key', $desc) && $desc['key'] === true) {
                    // Uniq id ??
                } else {
                    if (array_key_exists('uniq', $desc) && $desc['uniq'] === true) {
                        $ids = $this->columnId();
                        if (is_array($ids) && count($ids) == 1) {
                            $getter  = 'get' . \FreeFW\Tools\PBXString::toCamelCase($ids[0], true);
                            $id      = $this->$getter();
                            $getter  = $desc['getter'];
                            $value   = $this->$getter();
                            $filters = array();
                            $filters[$desc['field']] = $this->$getter();
                            if (intval($id) > 0) {
                                $filters[$ids[0]] = array(\FreeFW\Model\AbstractPDOStorage::FIND_NOT_EQUAL => $id);
                            }
                            $list = $this->find($filters);
                            if ($list->count() > 0) {
                                $check = false;
                                $this->addValidationError($field, '', 'form.field.notunique');
                                $this->addNotUniqueFieldError($field, 'form.field.notunique');
                            }
                        } else {
                            throw new \Exception('Error using uniq property in model...');
                        }
                    }
                }
            }
        }
        return $check;
    }
}
