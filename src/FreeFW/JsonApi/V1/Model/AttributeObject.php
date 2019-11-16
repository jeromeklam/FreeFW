<?php
namespace FreeFW\JsonApi\V1\Model;

/**
 * Attribute object
 *
 * @author jeromeklam
 */
class AttributeObject
{

    /**
     * Types
     * @var string
     */
    const TYPE_UNKNOWN = 'UNKNOWN';

    /**
     * Name
     * @var string
     */
    protected $name  = null;

    /**
     * Value
     * @var mixed
     */
    protected $value = null;

    /**
     * Label
     * @var string
     */
    protected $label = null;

    /**
     * Type
     * @var string
     */
    protected $type  = null;

    /**
     * Json name
     * @var string
     */
    protected $json_name = null;

    /**
     * Ignore json export
     * @var boolean
     */
    protected $json_ignore = false;
    
    /**
     * Constructor
     * 
     * @param string $p_name
     * @param mixed  $p_value
     * @param string $p_type
     * @param string $p_label
     * @param string $p_json_name
     */
    public function __construct(
        $p_name = null,
        $p_value = null,
        $p_type = self::TYPE_UNKNOWN,
        $p_label = null,
        $p_json_name = null
    ) {
        $this
            ->setName($p_name)
            ->setValue($p_value)
            ->setType($p_type)
            ->setLabel($p_label)
            ->setJsonName($p_json_name)
        ;
    }

    /**
     * Set name
     * 
     * @param string $p_name
     * 
     * @return \FreeFW\JsonApi\V1\Model\AttributeObject
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
     * Set value
     * 
     * @param mixed $p_value
     * 
     * @return \FreeFW\JsonApi\V1\Model\AttributeObject
     */
    public function setValue($p_value)
    {
        $this->value = $p_value;
        return $this;
    }

    /**
     * Get value
     * 
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set label
     * 
     * @param string $p_label
     * 
     * @return \FreeFW\JsonApi\V1\Model\AttributeObject
     */
    public function setLabel($p_label)
    {
        $this->label = $p_label;
        return $this;
    }

    /**
     * Get label
     * 
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Set type
     * 
     * @param string $p_type
     * 
     * @return \FreeFW\JsonApi\V1\Model\AttributeObject
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
     * Set json name
     * 
     * @param string $p_json_name
     * 
     * @return \FreeFW\JsonApi\V1\Model\AttributeObject
     */
    public function setJsonName($p_json_name)
    {
        $this->json_name = $p_json_name;
        return $this;
    }

    /**
     * Get json name
     * 
     * @return string
     */
    public function getJsonName()
    {
        if (trim($this->json_name) == '') {
            $this->json_name = $this->name;
        }
        return $this->json_name;
    }

    /**
     * Set json ignore
     * 
     * @param boolean $p_json_ignore
     * 
     * @return \FreeFW\JsonApi\V1\Model\AttributeObject
     */
    public function setJsonIgnore($p_json_ignore)
    {
        $this->json_ignore = $p_json_ignore;
        return $this;
    }

    /**
     * Get Json ignore
     * 
     * @return boolean
     */
    public function getJsonIgnore()
    {
        return $this->json_ignore;
    }
}
