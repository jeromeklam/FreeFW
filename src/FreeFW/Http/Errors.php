<?php
namespace FreeFW\Http;

/**
 * !! Do not implement Iterator !!
 * @author jerome.klam
 *
 */
class Errors implements \Serializable
{

    /**
     * Errors
     * @var array
     */
    protected $array = [];

    /**
     * Position
     * @var integer
     */
    protected $position = 0;

    /**
     * total
     * @var integer
     */
    protected $count = 0;

    /**
     * Constructor
     *
     * @param array $array
     */
    public function __construct($data = array())
    {
        if (is_array($data)) {
            $this->array = $data;
        }
        $this->count = count($this->array);
    }

    /**
     * Flush all errors
     *
     * @return \FreeFW\Http\Errors
     */
    public function flush()
    {
        $this->array    = [];
        $this->position = 0;
        $this->count    = 0;
        return $this;
    }

    /**
     * @see \Serializable
     */
    public function serialize()
    {
        return serialize($this->array);
    }

    /**
     * @see \Serializable
     */
    public function unserialize($data)
    {
        $this->array = unserialize($data);
    }

    /**
     * Return all errors
     *
     * @return array
     */
    public function getErrors()
    {
        return $this->array;
    }

    /**
     * Add a new error
     *
     * @param number $code
     * @param string $short
     * @param string $description
     * @param string $field
     * @param binary $type
     *
     * @return Errors
     */
    public function addError($code = 0, $short = null, $description = null, $field = null, $type = Error::TYPE_ERROR)
    {
        $error         = new Error($code, $short, $description, $field, $type);
        $this->array[] = $error;
        $this->count   = count($this->array);
        return $this;
    }

    /**
     * Add a critical error
     *
     * @param number $code
     * @param string $short
     * @param string $description
     * @param string $field
     * @param binary $type
     *
     * @return Errors
     */
    public function addCritical($code = 0, $short = null, $description = null, $field = null, $type = Error::TYPE_ERROR)
    {
        $error = new Error($code, $short, $description, $field, $type);
        $error->setStatus(500);
        $this->array[] = $error;
        $this->count   = count($this->array);
        return $this;
    }

    /**
     * Add required field error
     *
     * @param string $code
     * @param string $field_name
     *
     * @return \FreeFW\Http\Errors
     */
    public function addRequiredField($field_name, $code)
    {
        $error = new Error(
            $code,
            'Le champ est obligatoire',
            null,
            $field_name,
            Error::TYPE_REQUIRED
        );
        $error->setStatus(412);
        $this->array[] = $error;
        $this->count   = count($this->array);
        return $this;
    }

    /**
     * Add non unique field error
     *
     * @param string $code
     * @param string $field_name
     *
     * @return \FreeFW\Http\Errors
     */
    public function addNonUniqueField($field_name, $code)
    {
        $error = new Error(
            $code,
            'Le champ n\'est pas unique',
            null,
            $field_name,
            Error::TYPE_UNIQUE
        );
        $error->setStatus(412);
        $this->array[] = $error;
        $this->count   = count($this->array);
        return $this;
    }

    /**
     * Errors exists ?
     *
     * @return boolean
     */
    public function hasErrors()
    {
        return ($this->count > 0);
    }
}
