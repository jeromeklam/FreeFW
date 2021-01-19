<?php
namespace FreeFW\Model;

use FreeFW\Core\Model;

/**
 * Query resultset
 *
 * @author jeromeklam
 * @package SQL\Result
 */
class ResultSet extends \FreeFW\Core\Model implements
    \ArrayAccess,
    \Countable,
    \Iterator,
    \JsonSerializable
{

    /**
     * Behaviour
     */
    use \FreeFW\Behaviour\ValidatorTrait;

    /**
     * Models
     * @var array
     */
    protected $var = [];

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
        parent::__construct(\FreeFW\DI\DI::getShared('config'), \FreeFW\DI\DI::getShared('logger'));
        if (is_array($p_array)) {
            $this->var = $p_array;
        }
        $this->my_count = count($this->var);
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
        reset($this->var);
    }

    /**
     *
     * {@inheritDoc}
     * @see \Iterator::current()
     */
    public function current()
    {
        $var = current($this->var);
        return $var;
    }

    /**
     *
     * {@inheritDoc}
     * @see \Iterator::key()
     */
    public function key()
    {
        $var = key($this->var);
        return $var;
    }

    /**
     *
     * {@inheritDoc}
     * @see \Iterator::next()
     */
    public function next()
    {
        $var = next($this->var);
        return $var;
    }

    /**
     *
     * {@inheritDoc}
     * @see \Iterator::valid()
     */
    public function valid()
    {
        $key = key($this->var);
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
    public function add($p_value)
    {
        $this->var[]    = $p_value;
        $this->my_count = count($this->var);
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
            $this->var[] = $value;
        } else {
            $this->var[$offset] = $value;
        }
        $this->my_count = count($this->var);
    }

    /**
     *
     * {@inheritDoc}
     * @see \ArrayAccess::offsetExists()
     */
    public function offsetExists($offset)
    {
        return isset($this->var[$offset]);
    }

    /**
     *
     * {@inheritDoc}
     * @see \ArrayAccess::offsetUnset()
     */
    public function offsetUnset($offset)
    {
        unset($this->var[$offset]);
        $this->my_count = count($this->var);
    }

    /**
     *
     * {@inheritDoc}
     * @see \ArrayAccess::offsetGet()
     */
    public function offsetGet($offset)
    {
        return isset($this->var[$offset]) ? $this->var[$offset] : null;
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
        $result = array();
        foreach ($this->var as $line) {
            $result[] = $line->__toArray();
        }
        return $result;
    }

    /**
     * Magic
     *
     * @return array
     */
    public function __toArrayFiltered($p_fields = null, $p_include = null)
    {
        $result = array();
        foreach ($this->var as $line) {
            $result[] = $line->__toArrayFiltered($p_fields, $p_include);
        }
        return $result;
    }

    /**
     * Convert to json
     *
     * @param string $p_version
     *
     * @return array
     */
    public function __toJsonApi($p_version)
    {
        $result = array();
        foreach ($this->var as $line) {
            if (is_object($line) && method_exists($line, '__toJsonApi')) {
                $result[] = $line->__toJsonApi($p_version);
            } else {
                throw new \Exception('Impossible de convertir le résultat en json api, contenu incorrect !');
            }
        }
        return $result;
    }

    /**
     *
     * {@inheritDoc}
     * @see \JsonSerializable::jsonSerialize()
     */
    public function jsonSerialize()
    {
        return $this->__toArray();
    }

    /**
     * get all datas
     *
     * @return array
     */
    public function getDatas()
    {
        return $this->var;
    }

    /**
     * @see \FreeFW\Interfaces\ApiResponseInterface
     *
     * @return bool
     */
    public function isSingleElement() : bool
    {
        return false;
    }

    /**
     * @see \FreeFW\Interfaces\ApiResponseInterface
     *
     * @return bool
     */
    public function isArrayElement() : bool
    {
        return true;
    }
}
