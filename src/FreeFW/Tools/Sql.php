<?php
namespace FreeFW\Tools;

/**
 * Sql
 *
 * @author jeromeklam
 */
class Sql
{

    /**
     * make a query with an array of field => value
     *
     * @param string $p_table
     * @param array  $p_fields
     *
     * @return string
     */
    public static function makeInsertQuery(string $p_table, array $p_fields)
    {
        $fields = '';
        $values = '';
        foreach ($p_fields as $field => $value) {
            $local = str_replace(':', '', $field);
            if ($fields == '') {
                $fields = $local;
                $values = ':' . $local;
            } else {
                $fields = $fields . ', ' . $local;
                $values = $values . ', :' . $local;
            }
        }
        $sql    = 'INSERT INTO ' . $p_table . ' (' . $fields . ') VALUES (' . $values . ')';
        return $sql;
    }

    /**
     * make a query with an array of field => value
     *
     * @param string $p_table
     * @param array  $p_fields
     * @param array  $p_pks
     *
     * @return string
     */
    public static function makeUpdateQuery(string $p_table, array $p_fields, array $p_pks)
    {
        $fields = '';
        $where  = '';
        foreach ($p_fields as $field => $value) {
            if (!array_key_exists($field, $p_pks)) {
                $local = str_replace(':', '', $field);
                if ($fields == '') {
                    $fields = $local . ' = :' . $local;
                } else {
                    $fields = $fields . ', ' . $local . ' = :' . $local;
                }
            }
        }
        foreach ($p_pks as $field => $value) {
            $local = str_replace(':', '', $field);
            if ($fields == '') {
                $where = $local . ' = :' . $local;
            } else {
                $where = $fields . ' AND ' . $local . ' = :' . $local;
            }
        }
        $sql    = 'UPDATE ' . $p_table . ' SET ' . $fields . ' WHERE ' . $where;
        return $sql;
    }

    /**
     * make a query with an array of field => value
     *
     * @param string $p_table
     * @param array  $p_fields
     *
     * @return string
     */
    public static function makeDeleteQuery(string $p_table, array $p_fields)
    {
        $where = '1=1';
        foreach ($p_fields as $field => $value) {
            $local = str_replace(':', '', $field);
            $where = $where . ' AND ' . $local . ' = :' . $local;
        }
        $sql = 'DELETE FROM ' . $p_table . ' WHERE ' . $where;
        return $sql;
    }

    /**
     * Simple select query
     * y
     * @param string $p_table
     * @param array  $p_fields
     *
     * @return string
     */
    public static function makeSimpleSelect(string $p_table, array $p_fields = [])
    {
        $where = '1=1';
        foreach ($p_fields as $field => $value) {
            $local = str_replace(':', '', $field);
            $where = $where . ' AND ' . $local . ' = :' . $local;
        }
        $sql = 'SELECT * FROM ' . $p_table . ' WHERE ' . $where;
        return $sql;
    }
}
