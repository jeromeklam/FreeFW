<?php
namespace FreeFW\Development;

use \FreeFW\Tools\PBXString;

/**
 *
 * @author klam
 */
class Model
{

    /**
     * Namespace
     *
     * @var string
     */
    protected $ns = null;

    /**
     * Class name
     *
     * @var string
     */
    protected $name = null;

    /**
     * Table name
     *
     * @var string
     */
    protected $table = null;

    /**
     * Fields
     *
     * @var array
     */
    protected $fields = array();
    protected $fieldsArr = array();
    /**
     * Constructor
     *
     * @param string $p_ns
     * @param string $p_name
     * @param string $p_table_name
     * @param array  $p_fields
     */
    public function __construct($p_ns, $p_name, $p_table_name, $p_fields = array())
    {
        $this->ns    = $p_ns;
        $this->name  = $p_name;
        $this->table = strtolower($p_table_name);
        foreach ($p_fields as $key => $properties) {
            if (!isset($properties['type'])) {
                $properties['type'] = 'string';
            }
            if (!isset($properties['text'])) {
                $properties['text'] = $key;
            }
            if (!isset($properties['column'])) {
                $properties['column'] = null;
            }
            if (!isset($properties['size'])) {
                $properties['size'] = null;
            }
            $this->addField(
                $key,
                $properties['type'],
                $properties['text'],
                $properties['column'],
                $properties['size']
            );
        }
    }

    /**
     * Retourne un modèle à partir d'un descripteur PDO
     *
     * @param string $p_ns
     * @param string $p_name
     * @param string $p_table_name
     * @param string $p_dsn
     * @param string $p_user
     * @param string $p_paswd
     *
     * @return \FreeFW\Development\Model
     */
    public static function getFromPDO($p_ns, $p_name, $p_table_name, $p_dsn, $p_user, $p_paswd)
    {
        $new = new self($p_ns, $p_name, $p_table_name);
        
        $pdo = new \PDO($p_dsn, $p_user, $p_paswd);
        if ($pdo) {
            $rs = $pdo->query('SELECT * FROM ' . $p_table_name . ' LIMIT 0');
            for ($i = 0; $i < $rs->columnCount(); $i++) {
                $col = $rs->getColumnMeta($i);
                $new->addField(
                    strtolower($col['name']),
                    strtolower($col['native_type']),
                    strtolower($col['name']),
                    strtolower($col['name']),
                    $col['len'],
                    $col['precision']
                );
            }
        }
        
        return $new;
    }

    /**
     * R=Traduction de type
     *
     * @param string $p_type
     *
     * @return string
     */
    public static function tradType($p_type)
    {
        switch (strtolower($p_type)) {
            case 'string':
                return 'string';
            case 'var_string':
                return 'string';
            case 'newdecimal':
                return 'decimal';
            default:
                return $p_type;
        }
    }

    /**
     * Add field
     *
     * @param string $p_key
     * @param string $p_type
     * @param string $p_text
     * @param string $p_column
     * @param string $p_size
     * @param string $p_precision
     *
     * @return Model
     */
    public function addField($p_key, $p_type, $p_text, $p_column, $p_size = null, $p_precision = null)
    {
        $this->fields[$p_key] = array(
            'key'    => $p_key,
            'type'   => self::tradType($p_type),
            'text'   => $p_text,
            'column' => $p_column,
            'size'   => $p_size,
            'prec'   => $p_precision
        );
        $this->fieldsArr[] = $this->fields[$p_key];
        
        return $this;
    }

    /**
     * Get Setter
     *
     * @param unknown $p_key
     * @param unknown $p_type
     * @param unknown $p_text
     *
     * @return string
     */
    protected function getSet($p_key, $p_type, $p_text)
    {
        $str = '
    /**
     * Setter for ' . $p_key . '
     *
     * @param ' . $p_type . ' $p_' . $p_key . '
     *
     * @return ' . $this->name . '
     */
    public function set' . String::toCamelCase($p_key, true) . ' ($p_' . $p_key . ')
    {';
        if ($p_type == 'timestamp') {
            $str .= '
        if ($p_' . $p_key . ' !== null && $p_' . $p_key . ' != \'\' && strpos($p_' . $p_key . ', \'/\') !== false ) {
            $this->' . $p_key . ' = MyDate::ddmmyyyyToMysql($p_' . $p_key . ');
        } else {
            $this->' . $p_key . ' = $p_' . $p_key . ';
        }';
        } else {
            $str .= '
        $this->' . $p_key . ' = $p_' . $p_key . ';';
        }
        $str .= '

        return $this;
    }
';

        return $str;
    }

    /**
     * Get Getter
     *
     * @param unknown $p_key
     * @param unknown $p_type
     * @param unknown $p_text
     *
     * @return string
     */
    protected function getGet($p_key, $p_type, $p_text)
    {
        $str = '
    /**
     * Getter for ' . $p_key . '
     *
     * @return ' . $p_type . '
     */
    public function get' . String::toCamelCase($p_key, true) . ' ()
    {
        return $this->' . $p_key . ';
    }
';

        return $str;
    }

    /**
     * Get init, contruct, ...
     *
     * @return string
     */
    public function getInit()
    {
        $str = '
    /**
     *
     */
    public function __construct ()
    {
        parent::initialize();
    }

    /**
     *
     */
    public function columnMap ()
    {
        return array(' . PHP_EOL;
        $i = 0;
        $m = 0;
        while ($i < count($this->fieldsArr)) {
            $key        = $this->fieldsArr[$i]['key'];
            $properties = $this->fieldsArr[$i];
            if (strlen($properties['column']) > $m) {
                $m = strlen($properties['column']);
            }
            $i++;
        }
        $i = 0;
        $m = $m + 1;
        while ($i < count($this->fieldsArr)) {
            $key        = $this->fieldsArr[$i]['key'];
            $properties = $this->fieldsArr[$i];
            $str .= '            \'' . str_pad($properties['column'] . '\'', $m, ' ') . ' => \'' . $key . '\'';
            $i++;
            if ($i < count($this->fieldsArr)) {
                $str .= ',' . PHP_EOL;
            }
        }
        $str .= '
        );
    }';

        return $str;
    }

    /**
     * Get class content
     *
     * @return string
     */
    public function get()
    {
        $head = '<?php
namespace ' . $this->ns . ';

class ' . $this->name . ' extends \FreeFW\Modl\AbstractPDOStorage
{

    /**
     * Source
     * @var string
     */
    protected static $source = \'' . $this->table . '\';

    /**
     * Retourne la source
     *
     * @return string
     */
    public static function getSource ()
    {
        return self::$source;
    }
';
        $str  = '';
        foreach ($this->fields as $key => $properties) {
            $head .= '
    /**
     * ' . $properties['text'] . '
     * @var ' . $properties['type'] . ($properties['size'] != '' ? '(' . $properties['size'] . ')' : '' ) . '
     */
    protected $' . $key . ' = null;
';
            $str .= $this->getSet($key, $properties['type'], $properties['text']);
            $str .= $this->getGet($key, $properties['type'], $properties['text']);
        }
        return $head . $this->getInit() . PHP_EOL . $str . PHP_EOL . '}';
    }

    /**
     * Render class
     */
    public function render()
    {
        echo $this->get();
    }
}
