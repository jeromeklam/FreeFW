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
     * Include default or include with ApiParam
     * @var array
     */
    protected $include = [];

    /**
     * Options
     *
     * @var array
     */
    protected $option = [];

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

    /**
     * Set include
     *
     * @param mixed $p_include
     *
     * @return \FreeFW\Http\ApiParams
     */
    public function setInclude($p_include)
    {
        $this->include = $this->renderInclude($p_include);
        return $this;
    }

    /**
     * Get include
     *
     * @return array
     */
    public function getInclude()
    {
        return $this->include;
    }

    /**
     * Set option
     *
     * @param mixed $p_option
     * 
     * @return \FreeFW\Http\ApiParams
     */
    public function setOption($p_option)
    {
        $this->option = $p_option;
        return $this;
    }

    /**
     * Get Option
     *
     * @return Array
     */
    public function getOption()
    {
        return $this->option;
    }

    /**
     * render include
     *
     * @param string|array $p_include 'include1,include2,...' ou ['include1','include2',...]
     * @return array
     */
    public function renderInclude($p_include)
    {
        if (is_array($p_include)) {
            $include = $p_include;
        } else {
            $include = explode(',', str_replace(' ', '', $p_include));
        }

        return $include;
    }

    /**
     * Set sort
     *
     * @param mixed $p_sort
     *
     * @return \FreeFW\Http\ApiParams
     */
    public function setSort($p_sort)
    {
        if (is_array($p_sort)) {
            $this->sort = $p_sort;
        } else {
            $this->sort = [];
            $sorts = explode(',', $p_sort);
            foreach ($sorts as $idx => $field) {
                if ($field[0] == '-') {
                    $this->sort[substr($field, 1)] = '-';
                } else {
                    if ($field[0] == '-') {
                        $this->sort[substr($field, 1)] = '+';
                    } else {
                        $this->sort[$field] = '+';
                    }
                }
            }
        }
        return $this;
    }

    /**
     * Get sort
     *
     * @return array
     */
    public function getSort()
    {
        return $this->sort;
    }

    /**
     *
     * @param mixed $p_fields
     *
     * @return array[]|NULL[]
     */
    public function renderFields($p_fields)
    {
        $fields = [
            'relations' => [],
            'model' => []
        ];
        if (is_array($p_fields)) {
            foreach ($p_fields as $key => $flds) {
                if (is_int($key)) {
                    $fields['model'] = array_merge($fields['model'], explode(',', str_replace(' ', '', $flds)));
                } else {
                    $fields['relations'][$key] = explode(',', str_replace(' ', '', $flds));
                }
            }
        } else {
            $fields['model'] = explode(',', str_replace(' ', '', $p_fields));
        }
        return $fields;
    }

    /**
     * Set fields
     *
     * @param string|array $p_fields
     *
     * @return boolean
     */
    public function setFields($p_fields)
    {
        $this->fields = $this->renderFields($p_fields);
        return $this;
    }

    /**
     * Get
     * @return array|string[]|array[]
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * @return array of string
     */
    public function getFieldsFor($p_relation)
    {
        $fields = $this->getFields();
        if ($p_relation === '') {
            if (isset($fields['model'])) {
                return $fields['model'];
            }
        }
        if (isset($fields['relations'])) {
            if (isset($fields[$p_relation])) {
                return $fields[$p_relation];
            }
        }
        return [];
    }

    /**
     * Get request unis id
     * 
     * @return string
     */
    public function getUniqId()
    {
        $datas = [
            'filters' => json_encode($this->getFilters()),
            'include' => json_encode($this->getInclude())
        ];
        return md5(json_encode($datas));
    }
}
