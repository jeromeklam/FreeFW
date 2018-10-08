<?php
namespace FreeFW\JsonApi\V1\Model;

/**
 * JsonApi Object
 *
 * @author jeromeklam
 */
class JsonApiObject
{

    /**
     * Version
     * @var string
     */
    protected $version = '1.0';

    /**
     * Get as array
     *
     * @return string[]
     */
    public function __toArray()
    {
        return [
            'version' => $this->version
        ];
    }
}
