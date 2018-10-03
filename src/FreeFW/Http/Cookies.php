<?php
namespace FreeFW\Http;

/**
 * Cookies
 *
 * @author jeromeklam
 */
class Cookies implements \Countable, \Iterator, \ArrayAccess
{

    /**
     * Cookies
     * @var array
     */
    protected $cookies = [];

    /**
     *
     * {@inheritDoc}
     * @see \Iterator::next()
     */
    public function next()
    {
        next($this->cookies);
    }

    /**
     *
     * {@inheritDoc}
     * @see \Iterator::valid()
     */
    public function valid()
    {
        return key($this->cookies) !== null;
    }

    /**
     *
     * {@inheritDoc}
     * @see \Iterator::current()
     */
    public function current()
    {
        return current($this->cookies);
    }

    /**
     *
     * {@inheritDoc}
     * @see \Iterator::rewind()
     */
    public function rewind()
    {
        reset($this->cookies);
    }

    /**
     *
     * {@inheritDoc}
     * @see \Countable::count()
     */
    public function count()
    {
        return count($this->cookies);
    }

    /**
     *
     * {@inheritDoc}
     * @see \Iterator::key()
     */
    public function key()
    {
        $key = key($this->cookies);
        if ($key === null) {
            return false;
        }
        $cookie = $this->cookies[$key];
        return $cookie->getName();
    }

    /**
     *
     * @param unknown $offset
     * @return NULL
     */
    public function offsetGet($offset)
    {
        return isset($this->cookies[$offset]) ? $this->cookies[$offset] : null;
    }

    /**
     *
     * @param unknown $offset
     * @return unknown
     */
    public function offsetExists($offset)
    {
        return isset($this->cookies[$offset]);
    }

    /**
     *
     * @param unknown $offset
     */
    public function offsetUnset($offset)
    {
        unset($this->cookies[$offset]);
    }

    /**
     *
     * @param unknown $offset
     * @param unknown $value
     */
    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->cookies[] = $value;
        } else {
            $this->cookies[$offset] = $value;
        }
    }

    /**
     * Has
     *
     * @param string $key
     *
     * @return \FreeFW\Http\Cookie
     */
    public function has($key)
    {
        return isset($this->cookies[$key]);
    }
}
