<?php
namespace FreeFW\JsonApi\V1\Model;

/**
 * Resource object
 *
 * @author jeromeklam
 */
class ResourceObject implements \JsonSerializable
{

    /**
     * Id
     * @var string
     */
    protected $id = null;

    /**
     * Type
     * @var string
     */
    protected $type = null;

    /**
     * Attributes
     * @var \FreeFW\JsonApi\V1\Model\AttributesObject
     */
    protected $attributes = null;

    /**
     * RelationShips
     * @var \FreeFW\JsonApi\V1\Model\RelationshipsObject
     */
    protected $relationships = null;

    /**
     * Links
     * @var \FreeFW\JsonApi\V1\Model\LinksObject
     */
    protected $links = null;

    /**
     * Meta
     * @var \FreeFW\JsonApi\V1\Model\MetaObject
     */
    protected $meta = null;
    
    /**
     * Single ressource ?
     * @var boolean
     */
    protected $single = true;

    /**
     * Constructor
     *
     * @param string  $p_type
     * @param string  $p_id
     * @param boolean $p_single
     */
    public function __construct(string $p_type, $p_id = null, $p_single = true)
    {
        $this->id     = $p_id;
        $this->type   = $p_type;
        $this->single = $p_single;
    }

    /**
     * Get id
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
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
     * Set attributes
     *
     * @param \FreeFW\JsonApi\V1\Model\AttributesObject $p_attributes
     *
     * @return \FreeFW\JsonApi\V1\Model\ResourceObject
     */
    public function setAttributes(\FreeFW\JsonApi\V1\Model\AttributesObject $p_attributes)
    {
        $this->attributes = $p_attributes;
        return $this;
    }

    /**
     * Get attributes
     *
     * @return mixed
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * Get relationships
     *
     * @return \FreeFW\JsonApi\V1\Model\AttributesObject
     */
    public function getRelationships()
    {
        return $this->relationships;
    }
    
    /**
     * Set relationShips
     * 
     * @param \FreeFW\JsonApi\V1\Model\RelationshipsObject $p_relations
     * 
     * @return \FreeFW\JsonApi\V1\Model\ResourceObject
     */
    public function setRelationShips(\FreeFW\JsonApi\V1\Model\RelationshipsObject $p_relations)
    {
        $this->relationships = $p_relations;
        return $this;
    }

    /**
     * Single ressource ?
     * 
     * @return boolean
     */
    public function isSingle()
    {
        return $this->single;
    }
    
    /**
     *
     * {@inheritDoc}
     * @see \JsonSerializable::jsonSerialize()
     */
    public function jsonSerialize()
    {
        $obj       = new \stdClass();
        $obj->type = $this->getType();
        $obj->id   = $this->getId();
        if ($this->attributes && count($this->attributes) > 0) {
            $obj->attributes = $this->attributes;
        }
        if ($this->relationships && count($this->relationships) > 0) {
            $obj->relationships = $this->relationships;
        }
        return $obj;
    }
}
