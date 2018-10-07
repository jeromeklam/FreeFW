<?php
namespace FreeFW\Http;

/**
 * Cookie
 *
 * @author jeromeklam
 */
class Cookie
{

    /**
     * Name
     * @var string
     */
    protected $name = null;

    /**
     * Value
     * @var mixed
     */
    protected $value = null;

    /**
     *
     * @var string
     */
    protected $expiresAt = null;

    /**
     *
     * @var string
     */
    protected $path = null;

    /**
     *
     * @var string
     */
    protected $domain = null;

    /**
     * Constructor
     *
     * @param string $p_name
     * @param mixed  $p_value
     */
    public function __construct(
        string $p_name,
        $p_value,
        $p_expireAt = null,
        $p_path = null,
        $p_domain = null
    ) {
        $this->name      = $p_name;
        $this->value     = $p_value;
        $this->expiresAt = $p_expireAt;
        $p_path          = $p_path;
        $p_domain        = $p_domain;
    }

    /**
     * Set name
     *
     * @param string $p_name
     *
     * @return \FreeFW\Http\Cookie
     */
    public function setName(string $p_name)
    {
        $this->name = $p_name;
        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set value
     *
     * @param mixed $p_value
     *
     * @return \FreeFW\Http\Cookie
     */
    public function setValue($p_value)
    {
        $this->value = $p_value;
        return $this;
    }

    /**
     * Get value
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * As string
     *
     * @return string
     */
    public function __toString()
    {
        $headerValue = sprintf('%s=%s', $this->name, urlencode($this->value));
        if ($this->expiresAt !== 0) {
            $headerValue .= sprintf(
                '; expires=%s',
                gmdate('D, d-M-Y H:i:s T', $this->expiresAt)
            );
        }
        if (empty($this->path) === false) {
            $headerValue .= sprintf('; path=%s', $this->path);
        }
        if (empty($this->domain) === false) {
            $headerValue .= sprintf('; domain=%s', $this->domain);
        }
        return $headerValue;
    }
}
