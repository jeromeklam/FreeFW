<?php
namespace FreeFW\JsonApi\V1\Model;

/**
 * Errors object
 *
 * @author jeromeklam
 */
class ErrorsObject implements
    \ArrayAccess,
    \Countable,
    \Iterator
{

    /**
     * Errors
     * @var array[\FreeFW\JsonApi\V1\Model\ErrorObject]
     */
    protected $errors = [];

    /**
     * Count
     * @var number
     */
    protected $my_count = 0;

    /**
     * Total count
     * @var number
     */
    protected $total_count = 0;

    /**
     * Constructeur
     *
     * @param array $p_array
     */
    public function __construct(array $p_array = [])
    {
        if (is_array($p_array)) {
            $this->errors = $p_array;
        }
        $this->my_count = count($this->errors);
    }

    /**
     * Get total count
     *
     * @return number
     */
    public function getTotalCount()
    {
        return $this->total_count;
    }

    /**
     * Set total count
     *
     * @param number $p_count
     *
     * @return \FreeFW\Model\ResultSet
     */
    public function setTotalCount($p_count)
    {
        $this->total_count = $p_count;
        return $this;
    }

    /**
     *
     * {@inheritDoc}
     * @see \Iterator::rewind()
     */
    public function rewind()
    {
        reset($this->errors);
    }

    /**
     *
     * {@inheritDoc}
     * @see \Iterator::current()
     */
    public function current()
    {
        $var = current($this->errors);
        return $var;
    }

    /**
     *
     * {@inheritDoc}
     * @see \Iterator::key()
     */
    public function key()
    {
        $var = key($this->errors);
        return $var;
    }

    /**
     *
     * {@inheritDoc}
     * @see \Iterator::next()
     */
    public function next()
    {
        $var = next($this->errors);
        return $var;
    }

    /**
     *
     * {@inheritDoc}
     * @see \Iterator::valid()
     */
    public function valid()
    {
        $key = key($this->errors);
        $var = ($key !== null && $key !== false);
        return $var;
    }

    /**
     *
     * {@inheritDoc}
     * @see \Countable::count()
     */
    public function count()
    {
        return $this->my_count;
    }

    /**
     *
     * @param \FreeFW\Core\Model $value
     *
     * @return \FreeFW\Model\ResultSet
     */
    public function add($value)
    {
        $this->errors[]    = $p_value;
        $this->my_count = count($this->errors);
        return $this;
    }

    /**
     *
     * {@inheritDoc}
     * @see \ArrayAccess::offsetSet()
     */
    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->errors[] = $value;
        } else {
            $this->errors[$offset] = $value;
        }
        $this->my_count = count($this->errors);
    }

    /**
     *
     * {@inheritDoc}
     * @see \ArrayAccess::offsetExists()
     */
    public function offsetExists($offset)
    {
        return isset($this->errors[$offset]);
    }

    /**
     *
     * {@inheritDoc}
     * @see \ArrayAccess::offsetUnset()
     */
    public function offsetUnset($offset)
    {
        unset($this->errors[$offset]);
        $this->my_count = count($this->errors);
    }

    /**
     *
     * {@inheritDoc}
     * @see \ArrayAccess::offsetGet()
     */
    public function offsetGet($offset)
    {
        return isset($this->errors[$offset]) ? $this->errors[$offset] : null;
    }

    /**
     * Empty ?
     *
     * @return boolean
     */
    public function isEmpty()
    {
        if ($this->my_count <= 0) {
            return true;
        }
        return false;
    }

    /**
     * Magic
     *
     * @return array
     */
    public function __toArray()
    {
        $result = [];
        foreach ($this->errors as $idx => $oneError) {
            $result[] = $oneError->__toArray();
        }
        return $result;
    }
}
