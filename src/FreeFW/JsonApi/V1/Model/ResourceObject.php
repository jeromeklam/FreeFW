<?php
namespace FreeFW\JsonApi\V1\Model;

/**
 * Resource object
 *
 * @author jeromeklam
 */
class ResourceObject
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
     * Constructor
     *
     * @param string $p_type
     * @param string $p_id
     */
    public function __construct(string $p_type, $p_id = null)
    {
        $this->id   = $p_id;
        $this->type = $p_type;
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
     * @return \FreeFW\JsonApi\V1\Model\AttributesObject
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * To array
     *
     * @return []
     */
    public function __toArray()
    {
        $datas = [
            'id'   => (string)$this->id,
            'type' => (string)$this->type
        ];
        if ($this->attributes !== null) {
            $datas['attributes'] = $this->attributes->__toArray();
        }
        return $datas;
    }
}
