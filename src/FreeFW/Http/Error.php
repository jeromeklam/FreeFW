<?php
namespace FreeFW\Http;

/**
 *
 * @author jerome.klam
 *
 */
class Error
{
    const TYPE_ERROR    = 0;
    const TYPE_REQUIRED = 1;
    const TYPE_UNIQUE   = 2;

    /**
     * Error code
     * @var integer
     */
    protected $code = 0;

    /**
     * Error short description
     * @var string
     */
    protected $short = null;

    /**
     * Error description
     * @var string
     */
    protected $description = null;

    /**
     * Field name
     * @var string
     */
    protected $field = null;

    /**
     * Error type
     * @var binary
     */
    protected $type = self::TYPE_ERROR;

    /**
     * Status code
     * @var string
     */
    protected $status = '400';

    /**
     * Help link
     * @var string
     */
    protected $link = null;

    /**
     * Constructor
     *
     * @param number $code
     * @param string $short
     * @param string $description
     * @param string $field
     * @param binary $type
     */
    public function __construct($code = 0, $short = null, $description = null, $field = null, $type = self::TYPE_ERROR)
    {
        $this->code        = $code;
        $this->short       = $short;
        $this->description = $description;
        $this->field       = $field;
        $this->type        = $type;
    }

    /**
     * @return the $code
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param number $code
     *
     * @return Error
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return the $short
     */
    public function getShort()
    {
        return $this->short;
    }

    /**
     * @param string $short
     *
     * @return Error
     */
    public function setShort($short)
    {
        $this->short = $short;
        return $this;
    }

    /**
     * @return the $description
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return Error
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return the $field
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * @param string $field
     *
     * @return Error
     */
    public function setField($field)
    {
        $this->field = $field;
        return $this;
    }

    /**
     * @return the $type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param binary $type
     *
     * return Error
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return the $status
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     *
     * @return Error
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return the $link
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * @param string $link
     *
     * @return Error
     */
    public function setLink($link)
    {
        $this->link = $link;
        return $this;
    }
}
