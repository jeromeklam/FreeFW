<?php
namespace FreeFW\JsonApi\V1\Model;

/**
 * Relationships object
 *
 * @author jeromeklam
 */
class RelationshipObject
{

    /**
     * Types
     * @var string
     */
    const ONE_TO_ONE = '1TO1';
    const ONE_TO_MANY = '1TOM';

    /**
     * Nom
     * @var string
     */
    protected $name = null;

    /**
     * Modèle
     * @var string
     */
    protected $model = null;

    /**
     * Type
     * @var string
     */
    protected $type = null;

    /**
     * Values
     * @var array
     */
    protected $values = [];

    /**
     * Property name
     * @var string
     */
    protected $property_name = null;

    /**
     * Constructor
     * 
     * @param string $p_name
     * @param string $p_type
     */
    public function __construct($p_name, $p_type = self::ONE_TO_ONE)
    {
        $this
            ->setName($p_name)
            ->setType($p_type)
        ;
    }

    /**
     * Set name
     * 
     * @param string $p_name
     * 
     * @return \FreeFW\JsonApi\V1\Model\RelationshipObject
     */
    public function setName($p_name)
    {
        $this->name = $p_name;
        return $this;
    }

    /**
     * Get name
     * 
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Type
     * 
     * @param string $p_type
     * 
     * @return \FreeFW\JsonApi\V1\Model\RelationshipObject
     */
    public function setType($p_type)
    {
        $this->type = $p_type;
        return $this;
    }

    /**
     * Get type
     * 
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set property name
     * 
     * @param string $p_name
     * 
     * @return \FreeFW\JsonApi\V1\Model\RelationshipObject
     */
    public function setPropertyName($p_name)
    {
        $this->property_name = $p_name;
        return $this;
    }

    /**
     * Get property name
     * 
     * @return string
     */
    public function getPropertyName()
    {
        return $this->property_name;
    }

    /**
     * Add one value
     * 
     * @param mixed $p_value
     * 
     * @return \FreeFW\JsonApi\V1\Model\RelationshipObject
     */
    public function addValue($p_value)
    {
        $this->values[] = $p_value;
        return $this;
    }
    
    /**
     * Get values
     * 
     * @return array
     */
    public function getValues()
    {
        return $this->values;
    }

    /**
     * Set model
     * 
     * @param string $p_model
     * 
     * @return \FreeFW\JsonApi\V1\Model\RelationshipObject
     */
    public function setModel($p_model)
    {
        $this->model = $p_model;
        return $this;
    }

    /**
     * Get model
     * @return string
     */
    public function getModel()
    {
        return $this->model;
    }
}
