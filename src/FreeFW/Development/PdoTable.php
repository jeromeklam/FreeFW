<?php
namespace FreeFW\Development;

/**
 * Class de gestion d'une table PDO
 * @author jerome.klam
 *
 * Utilisation :
 *     * Le but est de créer une variable de type PdoTable et de passer les informations suivantes :
 *         * ns       : Le Namespace à utiliser, \ comme séparateur
 *         * class    : Le nom de la classe
 *         * name     : Le nom du fichier en base de données
 *         * columns  : La liste des colonnes
 *         * prefix   : Le préfixe éventuel des champs en base
 */
class PdoTable
{

    /**
     * Nom de la table en db
     * @var string
     */
    protected $table_name = null;

    /**
     * NameSpace PHP
     * @var string
     */
    protected $table_ns = null;

    /**
     * Nom de la classe PHP
     * @var string
     */
    protected $table_class = null;

    /**
     * Préfixe des colonnes de la table
     * @var string
     */
    protected $column_prefix = null;

    /**
     * Liste des colonnes
     * @var array
     */
    protected $table_columns = array();

    /**
     * Constructeur
     *
     * @param string $p_ns
     * @param string $p_class
     * @param string $p_name
     * @param array  $p_columns
     * @param string $p_prefix
     */
    public function __construct($p_ns, $p_class, $p_name, $p_columns = array(), $p_prefix = null)
    {
        $this->table_ns      = $p_ns;
        $this->table_class   = $p_class;
        $this->table_name    = $p_name;
        $this->column_prefix = $p_prefix;
        if (is_array($p_columns) && count($p_columns) > 0) {
            $this->table_columns = $p_columns;
        }
    }

    /**
     * Informations sur la colonne
     *
     * @param array $p_column
     *
     * @return array
     */
    protected static function getColumnInfos($p_column)
    {
        $type     = 'String';
        $default  = 'null';
        $key      = false;
        $fullText = false;
        switch (strtoupper($p_column['native_type'])) {
            case 'INTEGER':
                $type = 'number';
                $typs = \FreeFW\Constants::TYPE_INTEGER;
                break;
            case 'LONG':
                $type = 'number';
                $typs = \FreeFW\Constants::TYPE_INTEGER;
                break;
            case 'LONGLONG':
                $type = 'number';
                $typs = \FreeFW\Constants::TYPE_BIGINT;
                break;
            case 'DATETIME':
            case 'TIMESTAMP':
                $type = 'string';
                $typs = \FreeFW\Constants::TYPE_DATETIME;
                break;
            case 'BLOB':
                $type = 'string';
                $typs = \FreeFW\Constants::TYPE_TEXT;
                break;
            case 'VAR_STRING':
            case 'STRING':
                $type     = 'string';
                $typs     = \FreeFW\Constants::TYPE_STRING;
                $fullText = true;
                break;
            default:
                $typs = \FreeFW\Constants::TYPE_STRING;
                $type = 'string';
                break;
        }
        if (array_key_exists('flags', $p_column)) {
            if (in_array('primary_key', $p_column['flags'])) {
                $key = true;
            }
        }
        return array(
            'type'    => $type,
            'typs'    => $typs,
            'default' => $default,
            'key'     => $key,
            'fullt'   => $fullText
        );
    }

