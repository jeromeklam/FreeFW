<?php
namespace FreeFW\Model;

use \FreeFW\Constants as FFCST;

/**
 * Field
 *
 * @author jeromeklam
 */
class Field extends \FreeFW\Core\Model
{

    /**
     * Field name
     * @var string
     */
    protected $fld_name;

    /**
     * Field type
     * @var string
     */
    protected $fld_type;

    /**
     * Field length
     * @var int
     */
    protected $fld_length;

    /**
     * Field length complement
     * @var int
     */
    protected $fld_complement;

    /**
     * Primary key ?
     * @var bool
     */
    protected $fld_primary;

    /**
     * Required ?
     * @var bool
     */
    protected $fld_required;

    /**
     * Set field name
     *
     * @param string $p_name
     *
     * @return \FreeFW\Model\Field
     */
    public function setFldName(string $p_name)
    {
        $this->fld_name = $p_name;
        return $this;
    }

    /**
     * Get field name
     *
     * @return string
     */
    public function getFldName()
    {
        return $this->fld_name;
    }

    /**
     * Set field type
     *
     * @param string $p_type
     *
     * @return \FreeFW\Model\Field
     */
    public function setFldType(string $p_type)
    {
        $this->fld_type = $p_type;
        return $this;
    }

    /**
     * Get field type
     *
     * @return string
     */
    public function getFldType()
    {
        return $this->fld_type;
    }

    /**
     * Set field length
     *
     * @param int $p_length
     *
     * @return \FreeFW\Model\Field
     */
    public function setFldLength(int $p_length)
    {
        $this->fld_length = $p_length;
        return $this;
    }

    /**
     * Get field length
     *
     * @return int
     */
    public function getFldLength()
    {
        return $this->fld_length;
    }

    /**
     * Set field complement
     *
     * @param int $p_complement
     *
     * @return \FreeFW\Model\Field
     */
    public function setFldComplement(int $p_complement)
    {
        $this->fld_complement = $p_complement;
        return $this;
    }

    /**
     * Get field complement
     *
     * @return int
     */
    public function getFldComplement()
    {
        return $this->fld_complement;
    }

    /**
     * Set field as primary
     *
     * @param bool $p_primary
     *
     * @return \FreeFW\Model\Field
     */
    public function setFldPrimary(bool $p_primary)
    {
        $this->fld_primary = $p_primary;
        return $this;
    }

    /**
     * Get field primary
     *
     * @return bool
     */
    public function getFldPrimary()
    {
        return $this->fld_primary;
    }

    /**
     * Set field required
     *
     * @param bool $p_required
     *
     * @return \FreeFW\Model\Field
     */
    public function setFldRequired(bool $p_required)
    {
        $this->fld_required = $p_required;
        return $this;
    }

    /**
     * Get field required
     *
     * @return boolean
     */
    public function getFldRequired()
    {
        return $this->fld_required;
    }

    /**
     *
     * {@inheritDoc}
     * @see \FreeFW\Core\Model::getProperties()
     */
    public static function getProperties()
    {
        return [
            'fld_name' => [
                FFCST::PROPERTY_PRIVATE => 'fld_name',
                FFCST::PROPERTY_TYPE    => FFCST::TYPE_STRING,
                FFCST::PROPERTY_OPTIONS => [FFCST::OPTION_REQUIRED]
            ],
            'fld_type' => [
                FFCST::PROPERTY_PRIVATE => 'fld_type',
                FFCST::PROPERTY_TYPE    => FFCST::TYPE_STRING,
                FFCST::PROPERTY_OPTIONS => [FFCST::OPTION_REQUIRED]
            ],
            'fld_length' => [
                FFCST::PROPERTY_PRIVATE => 'fld_length',
                FFCST::PROPERTY_TYPE    => FFCST::TYPE_INTEGER,
                FFCST::PROPERTY_OPTIONS => [FFCST::OPTION_REQUIRED]
            ],
            'fld_complement' => [
                FFCST::PROPERTY_PRIVATE => 'fld_complement',
                FFCST::PROPERTY_TYPE    => FFCST::TYPE_INTEGER,
                FFCST::PROPERTY_OPTIONS => []
            ],
            'fld_primary' => [
                FFCST::PROPERTY_PRIVATE => 'fld_primary',
                FFCST::PROPERTY_TYPE    => FFCST::TYPE_BOOLEAN,
                FFCST::PROPERTY_OPTIONS => [],
                FFCST::PROPERTY_DEFAULT => false
            ],
            'fld_required' => [
                FFCST::PROPERTY_PRIVATE => 'fld_required',
                FFCST::PROPERTY_TYPE    => FFCST::TYPE_BOOLEAN,
                FFCST::PROPERTY_OPTIONS => [],
                FFCST::PROPERTY_DEFAULT => false
            ]
        ];
    }

