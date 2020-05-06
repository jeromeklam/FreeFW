<?php
namespace FreeFW\OpenApi\V3;

/**
 * OpenApi v3 Components object
 *
 * @author jeromeklam
 */
class Components extends \FreeFW\OpenApi\V3\Base
{

    /**
     * Schemas
     * @var [\FreeFW\OpenApi\V3\Schema]
     */
    protected $schemas = null;

    /**
     * Responses
     * @var string
     */
    protected $responses = null;

    /**
     * Headers
     * @var [\FreeFW\OpenApi\V3\Header]
     */
    protected $headers = null;
}
