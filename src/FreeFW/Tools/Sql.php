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
     * make a query wirh an array of field => value
     *
     * @param array $p_fields
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
