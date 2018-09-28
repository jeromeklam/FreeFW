<?php
namespace FreeFW\Development\Api;

/**
 *
 * @author jerome.klam
 *
 */
class Param
{

    /**
     * Types
     * @var string
     */
    const TYPE_STRING = 'string';

    /**
     * Nom du paramètre
     * @var string
     */
    protected $name = null;

    /**
     * Description du paramètre
     * @var string
     */
    protected $description = null;

    /**
     * Type du paramètre
     * @var string
     */
    protected $type = null;

    /**
     * Valeur par défaut
     * @var mixed
     */
    protected $default = null;

    /**
     * Valeur du paramètre
     * @var mixed
     */
    protected $value = null;

    /**
     * Complément en fonction du type
     * @var mixed
     */
    protected $complement = null;

    /**
     * Retourne un paramètre d'après des valeurs de la config
     *
     * @param array $p_config
     *
     * @return \FreeFW\Development\Api\Param
     */
    public static function getFromConfig($p_config)
    {
        $param = new static();
        if (is_array($p_config)) {
            if (array_key_exists('name', $p_config)) {
                $param->setName($p_config['name']);
            }
            if (array_key_exists('type', $p_config)) {
                $param->setType($p_config['type']);
            }
            if (array_key_exists('value', $p_config)) {
                $param->setValue($p_config['value']);
            }
            if (array_key_exists('default', $p_config)) {
                $param->setDefault($p_config['default']);
            }
            if (array_key_exists('complement', $p_config)) {
                $param->setComplement($p_config['complement']);
            }
            if (array_key_exists('description', $p_config)) {
                $param->setDescription($p_config['description']);
            }
        }
        return $param;
    }
    /**
     * Affectation du nom du paramètre
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
     * Retourne le nom du paramètre
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
     * Récupération de la description
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
        if (in_array($p_type, self::getTypes())) {
            $this->type = $p_type;
        } else {
            throw new \InvalidArgumentException(sprintf('%s type unknown !', $p_type));
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
     * Affectation de la valeur
     *
     * @param mixed $p_value
     *
     * @return \FreeFW\Development\Api\Param
     */
    public function setValue($p_value)
    {
        $this->value = $p_value;
        return $this;
    }

    /**
     * Retourne la valeur
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
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
     * Récupération de la valeur pae défaut
     *
     * @return mixed
     */
    public function getDefault()
    {
        return $this->default;
    }

    /**
     * Affectation du complément
     *
     * @param mixed $p_complement
     *
     * @return \FreeFW\Development\Api\Param
     */
    public function setComplement($p_complement)
    {
        $this->complement = $p_complement;
        return $this;
    }

    /**
     * Retourne le complément
     *
     * @return mixed
     */
    public function getComplement()
    {
        return $this->complement;
    }

    /**
     * Retourne la liste des types disponibles
     *
     * @return array
     */
    public static function getTypes()
    {
        return [
            self::TYPE_STRING
        ];
    }

    /**
     * Retourne un paramètre au format swagger
     *
     * @param number $p_version
     *
     * @return \stdClass
     */
    public function getForSwagger($p_version = 3)
    {
        $param = new \stdClass();
        if ($this->getDefault() !== null) {
            $param->default = $this->getDefault();
        } else {
            $param->default = $this->getValue();
        }
        if ($this->getDescription() !== null) {
            $param->description = $this->getDescription();
        }
        return $param;
    }
}
