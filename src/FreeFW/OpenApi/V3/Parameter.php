<?php
namespace FreeFW\OpenApi\V3;

/**
 * OpenApi v3 Parameter object
 *
 * @author jeromeklam
 */
class Parameter extends \FreeFW\OpenApi\V3\Base
{

    /**
     * IN
     * @var string
     */
    const IN_PATH   = 'path';
    const IN_QUERY  = 'query';
    const IN_COOKIE = 'cookie';
    const IN_HEADER = 'header';

    /**
     * Name
     * @var string
     */
    protected $name = null;

    /**
     * In
     * @var string
     */
    protected $in = null;

    /**
     * Description
     * @var string
     */
    protected $description = null;

    /**
     * Obligatoire ?
     * @var bool
     */
    protected $required = null;

    /**
     * Schema
     * @var \FreeFW\OpenApi\V3\Schema
     */
    protected $schema = null;
}
