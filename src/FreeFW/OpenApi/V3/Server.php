<?php
namespace FreeFW\OpenApi\V3;

/**
 * OpenApi v3 Server object
 *
 * @author jeromeklam
 */
class Server extends \FreeFW\OpenApi\V3\Base
{

    /**
     * Url
     * @var string
     */
    protected $url = null;

    /**
     * Description
     * @var string
     */
    protected $description = null;

    /**
     * Variables
     * @var array
     */
    protected $variables = null;
}
