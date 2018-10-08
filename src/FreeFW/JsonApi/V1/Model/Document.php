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
     * @var array
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
    public function __construct()
    {
        $this->meta    = new \FreeFW\JsonApi\V1\Model\MetaObject();
        $this->jsonapi = new \FreeFW\JsonApi\V1\Model\JsonApiObject();
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
        return $return;
    }

}
