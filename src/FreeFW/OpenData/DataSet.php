<?php

namespace FreeFW\OpenData;

/**
 * Model DataSet
 *
 * @author jeromeklam
 */
class DataSet
{

    /**
     * Factory pattern
     * @var Array
     */
    protected static $factory = [];

    /**
     * Elem
     * @var \FreeFW\OpenData\ObjectInterface
     */
    protected $elem = null;

    /**
     * Protected constructor
     * 
     * @param String $p_dataset
     */
    protected function __construct($p_dataset)
    {
        if (class_exists($p_dataset)) {
            $elem = new $p_dataset();
            if (!$elem instanceof \FreeFW\OpenData\ObjectInterface) {
                throw new \InvalidArgumentException($p_dataset . ' is not on OpenData Object');
            }
            $this->elem = $elem;
        } else {
            throw new \InvalidArgumentException($p_dataset . ' is unknown !');
        }
    }

    /**
     * Get factory
     * 
     * @param String $p_dataset
     * 
     * @return \FreeFW\OpenData\DataSet
     */
    public function getFactory($p_dataset)
    {
        if (!isset(self::$factory[$p_dataset])) {
            self::$factory[$p_dataset] = new self($p_dataset);
        }
        return self::$factory[$p_dataset];
    }

    /**
     * Get datas
     * 
     * @param String $p_where
     * 
     * @return String
     */
    public function get($p_where)
    {
        $basepath = $this->elem->getBasePath();

        $url = rtrim($basepath, '/') . '/' . $this->elem->getDataSet() . '/records?';
        if ($p_where != '') {
            $url .= 'where=' . urlencode($p_where);
        }
        $client = new \FreeFW\Http\Client\CurlWrapper();
        return $client->get($url);
    }

    /**
     * Find elements
     * 
     * @param \FreeFW\OpenData\ObjectInterface $p_object
     * 
     * @return Array | false
     */
    public function find(\FreeFW\OpenData\ObjectInterface $p_object, $p_operator = 'like', $p_and_or = 'and')
    {
        $results = false;
        $where   = '';
        $reflect = new \ReflectionClass($p_object);
        $props   = $reflect->getProperties();
        foreach ($props as $oneProp) {
            $add = false;
            if ($p_object->{$oneProp->getName()} !== null) {
                $add = $oneProp->getName() . ' ' . $p_operator . ' \'' . $p_object->{$oneProp->getName()} . '\'';
            }
            if ($add) {
                if ($where != '') {
                    $where .= ' ' . $p_and_or;
                }
                $where .= ' ' . $add;
            }
        }
        $results = $this->get($where);
        if ($results) {
            $json = json_decode($results);
            if ($json) {
                if (isset($json->total_count)) {
                    $total = $json->total_count;
                    if ($total > 0 && isset($json->results)) {
                        $results = [];
                        foreach ($json->results as $oneElem) {
                            $elem = clone($this->elem);
                            foreach ($props as $oneProp) {
                                if (isset($oneElem->{$oneProp->getName()})) {
                                    $elem->{$oneProp->getName()} = $oneElem->{$oneProp->getName()};
                                }
                            }
                            $results[] = $elem;
                        }
                    }
                }
            }
        }
        return $results;
    }
}
