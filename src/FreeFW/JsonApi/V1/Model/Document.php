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
     * @param unknown $p_data
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
     *
     * {@inheritDoc}
     * @see \JsonSerializable::jsonSerialize()
     */
    public function jsonSerialize()
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
            $return['data'] = $this->data->__toArray();
        }
        return $return;
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
            return $resource;
        }
        throw new \FreeFW\Core\FreeFWJsonApiException('type is required in data attribute !');
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
