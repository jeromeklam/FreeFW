<?php
namespace FreeFW\OpenApi\V3;

/**
 * OpenApi v3 Operation object
 *
 * @author jeromeklam
 */
class Operation extends \FreeFW\OpenApi\V3\Base
{

    /**
     * Tags
     * @var string
     */
    protected $tags = null;

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
     * Uniq id
     * @var string
     */
    protected $operation_id = null;

    /**
     * Parameters
     * @var [\FreeFW\OpenApi\V3\Parameter]
     */
    protected $parameters = null;
}
