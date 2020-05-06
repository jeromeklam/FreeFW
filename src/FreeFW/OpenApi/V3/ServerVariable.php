<?php
namespace FreeFW\OpenApi\V3;

/**
 * OpenApi v3 ServerVariable object
 *
 * @author jeromeklam
 */
class ServerVariable extends \FreeFW\OpenApi\V3\Base
{

    /**
     * Default
     * @var string
     */
    protected $default = null;

    /**
     * Description
     * @var string
     */
    protected $description = null;

    /**
     * Enum
     * @var array
     */
    protected $enum = null;
}
