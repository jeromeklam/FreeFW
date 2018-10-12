<?php
namespace FreeFW\Core;

/**
 * Basic error
 *
 * @author jeromeklam
 */
class Error
{

    /**
     * Types
     * @var int
     */
    const TYPE_ERROR        = 500;
    const TYPE_PRECONDITION = 412;

    /**
     * Error code
     * @var integer
     */
    protected $code = 0;

    /**
     * Error message
     * @var string
     */
    protected $message = null;

    /**
     * Error type
     * @var int
     */
    protected $type = self::TYPE_ERROR;

    /**
     * Field
     * @var mixed
     */
    protected $field = null;

    /**
     * Constructor
     *
     * @param int    $p_code
     * @param string $p_message
     * @param int    $p_type
     * @param mixed  $p_field
     */
    public function __construct(
        int $p_code,
        $p_message = null,
        $p_type = \FreeFW\Core\Error::TYPE_ERROR,
        $p_field = null
    ) {
        $this
            ->setCode($p_code)
            ->setMessage($p_message)
            ->setType($p_type)
            ->setField($p_field)
        ;
    }

    /**
     * Set code
     *
     * @param int $p_code
     *
     * @return \FreeFW\Core\Error
     */
    public function setCode(int $p_code)
    {
        $this->code = $p_code;
        return $this;
    }

    /**
     * Get code
     *
     * @return int
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set message
     *
     * @param string $p_message
     *
     * @return \FreeFW\Core\Error
     */
    public function setMessage($p_message)
    {
        $this->message = $p_message;
        return $this;
    }

    /**
     * Get message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set type
     *
     * @param int $p_type
     *
     * @return \FreeFW\Core\Error
     */
    public function setType(int $p_type)
    {
        $this->type = $p_type;
        return $this;
    }

    /**
     * Get type
     *
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set field
     *
     * @param mixed $p_field
     *
     * @return \FreeFW\Core\Error
     */
    public function setField($p_field)
    {
        $this->field = $p_field;
        return $this;
    }

    /**
     * Get field
     *
     * @return mixed
     */
    public function getField()
    {
        return $this->field;
    }
}
