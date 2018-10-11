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

    /**
     * Data
     * @var \FreeFW\Core\Model
     */
    protected $data = null;

    /**
     * Get new model
     *
     * @param string $p_model
     *
     * @return \FreeFW\Core\Model
     */
    public function getApiModel(string $p_model) : \FreeFW\Core\Model
    {
        $class = str_replace('_', '::Model::', $p_model);
        return \FreeFW\DI\DI::get($class);
    }

    /**
     * Set data
     *
     * @param \FreeFW\Core\Model $p_data
     *
     * @return \FreeFW\Http\ApiParams
     */
    public function setData(\FreeFW\Core\Model $p_data)
    {
        $this->data = $p_data;
        return $this;
    }

    /**
     * Get Data
     *
     * @return \FreeFW\Core\Model
     */
    public function getData() : \FreeFW\Core\Model
    {
        return $this->data;
    }

    /**
     * Has data ?
     *
     * @return bool
     */
    public function hasData() : bool
    {
        return ($this->data instanceof \FreeFW\Core\Model);
    }
}
