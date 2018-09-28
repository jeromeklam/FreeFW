<?php
namespace FreeFW\Model;

/**
 *
 * @author klam
 *
 */
abstract class AbstractQueryStorage extends \FreeFW\Model\AbstractPDOStorage
{

    /**
     * Retourne la requête SQL
     *
     * @return string|boolean
     */
    public static function getSimpleQuery()
    {
        $withoutModel = str_replace('\\Model\\', '::', get_called_class());
        $withoutModel = trim($withoutModel, '\\');
        $called       = str_replace('\\', '.', $withoutModel);
        $query        = \FreeFW\Model\SimpleQuery::getInstance($called);
        $sorted       = array();
        $class        = get_called_class();
        if (method_exists($class, 'getQueryOrder')) {
            $sorted = $class::getQueryOrder();
        }
        foreach ($sorted as $col => $order) {
            $query->addSortField($col, $order);
        }
        return $query;
    }

    /**
     * Est une requête ?
     *
     * @return boolean
     */
    public static function isQuery()
    {
        return true;
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
     * @return \Iterator
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
        if (!static::isQuery()) {
            return parent::find(
                $p_filters,
                $p_sortCols,
                $p_groupCols,
                $p_from,
                $p_len,
                $p_fulltext
            );
        }
        $withoutModel = str_replace('\\Model\\', '::', get_called_class());
        $withoutModel = trim($withoutModel, '\\');
        $called       = str_replace('\\', '.', $withoutModel);
        $query        = \FreeFW\Model\SimpleQuery::getInstance(
            $called,
            \FreeFW\Model\SimpleQuery::SELECT_DISTINCT
        );
        // Transfert des infos vers la requête
        $query
            ->setSelectFields(true)
            ->setLimit($p_len, $p_from)
        ;
        // La partie tri
        foreach ($p_sortCols as $col => $sort) {
            $query->addSortField($col, $sort);
        }
        // La partie filtres
        foreach ($p_filters as $property => $value) {
            $realvalue = $value;
            $oper      = self::FIND_EQUAL;
            if (is_array($value)) {
                foreach ($value as $oper => $realvalue) {
                    break;
                }
            } else {
                $realvalue = $value;
            }
            switch ($oper) {
                case \FreeFW\Model\AbstractStorage::FIND_LIKE:
                    $query->fieldLike($property, $realvalue);
                    break;
                case \FreeFW\Model\AbstractStorage::FIND_EQUAL:
                    $query->fieldEqual($property, $realvalue);
                    break;
                case \FreeFW\Model\AbstractStorage::FIND_GREATER:
                    $query->fieldGreater($property, $realvalue);
                    break;
                case \FreeFW\Model\AbstractStorage::FIND_GREATER_OR_NULL:
                    $query->fieldGreater($property, $realvalue, true);
                    break;
                case \FreeFW\Model\AbstractStorage::FIND_LOWER:
                    $query->fieldLower($property, $realvalue);
                    break;
                case \FreeFW\Model\AbstractStorage::FIND_LOWER_OR_NULL:
                    $query->fieldLower($property, $realvalue, true);
                    break;
                case \FreeFW\Model\AbstractStorage::FIND_GREATER_EQUAL:
                    $query->fieldGreaterEqual($property, $realvalue);
                    break;
                case \FreeFW\Model\AbstractStorage::FIND_GREATER_EQUAL_OR_NULL:
                    $query->fieldGreaterEqual($property, $realvalue, true);
                    break;
                case \FreeFW\Model\AbstractStorage::FIND_LOWER_EQUAL:
                    $query->fieldLowerEqual($property, $realvalue);
                    break;
                case \FreeFW\Model\AbstractStorage::FIND_LOWER_EQUAL_OR_NULL:
                    $query->fieldLowerEqual($property, $realvalue, true);
                    break;
                case \FreeFW\Model\AbstractStorage::FIND_EMPTY:
                    $query->fieldEmpty($property, $realvalue);
                    break;
                case \FreeFW\Model\AbstractStorage::FIND_NOT_EMPTY:
                    $query->fieldNotEmpty($property, $realvalue);
                    break;
                case \FreeFW\Model\AbstractStorage::FIND_IN:
                    $query->fieldIn($property, $realvalue);
                    break;
                case \FreeFW\Model\AbstractStorage::FIND_NOT_IN:
                    $query->fieldNotIn($property, $realvalue);
                    break;
                default:
                    $query->fieldEqual($property, $realvalue);
                    break;
            }
        }
        //var_export($query->getSQLQuery());
        //die;
        // Fini
        return $query->getResult();
    }
}
