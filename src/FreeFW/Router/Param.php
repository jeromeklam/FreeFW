<?php
namespace FreeFW\Router;

/**
 *
 * @author jeromeklam
 *
 */
class Param
{

    /**
     * Origine du paramètre
     * @var string
     */
    const FROM_URI   = 'uri';
    const FROM_QUERY = 'query';
    const FROM_BODY  = 'data';

    /**
     * Types
     * @var string
     */
    const TYPE_STRING  = 'string';
    const TYPE_NUMBER  = 'number';
    const TYPE_DATE    = 'date';
    const TYPE_BOOLEAN = 'boolean';
    const TYPE_ARRAY   = 'array';
    const TYPE_INCLUDE = 'include';
    const TYPE_FIELDS  = 'fields';
    const TYPE_SORT    = 'sort';
    const TYPE_FILTER  = 'filter';
    const TYPE_EXTRA   = 'extra';

    /**
     * Tout
     * @var string
     */
    const EXTRA_ALL = '*';

    /**
     * Nom du paramètre
     * @var string
     */
    protected $name = null;

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
     * From
     * @var string
     */
    protected $from = self::FROM_URI;

    /**
     * Obligatoire
     * @var boolean
     */
    protected $required = false;

    /**
     * Valeur par défaut
     * @var mixed
     */
    protected $default = null;

    /**
     * Paramètre étendu (pas un champ de table)
     * @var boolean
     */
    protected $extended = false;

    /**
     * Affectation du nom
     *
     * @param string $p_name
     *
     * @return \FreeFW\Development\Api\Param
     */
    public function setName($p_name)
    {
        $this->name = $p_name;
        return $this;
    }

    /**
     * Récupération du nom
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Affectation de la description
     *
     * @param string $p_description
     *
     * @return \FreeFW\Development\Api\Param
     */
    public function setDescription($p_description)
    {
        $this->description = $p_description;
        return $this;
    }

    /**
     * Retourne la description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Affectation du type
     *
     * @param string $p_type
     *
     * @return \FreeFW\Development\Api\Param
     */
    public function setType($p_type)
    {
        $p_type = strtolower($p_type);
        if (in_array($p_type, self::getTypes())) {
            $this->type = $p_type;
        } else {
            throw new \InvalidArgumentException(sprintf('%s type is unknown !', $p_type));
        }
        return $this;
    }

    /**
     * Récupération du type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Affectation de la partie from
     *
     * @param string $p_from
     *
     * @return \FreeFW\Development\Api\Param
     */
    public function setFrom($p_from)
    {
        $p_from = strtolower($p_from);
        if (in_array($p_from, self::getFroms())) {
            $this->from = $p_from;
        } else {
            throw new \InvalidArgumentException(sprintf('%s from is unknown !', $p_from));
        }
        return $this;
    }

    /**
     * Récupération de la partie from
     *
     * @return string
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * Affectation obligatoire
     *
     * @param boolean $p_required
     *
     * @return \FreeFW\Development\Api\Param
     */
    public function setRequired($p_required)
    {
        $this->required = $p_required;
        return $this;
    }

    /**
     * Obligatoire ?
     *
     * @return boolean
     */
    public function getRequired()
    {
        return $this->required;
    }

    /**
     * Affectation de la valeur par défaut
     *
     * @param mixed $p_default
     *
     * @return \FreeFW\Development\Api\Param
     */
    public function setDefault($p_default)
    {
        $this->default = $p_default;
        return $this;
    }

    /**
     * Retourne la valeur par défaut
     *
     * @return mixed
     */
    public function getDefault()
    {
        return $this->default;
    }

    /**
     * Affectation du mode étendu
     *
     * @param boolean $p_extended
     *
     * @return \FreeFW\Router\Param
     */
    public function setExtended($p_extended)
    {
        $this->extended = $p_extended;
        return $this;
    }

    /**
     * Champ étendu ?
     *
     * @return boolean
     */
    public function getExtended()
    {
        return $this->extended;
    }

    /**
     * Obligatoire ?
     *
     * @return boolean
     */
    public function isRequired()
    {
        return $this->required;
    }

    /**
     * Retourne la liste des froms
     *
     * @return array
     */
    public static function getFroms()
    {
        return array(
            self::FROM_URI,
            self::FROM_BODY,
            self::FROM_QUERY
        );
    }

    /**
     * Retourne la liste des types disponibles
     *
     * @return array
     */
    public static function getTypes()
    {
        return array(
            self::TYPE_STRING,
            self::TYPE_NUMBER,
            self::TYPE_DATE,
            self::TYPE_BOOLEAN,
            self::TYPE_ARRAY,
            self::TYPE_SORT,
            self::TYPE_INCLUDE,
            self::TYPE_FILTER,
            self::TYPE_FIELDS,
            self::TYPE_EXTRA
        );
    }
}
