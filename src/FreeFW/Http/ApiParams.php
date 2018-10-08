<?php
namespace FreeFW\Http;

/**
 * Standard Api params
 *
 * @author jeromeklam
 */
class ApiParams
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
     * @var array
     */
    protected $sort = [];

    /**
     * Page
     * @var integer
     */
    protected $page = 0;
}
