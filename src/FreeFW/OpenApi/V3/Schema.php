<?php
namespace FreeFW\OpenApi\V3;

use \FreeFW\Constants as FFCST;

/**
 * OpenApi v3 Schema object
 *
 * @author jeromeklam
 */
class Schema extends \FreeFW\OpenApi\V3\Base
{

    /**
     * Types constants
     * @var string
     */
    const TYPE_OBJECT  = 'object';
    const TYPE_STRING  = 'string';
    const TYPE_NUMBER  = 'number';
    const TYPE_INTEGER = 'integer';
    const TYPE_BOOLEAN = 'boolean';

    /**
     * Formats
     * @var string
     */
    const FORMAT_INT32    = 'int32';
    const FORMAT_INT64    = 'int64';
    const FORMAT_FLOAT    = 'float';
    const FORMAT_DOUBLE   = 'double';
    const FORMAT_STRING   = 'string';
    const FORMAT_BYTE     = 'byte';
    const FORMAT_BINARY   = 'binary';
    const FORMAT_BOOLEAN  = 'boolean';
    const FORMAT_DATE     = 'date';
    const FORMAT_DATETIME = 'date-time';
    const FORMAT_PASWORD  = 'password';

    /**
     * Description
     * @var string
     */
    protected $description = null;

    /**
     * Type
     * @var string
     */
    protected $type = null;

    /**
     * Format
     * @var string
     */
    protected $format = null;

    /**
     * Properties
     * @var [\FreeFW\OpenApi\V3\Schema]
     */
    protected $properties = null;

    /**
     * Nullable
     * @var boolean, default false
     */
    protected $nullable = null;

    /**
     * Enum
     * @var array
     */
    protected $enum = null;

    /**
     * Default value
     * @var mixed
     */
    protected $default = null;

    /**
     * Minimum length
     * @var integer
     */
    protected $min_length = null;

    /**
     * Maximum length
     * @var integer
     */
    protected $max_length = null;

    /**
     * Read only
     * @var bool
     */
    protected $read_only = null;

    /**
     * Ref
     * @var string
     */
    protected $ref = null;

    /**
     * Example
     * @var mixed
     */
    protected $example = null;

    /**
     * Set default value
     *
     * @param mixed $p_default
     *
     * @return \FreeFW\OpenApi\V3\Schema
     */
    public function setDefault($p_default)
    {
        switch ($p_default) {
            case FFCST::DEFAULT_TRUE:
                $this->default = true;
                break;
            case FFCST::DEFAULT_FALSE:
                $this->default = false;
                break;
            case FFCST::DEFAULT_NOW:
                $this->default = 'maintenant';
                break;
            case FFCST::DEFAULT_CURRENT_USER:
                $this->default = 'utilisateur';
                break;
            case FFCST::DEFAULT_CURRENT_GROUP:
                $this->default = 'groupe';
                break;
            default:
                $this->default = $p_default;
                break;
        }
        return $this;
    }

    /**
     * Set format and type
     *
     * @param string $p_format
     *
     * @return \FreeFW\OpenApi\V3\Schema
     */
    public function setFormat($p_format)
    {
        switch ($p_format) {
            case self::FORMAT_INT32:
                $this->format = $p_format;
                $this->type   = self::TYPE_INTEGER;
                break;
            case self::FORMAT_INT64:
                $this->format = $p_format;
                $this->type   = self::TYPE_INTEGER;
                break;
            case self::FORMAT_FLOAT:
                $this->format = $p_format;
                $this->type   = self::TYPE_NUMBER;
                break;
            case self::FORMAT_DOUBLE:
                $this->format = $p_format;
                $this->type   = self::TYPE_NUMBER;
                break;
            case self::FORMAT_STRING:
                $this->format = $p_format;
                $this->type   = self::TYPE_STRING;
                break;
            case self::FORMAT_BYTE:
                $this->format = $p_format;
                $this->type   = self::TYPE_STRING;
                break;
            case self::FORMAT_BINARY:
                $this->format = $p_format;
                $this->type   = self::TYPE_STRING;
                break;
            case self::FORMAT_BOOLEAN:
                $this->format = $p_format;
                $this->type   = self::TYPE_BOOLEAN;
                break;
            case self::FORMAT_DATE:
                $this->format = $p_format;
                $this->type   = self::TYPE_STRING;
                break;
            case self::FORMAT_DATETIME:
                $this->format = $p_format;
                $this->type   = self::TYPE_STRING;
                break;
            case self::FORMAT_PASWORD:
                $this->format = $p_format;
                $this->type   = self::TYPE_STRING;
                break;
            default:
                $this->format = self::FORMAT_STRING;
                $this->type   = self::TYPE_STRING;
                break;
        }
        return $this;
    }
}
