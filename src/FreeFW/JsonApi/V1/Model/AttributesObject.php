<?php
namespace FreeFW\JsonApi\V1\Model;

/**
 * Attributes object
 *
 * @author jeromeklam
 */
class AttributesObject
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
        $this->attributes = $p_attributes;
    }

    /**
     * To array
     *
     * @return array
     */
    public function __toArray()
    {
        if (!is_array($this->attributes)) {
            $this->attributes = [];
        }
        return $this->attributes;
    }
}
