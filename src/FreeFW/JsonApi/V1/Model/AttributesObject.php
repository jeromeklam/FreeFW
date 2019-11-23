<?php
namespace FreeFW\JsonApi\V1\Model;

/**
 * Attributes object
 *
 * @author jeromeklam
 */
class AttributesObject implements \Countable, \JsonSerializable
{

    /**
     * Attributes
     * @var array | null
     */
    protected $attributes = null;

    /**
     * Constructor
     *
     * @param array $p_attributes
     */
    public function __construct(array $p_attributes = [])
    {
        $this->attributes = [];
        foreach ($p_attributes as $key => $value) {
            if ($value instanceof \FreeFW\JsonApi\V1\Model\AttributeObject) {
                $this->addAttribute($value); 
            } else {
                $oneAttr = new \FreeFW\JsonApi\V1\Model\AttributeObject($key, $value);
                $this->addAttribute($oneAttr); 
            }
        }
    }

    /**
     * Flush all attributes
     * 
     * @return \FreeFW\JsonApi\V1\Model\AttributesObject
     */
    public function flushAttributes()
    {
        $this->attributes = [];
        return $this;
    }

    /**
     * Add one attribute
     * 
     * @param \FreeFW\JsonApi\V1\Model\AttributeObject $p_attr
     * 
     * @return \FreeFW\JsonApi\V1\Model\AttributesObject
     */
    public function addAttribute(\FreeFW\JsonApi\V1\Model\AttributeObject $p_attr)
    {
        $this->attributes[] = $p_attr;
        return $this;
    }

    /**
     * As array
     * 
     * @return array
     */
    public function __toArray()
    {
        $arr = [];
        foreach ($this->attributes as $idx => $attr) {
            $arr[$attr->getName()] = $attr->getValue();
        }
        return $arr;
    }

    /**
     * @see \Countable
     */
    public function count()
    {
        return count($this->attributes);
    }

    /**
     * 
     * {@inheritDoc}
     * @see \JsonSerializable::jsonSerialize()
     */
    public function jsonSerialize()
    {
        $obj = new \stdClass();
        foreach ($this->attributes as $idx => $attribute) {
            if (!$attribute->getJsonIgnore()) {
                $attName = $attribute->getJsonName();
                $obj->$attName = $attribute->getValue();
            }
        }
        return $obj;
    }
}
