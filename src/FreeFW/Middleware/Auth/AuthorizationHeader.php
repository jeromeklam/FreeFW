<?php
namespace FreeFW\Middleware\Auth;

/**
 *
 * @author jeromeklam
 *
 */
class AuthorizationHeader {

    /**
     * Header type
     * @var string
     */
    protected $type = null;

    /**
     * Parameters
     * @var array
     */
    protected $params = [];

    /**
     * Constructor
     *
     * @param string $p_auth_string
     */
    public function __construct($p_auth_string = '')
    {
        $this->type   = '';
        $this->params = [];
        if (trim($p_auth_string) != '') {
            $this->parse($p_auth_string);
        }
    }

    /**
     * Pase auth string
     *
     * @param string $p_auth_string
     */
    public function parse($p_auth_string)
    {
        if (trim($p_auth_string) != '') {
            $words        = str_word_count(trim($p_auth_string), 2);
            $this->type   = strtoupper($words[0]);
            $parameters   = trim(substr(trim($p_auth_string), strlen($this->type)));
            $segments     = explode(',', $parameters);
            $this->params = [];
            foreach ($segments as $oneSegment) {
                $parts = explode('=', $oneSegment);
                if (count($parts) >= 2) {
                    $index = str_replace(' ', '_', strtolower(trim($parts[0])));
                    array_shift($parts);
                    $content              = implode('=', $parts);
                    $content              = trim($content, '"');
                    $this->params[$index] = $content;
                }
            }
        }
    }

    /**
     * Set type
     *
     * @param string $p_type
     *
     * @return \FreeFW\Middleware\Auth\AuthorizationHeader
     */
    public function setType($p_type)
    {
        $this->type = $p_type;
        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Get parameters
     *
     * @return array
     */
    public function getParameters()
    {
        return $this->params;
    }

    /**
     * Get one parameter
     *
     * @param string $p_name
     * @param string $p_default
     *
     * @return string
     */
    public function getParameter($p_name, $p_default = '')
    {
        if (array_key_exists($p_name, $this->params)) {
            return $this->params[$p_name];
        }
        return $p_default;
    }

    /**
     * Add parameter
     *
     * @param string $p_name
     * @param mixed $p_value
     *
     * @return \FreeFW\Middleware\Auth\AuthorizationHeader
     */
    public function addParameter($p_name, $p_value)
    {
        $this->params[$p_name] = $p_value;
        return $this;
    }

    /**
     * Get as string
     *
     * @return string
     */
    public function __toString()
    {
        $str    = \FreeFW\Tools\PBXString::toCamelCase(strtolower($this->type), true);
        $params = '';
        foreach ($this->params as $name => $value) {
            if ($params != '') {
                $params = $params . ', ';
            }
            $params = $params . $name . '="' . $value . '"';
        }
        return trim($str . ' ' . $params);
    }
}
