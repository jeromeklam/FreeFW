<?php
namespace FreeFW\JsonApi\V1\Model;

/**
 * An error object
 *
 * @author jeromeklam
 */
class ErrorObject
{

    /**
     * A unique identifier for this particular occurrence of the problem.
     * @var string
     */
    protected $id = null;

    /**
     * a links object containing the following members
     * @var \FreeFW\JsonApi\V1\Model\LinksObject
     */
    protected $links = null;

    /**
     * a link that leads to further details about this particular occurrence of the problem.
     * @var string
     */
    protected $about = null;

    /**
     * the HTTP status code applicable to this problem, expressed as a string value.
     * @var int
     */
    protected $status = 200;

    /**
     * an application-specific error code, expressed as a string value.
     * @var string
     */
    protected $code = null;

    /**
     * a short, human-readable summary of the problem that SHOULD NOT change from occurrence to occurrence
     * of the problem, except for purposes of localization.
     * @var string
     */
    protected $title = null;

    /**
     * a human-readable explanation specific to this occurrence of the problem. Like title,
     * this field’s value can be localized.
     * @var string
     */
    protected $detail = null;

    /**
     * an object containing references to the source of the error,
     * optionally including any of the following members:
     * @var string
     */
    protected $source = null;

    /**
     * a JSON Pointer [RFC6901] to the associated entity in the request document
     * [e.g. "/data" for a primary data object, or "/data/attributes/title" for a specific attribute].
     */
    protected $pointer = null;

    /**
     * a string indicating which URI query parameter caused the error.
     * @var string
     */
    protected $parameter = null;

    /**
     * Meta
     * @var \FreeFW\JsonApi\V1\Model\MetaObject
     */
    protected $meta = null;

    /**
     * Constructor
     *
     * @param int    $p_status
     * @param string $p_message
     * @param string $p_code
     */
    public function __construct(int $p_status, string $p_message, string $p_code)
    {
        $this->status = $p_status;
        $this->title  = $p_message;
        $this->code   = $p_code;
    }

    /**
     * Magic
     *
     * @return array
     */
    public function __toArray()
    {
        $result = [];
        if ($this->status !== null) {
            $result['status'] = $this->status;
        }
        if ($this->code !== null) {
            $result['code'] = $this->code;
        }
        if ($this->title !== null) {
            $result['title'] = $this->title;
        }
        return $result;
    }
}