    /**
     * Retourne la chaine de constitution des propriétés
     *
     * @return string
     */
    protected function getFieldsInfos()
    {
        $result = '';
        $prefix = '';
        $getset = '';
        $keys   = array();
        $fulls  = array();
        $maps   = array();
        $desc   = array();
        if ($this->column_prefix !== null && $this->column_prefix !== false && $this->column_prefix != '') {
            $prefix = rtrim($this->column_prefix, '_') . '_';
        }
        foreach ($this->table_columns as $idx => $column) {
            $column_name = $column['name'];
            $field_name  = strtolower(str_replace($prefix, '', $column_name));
            $camelcase   = \FreeFW\Tools\PBXString::toCamelCase($field_name, true);
            $infos       = self::getColumnInfos($column);
            $result .= PHP_EOL .
            '    /**' . PHP_EOL .
            '     * ' . $column_name . PHP_EOL .
            '     * @var ' . $infos['type'] . PHP_EOL .
            '     */' . PHP_EOL .
            '    protected $' . $field_name . ' = ' . $infos['default'] . ';' . PHP_EOL .
            '    const DESC_' . strtoupper($field_name) . ' = array(' . PHP_EOL .
            '        \'name\'   => \'' . $field_name. '\',' . PHP_EOL .
            '        \'column\' => \'' . $column_name . '\',' . PHP_EOL .
            '        \'field\'  => \'' . $field_name. '\',' . PHP_EOL .
            '        \'type\'   => \\FreeFW\\Core\\Constants::TYPE_' . $infos['typs'] . ',' . PHP_EOL .
            '        \'camel\'  => \'' . $camelcase. '\',' . PHP_EOL .
            '        \'snake\'  => \'' . $field_name. '\',' . PHP_EOL .
            '        \'getter\' => \'get' . $camelcase. '\',' . PHP_EOL .
            '        \'setter\' => \'set' . $camelcase. '\',' . PHP_EOL .
            '        \'search\' => ' . ($infos['fullt'] ? 'true' : 'false');
            ;
            if ($infos['key']) {
                $result .= ',' . PHP_EOL . '        \'key\'    => true';
                $result .= ',' . PHP_EOL . '        \'uniq\'   => true';
            }
            $result .= PHP_EOL . '    );';
            $getset .= PHP_EOL .
            '    /**' . PHP_EOL .
            '     * Setter ' . $field_name . PHP_EOL .
            '     *' . PHP_EOL .
            '     * @param ' . strtolower($infos['type']) . ' $p_value' . PHP_EOL .
            '     *' . PHP_EOL .
            '     * @return \\[[:namespace:]]\\[[:class:]]' . PHP_EOL .
            '     */' . PHP_EOL .
            '    public function set' . \FreeFW\Tools\PBXString::ToCamelCase($field_name, true) .
            '($p_value)' . PHP_EOL .
            '    {' . PHP_EOL .
            '        $this->' . $field_name. ' = $p_value;' . PHP_EOL .
            '        return $this;' . PHP_EOL .
            '    }' . PHP_EOL;
            $getset .= PHP_EOL .
            '    /**' . PHP_EOL .
            '     * Getter ' . $field_name . PHP_EOL .
            '     *' . PHP_EOL .
            '     * @return ' . strtolower($infos['type']) . PHP_EOL .
            '     */' . PHP_EOL .
            '    public function get' . \FreeFW\Tools\PBXString::ToCamelCase($field_name, true) .
            '()' . PHP_EOL .
            '    {' . PHP_EOL .
            '        return $this->' . $field_name. ';' . PHP_EOL .
            '    }' . PHP_EOL;
            if ($infos['key']) {
                $keys[] = '\'' . $field_name . '\'';
            }
            if ($infos['fullt']) {
                $fulls[] = '\'' . $field_name . '\'';
            }
            $maps[$column_name] = $field_name;
            $description        = array(
                'name'   => $column_name,
                'field'  => $field_name,
                'type'   => $infos['type'],
                'key'    => $infos['key'],
                'like'   => $infos['fullt']
            );
            $desc[] = $description;
        }
        $len = 0;
        $map = '';
        foreach ($maps as $field => $prop) {
            if (strlen($field) > $len) {
                $len = strlen($field);
            }
        }
        $len++;
        foreach ($maps as $field => $prop) {
            $map .= '            \'' . $field . '\'' . str_pad(' ', $len - strlen($field)) .
            '=> \'' . $prop . '\',' . PHP_EOL;
        }
        $map = rtrim($map, ',' . PHP_EOL);
        return array(
            'fields' => $result,
            'map'    => $map,
            'desc'   => $desc,
            'getset' => $getset,
            'full'   => implode(', ', $fulls),
            'keys'   => implode(', ', $keys)
        );
    }

    /**
     * Retourne le contenu de la classe
     *
     * @return string
     */
    public function get()
    {
        $model = file_get_contents(__DIR__ . '/Template/modelBody.tpl');
        $infos = $this->getFieldsInfos();
        $tags  = array(
            'namespace'   => $this->table_ns,
            'description' => '... ...',
            'class'       => $this->table_class,
            'table'       => $this->table_name,
            'fields'      => $infos['fields'],
            'colmap'      => $infos['map'],
            'colid'       => '            ' . $infos['keys'],
            'colfull'     => '            ' . $infos['full'],
        );
        $arr1 = 'array(';
        $arr2 = 'array(';
        $tota = count($infos['desc']);
        $lng1 = 0;
        $lng2 = 0;
        foreach ($infos['desc'] as $idx => $oneDesc) {
            if (strlen($oneDesc['name']) > $lng1) {
                $lng1 = strlen($oneDesc['name']);
            }
            if (strlen($oneDesc['field']) > $lng2) {
                $lng2 = strlen($oneDesc['field']);
            }
        }
        $lng1++;
        $lng2++;
        foreach ($infos['desc'] as $idx => $oneDesc) {
            $arr1 .= PHP_EOL;
            $arr2 .= PHP_EOL;
            $arr1 .= '            \'' . $oneDesc['name'] . '\'' .
                     str_pad(' ', $lng1 - strlen($oneDesc['name'])) . '=> self::DESC_' .
                     strtoupper($oneDesc['field'])
            ;
            $arr2 .= '            \'' . $oneDesc['field'] . '\'' .
                     str_pad(' ', $lng2 - strlen($oneDesc['field'])) . '=> self::DESC_' .
                     strtoupper($oneDesc['field'])
            ;
            if ($idx < ($tota-1)) {
                $arr1 .= ',';
                $arr2 .= ',';
            }
        }
        $arr1 .= PHP_EOL . '        );';
        $arr2 .= PHP_EOL . '        );';
        $tags['getset']   = \FreeFW\Tools\PBXString::parse($infos['getset'], $tags);
        $tags['desccol']  = $arr1;
        $tags['descprop'] = $arr2;
        return \FreeFW\Tools\PBXString::parse($model, $tags);
    }
}
