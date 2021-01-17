<?php
namespace FreeFW\JsonApi\V1\Model;

/**
 * Attributes object
 *
 * @author jeromeklam
 */
class IncludedObject implements \Countable
{

    /**
     * Resources
     * @var array | null
     */
    protected $resources = [];

    /**
     * Incomings
     * @var array
     */
    protected $incoming = [];

    /**
     * Add resource
     *
     * @param \FreeFW\JsonApi\V1\Model\ResourceObject $p_resource
     *
     * @return \FreeFW\JsonApi\V1\Model\IncludedObject
     */
    public function addIncluded(\FreeFW\JsonApi\V1\Model\ResourceObject $p_resource)
    {
        $key = $p_resource->getType() . '.' . $p_resource->getId();
        $this->resources[$key] = $p_resource;
        return $this;
    }

    /**
     * Add resource
     *
     * @param \FreeFW\JsonApi\V1\Model\ResourceObject $p_resource
     *
     * @return \FreeFW\JsonApi\V1\Model\IncludedObject
     */
    public function addIncoming(\FreeFW\JsonApi\V1\Model\ResourceObject $p_resource)
    {
        $key = $p_resource->getType() . '.' . $p_resource->getId();
        $this->incoming[$key] = $p_resource;
        return $this;
    }

    /**
     * Get included resources
     *
     * @return array
     */
    public function getIncluded()
    {
        return $this->resources;
    }

    /**
     *
     * @param \FreeFW\JsonApi\V1\Model\ResourceObject $p_resource
     *
     * @return boolean
     */
    public function incomingExists($p_resource)
    {
        $key = $p_resource->getType() . '.' . $p_resource->getId();
        if (isset($this->incoming[$key])) {
            return true;
        }
        return false;
    }

    /**
     *
     * {@inheritDoc}
     * @see \Countable::count()
     */
    public function count()
    {
        return count($this->resources);
    }
}
