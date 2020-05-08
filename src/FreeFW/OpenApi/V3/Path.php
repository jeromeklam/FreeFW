<?php
namespace FreeFW\OpenApi\V3;

/**
 * OpenApi v3 Path object
 *
 * @author jeromeklam
 */
class Path extends \FreeFW\OpenApi\V3\Base
{

    /**
     * Summary
     * @var string
     */
    protected $summary = null;

    /**
     * Description
     * @var string
     */
    protected $description = null;

    /**
     * Get operation
     * @var \FreeFW\OpenApi\V3\Operation
     */
    protected $get = null;

    /**
     * Put operation
     * @var \FreeFW\OpenApi\V3\Operation
     */
    protected $put = null;

    /**
     * Delete operation
     * @var \FreeFW\OpenApi\V3\Operation
     */
    protected $delete = null;

    /**
     * Post operation
     * @var \FreeFW\OpenApi\V3\Operation
     */
    protected $post = null;

    /**
     * Parameters
     * @var [\FreeFW\OpenApi\V3\Parameter]
     */
    protected $parameters = null;

    /**
     * Add one operation
     *
     * @param string                       $p_method
     * @param \FreeFW\OpenApi\V3\Operation $p_operation
     *
     * @return \FreeFW\OpenApi\V3\Path
     */
    public function addOperation($p_method, $p_operation)
    {
        switch (strtolower($p_method)) {
            case 'get':
                $this->get = $p_operation;
                break;
            case 'put':
                $this->put = $p_operation;
                break;
            case 'post':
                $this->post = $p_operation;
                break;
            case 'delete':
                $this->delete = $p_operation;
                break;
        }
        return $this;
    }
}
