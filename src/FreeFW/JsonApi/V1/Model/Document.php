<?php
namespace FreeFW\JsonApi\V1\Model;

use function GuzzleHttp\json_encode;

/**
 * JsonApi V1
 *
 * @author jeromeklam
 */
class Document implements \JsonSerializable
{

    /**
     * Data
     * @var \FreeFW\JsonApi\V1\Model\ResourceObject | array[\FreeFW\JsonApi\V1\Model\ResourceObject]
     */
    protected $data = null;

    /**
     * Errors
     * @var \FreeFW\JsonApi\V1\Model\ErrorsObject
     */
    protected $errors = null;

    /**
     * Meta
     * @var \FreeFW\JsonApi\V1\Model\MetaObject
     */
    protected $meta = null;

    /**
     * Links
     * @var \FreeFW\JsonApi\V1\Model\LinksObject
     */
    protected $links = null;

    /**
     * Included
     * @var array[\FreeFW\JsonApi\V1\Model\ResourceObject]
     */
    protected $included = null;

    /**
     * Server description
     * @var object
     */
    protected $jsonapi = null;

    /**
     * COnstructor
     */
    public function __construct(\stdClass $p_data = null)
    {
        $this->meta    = new \FreeFW\JsonApi\V1\Model\MetaObject();
        $this->jsonapi = new \FreeFW\JsonApi\V1\Model\JsonApiObject();
        if ($p_data !== null) {
            $this->getFromObject($p_data);
        }
    }

    /**
     * Add an error
     *
     * @param \FreeFW\JsonApi\V1\Model\ErrorObject $p_error
     *
     * @return self
     */
    public function addError(\FreeFW\JsonApi\V1\Model\ErrorObject $p_error) : self
    {
        if ($this->errors === null) {
            $this->errors = new \FreeFW\JsonApi\V1\Model\ErrorsObject();
        }
        $this->errors[] = $p_error;
        return $this;
    }

    /**
     * Set data
     *
     * @param mixed $p_data
     *
     * @return \FreeFW\JsonApi\V1\Model\Document
     */
    public function setData($p_data)
    {
        $this->data = $p_data;
        return $this;
    }

    /**
     * Get Data
     *
     * @return \FreeFW\JsonApi\V1\Model\ResourceObject
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Add one element to data
     *
     * @param mixed $p_data
     *
     * @return \FreeFW\JsonApi\V1\Model\Document
     */
    public function addData($p_data)
    {
        if ($this->data === null) {
            $this->data = [];
        }
        $this->data[] = $p_data;
        return $this;
    }

    public function __toJson()
    {
        $return = [];
        if ($this->jsonapi !== null) {
            $return['jsonapi'] = $this->jsonapi->__toArray();
        }
        if ($this->meta !== null) {
            $return['meta'] = $this->meta->__toArray();
        }
        if ($this->errors !== null) {
            $return['errors'] = $this->errors->__toArray();
        }
        if ($this->data !== null) {
            if (is_array($this->data)) {
                $data = [];
                foreach ($this->data as $idx => $oneData) {
                    $data[] = $oneData;
                }
                $return['data'] = $data;
            } else {
                $return['data'] = $this->data;
            }
            $resources = $this->included->getIncluded();
            if (count($resources) > 0) {
                $return['included'] = [];
                foreach ($resources as $key => $resource) {
                    $return['included'][] = $resource;
                }
            }
        }
        return $return;
    }
    /**
     *
     * {@inheritDoc}
     * @see \JsonSerializable::jsonSerialize()
     */
    public function jsonSerialize()
    {
        return $this->__toJson();
    }

    /**
     * Get resourceObject
     *
     * @param \StdClass $p_object
     *
     * @return \FreeFW\JsonApi\V1\Model\ResourceObject
     */
    protected function getResourceObject(\StdClass $p_object)
    {
        if (isset($p_object->type)) {
            $type = $p_object->type;
            $id   = null;
            if (isset($p_object->id)) {
                $id = $p_object->id;
            }
            $resource = new \FreeFW\JsonApi\V1\Model\ResourceObject($type, $id);
            if (isset($p_object->attributes)) {
                $attributes = new \FreeFW\JsonApi\V1\Model\AttributesObject((array)$p_object->attributes);
                $resource->setAttributes($attributes);
            }
            if (isset($p_object->relationships)) {
                $relations = new \FreeFW\JsonApi\V1\Model\RelationshipsObject((array)$p_object->relationships);
                $resource->setRelationShips($relations);
            }
            return $resource;
        }
        throw new \FreeFW\JsonApi\FreeFWJsonApiException('type is required in data attribute !');
    }

    /**
     * Set included object
     *
     * @param \FreeFW\JsonApi\V1\Model\IncludedObject $p_inluded
     *
     * @return \FreeFW\JsonApi\V1\Model\Document
     */
    public function setIncluded(\FreeFW\JsonApi\V1\Model\IncludedObject $p_inluded)
    {
        $this->included = $p_inluded;
        return $this;
    }

    /**
     * Get from StdClass
     *
     * @param \stdClass $p_data
     *
     * @return \FreeFW\JsonApi\V1\Model\Document
     */
    protected function getFromObject(\stdClass $p_data = null)
    {
        if (isset($p_data->data)) {
            $data = $p_data->data;
            if ($data instanceof \stdClass) {
                // Single object
                $this->data = $this->getResourceObject($data);
            } else {
                if (is_array($data)) {
                    // Collection
                } else {
                    // @todo
                }
            }
        }
        return $this;
    }

    /**
     * Is jsonApi
     *
     * @return boolean
     */
    public function isJsonApi()
    {
        if ($this->data instanceof \FreeFW\JsonApi\V1\Model\ResourceObject) {
            return true;
        } else {
            die('trtrtr');
        }
        return false;
    }

    /**
     * Simple resource ?
     *
     * @return boolean
     */
    public function isSimpleResource()
    {
        if ($this->data instanceof \FreeFW\JsonApi\V1\Model\ResourceObject) {
            return true;
        }
        return false;
    }

    /**
     * Has errors
     *
     * @return bool
     */
    public function hasErrors()
    {
        return ($this->errors !== null);
    }

    /**
     * Get Http code
     *
     * @return int
     */
    public function getHttpCode()
    {
        $code= 200;
        if ($this->errors) {
            /**
             * @var \FreeFW\JsonApi\V1\Model\ErrorObject $oneError
             */
            foreach ($this->errors as $idx => $oneError) {
                if ($oneError->getStatus() > $code) {
                    $code = $oneError->getStatus();
                }
            }
        }
        return $code;
    }
}
