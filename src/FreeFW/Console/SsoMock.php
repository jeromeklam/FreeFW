<?php
namespace FreeFW\Console;

/**
 * SSO Server mock class
 * @author jeromeklam
 *
 */
class SsoMock
{

    /**
     * Broker id
     * @var string
     */
    protected $broker_id = null;

    /**
     * 
     * @param string $p_broker_id
     */
    public function __construct($p_broker_id)
    {
        $this->broker_id = $p_broker_id;
    }

    /**
     * Get broker id
     *
     * @return string
     */
    public function getBrokerId()
    {
        return $this->broker_id;
    }
}
