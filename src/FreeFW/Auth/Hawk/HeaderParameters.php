<?php
namespace FreeFW\Auth\Hawk;

use Psr\Http\Message\ServerRequestInterface;

/**
 *
 * @author jerome.klam
 *
 */
class HeaderParameters
{

    /**
     * Header arameters
     * @var array
     */
    protected $params = array();

    /**
     * Instance
     * @var HeaderParameters
     */
    protected static $instance = null;

    /**
     * Constructor
     *
     * @param ServerRequestInterface $request
     */
    protected function __construct(ServerRequestInterface $request)
    {
        $this->params = array();
        if ($request->hasHeader('authorization')) {
            $hawk           = $request->getHeader('authorization');
            $segments       = explode(', ', substr(trim($hawk), 5, -1));
            $this->params   = array();
            foreach ($segments as $idx => $oneSegment) {
                $parts = explode('=', $oneSegment);
                if (count($parts) >= 2) {
                    $index = $parts[0];
                    array_shift($parts);
                    $content              = implode('=', $parts);
                    $content              = trim($content, '"');
                    $this->params[$index] = $content;
                }
            }
        }
    }

    /**
     * Return instance
     *
     * @return HeaderParameters
     */
    public function getInstance(ServerRequestInterface $request)
    {
        if (self::$instance === null) {
            self::$instance = new static($request);
        }
        return self::$instance;
    }

    /**
     * Return all parameters
     *
     * @return array
     */
    public function getParameters()
    {
        return $this->params;
    }

    /**
     * Return one parameter value
     *
     * @param string $p_key
     * @param mixed  $p_default
     *
     * @return mixed|string
     */
    public function getParameter($p_key, $p_default = false)
    {
        if (array_key_exists($p_key, $this->params)) {
            return $this->params[$p_key];
        }
        return $p_default;
    }

    /**
     * Return application id
     *
     * @return mixed|string
     */
    public function getApplicationId()
    {
        return $this->getParameter('id', null);
    }
}
