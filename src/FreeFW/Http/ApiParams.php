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
     * @var \FreeFW\Model\Conditions
     */
    protected $filters = null;

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
     * Start
     * @var integer
     */
    protected $start = 0;

    /**
     * Length
     * @var integer
     */
    protected $length = 0;

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

    /**
     * Set filters
     *
     * @param \FreeFW\Model\Conditions $p_filters
     *
     * @return \FreeFW\Http\ApiParams
     */
    public function setFilters(\FreeFW\Model\Conditions $p_filters)
    {
        $this->filters = $p_filters;
        return $this;
    }

    /**
     * Get filters
     *
     * @return \FreeFW\Model\Conditions
     */
    public function getFilters()
    {
        return $this->filters;
    }

    /**
     * Set start
     *
     * @param int $p_start
     *
     * @return \FreeFW\Http\ApiParams
     */
    public function setStart($p_start)
    {
        $this->start = $p_start;
        return $this;
    }

    /**
     * Get start
     *
     * @return int
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * Set length
     *
     * @param int $p_length
     *
     * @return \FreeFW\Http\ApiParams
     */
   public function setLength($p_length)
   {
       $this->length = $p_length;
       return $this;
   }

   /**
    * Get length
    *
    * @return int
    */
   public function getlength()
   {
       return $this->length;
   }
}
