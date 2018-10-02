<?php
namespace FreeFW\Behaviour;

/**
 *
 * @author jeromeklam
 *
 */
trait RequestAwareTrait
{

    /**
     * Request
     * @var \Psr\Http\Message\ServerRequestInterface
     */
    protected $request = null;

    /**
     * Set request
     *
     * @param \Psr\Http\Message\ServerRequestInterface $p_request
     *
     * @return \FreeFW\Behaviour\RequestAwareTrait
     */
    public function setRequest(\Psr\Http\Message\ServerRequestInterface $p_request)
    {
        $this->request = $p_request;
        return $this;
    }
}
