<?php
namespace FreeFW\Http;

/**
 * Api Server request
 *
 * @author jeromeklam
 */
class ApiServerRequest extends \GuzzleHttp\Psr7\ServerRequest implements
    \Psr\Http\Message\ServerRequestInterface
{

    /**
     * Filters
     * @var array
     */
    protected $filters = [];

    /**
     * Fields
     * @var array
     */
    protected $fields = [];

    /**
     * Includes
     * @var array
     */
    protected $includes = [];

    /**
     * Sort
     *
     * @var array
     */
    protected $sort = [];

    /**
     * Pagination
     *
     * @var integer
     */
    protected $page = 0;
}