    /**
     * Convert local type to php
     *
     * @return string
     */
    public function getFldTypeForPhp()
    {
        $type = 'mixed';
        switch ($this->getFldType()) {
            case FFCST::TYPE_INTEGER:
            case FFCST::TYPE_BIGINT:
                $type = 'int';
                break;
            case FFCST::TYPE_DATETIME:
            case FFCST::TYPE_MD5:
            case FFCST::TYPE_STRING:
                $type = 'string';
                break;
            case FFCST::TYPE_BOOLEAN:
                $type = 'bool';
                break;
        }
        return $type;
    }

    /**
     * Convert local type to class
     *
     * @return string
     */
    public function getFldTypeForClass()
    {
        $type = 'TYPE_STRING';
        switch ($this->getFldType()) {
            case FFCST::TYPE_INTEGER:
                $type = 'TYPE_INTEGER';
                break;
            case FFCST::TYPE_BIGINT:
                $type = 'TYPE_BIGINT';
                break;
            case FFCST::TYPE_DATETIME:
                $type = 'TYPE_DATETIME';
                break;
            case FFCST::TYPE_MD5:
                $type = 'TYPE_MD5';
                break;
            case FFCST::TYPE_STRING:
                $type = 'TYPE_STRING';
                break;
            case FFCST::TYPE_BOOLEAN:
                $type = 'TYPE_BOOLEAN';
                break;
            case FFCST::TYPE_BLOB:
                $type = 'TYPE_BLOB';
                break;
        }
        return $type;
    }

    /**
     * Get field options
     *
     * @return string
     */
    public function getFldOptionsForClass()
    {
        $options = [];
        if ($this->getFldRequired()) {
            $options[] = 'FFCST::OPTION_REQUIRED';
        }
        if ($this->getFldPrimary()) {
            $options[] = 'FFCST::OPTION_PK';
        }
        return implode(', ', $options);
    }

    /**
     * Get new from pdo metadatas
     *
     * @param array $p_pdo_description
     *
     * @return \FreeFW\Model\Field
     */
    public static function getFromPDO(array $p_pdo_description)
    {
        $me = self::getNew();
        if (is_array($p_pdo_description)) {
            if (array_key_exists('name', $p_pdo_description)) {
                $me->setFldName($p_pdo_description['name']);
            }
            if (array_key_exists('len', $p_pdo_description)) {
                $me->setFldLength($p_pdo_description['len']);
            }
            if (array_key_exists('precision', $p_pdo_description)) {
                $me->setFldComplement($p_pdo_description['precision']);
            }
            $me->setFldType(FFCST::TYPE_STRING);
            if (array_key_exists('native_type', $p_pdo_description)) {
                switch (strtoupper($p_pdo_description['native_type'])) {
                    case 'LONGLONG':
                        $me->setFldType(FFCST::TYPE_BIGINT);
                        break;
                    case 'TINY':
                        $me->setFldType(FFCST::TYPE_INTEGER);
                        break;
                    case 'BLOB':
                        $me->setFldType(FFCST::TYPE_BLOB);
                        break;
                    case 'TIMESTAMP':
                        $me->setFldType(FFCST::TYPE_DATETIME);
                        break;
                }
            }
            if (array_key_exists('flags', $p_pdo_description)) {
                if (is_array($p_pdo_description['flags'])) {
                    foreach ($p_pdo_description['flags'] as $idx => $flag) {
                        if ($flag == 'primary_key') {
                            $me->setFldPrimary(true);
                        }
                        if ($flag == 'not_null') {
                            $me->setFldRequired(true);
                        }
                    }
                }
            }
        }
        return $me;
    }
}
