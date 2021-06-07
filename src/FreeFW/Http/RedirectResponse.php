<?php
namespace FreeFW\Http;

/**
 * ServerRequest
 *
 * @author jeromeklam
 */
class RedirectResponse extends \GuzzleHttp\Psr7\Response
{

    /**
     * Model
     * @var string
     */
    protected $model = null;

    /**
     * Role
     * @var string
     */
    protected $role = null;

    /**
     * Params
     * @var array
     */
    protected $params = null;

    /**
     *
     * @param string $p_model
     * @param string $p_role
     * @param array $p_params
     */
    public function __construct(string $p_model, string $p_role, array $p_params = [])
    {
        parent::__construct();
        $this->model  = $p_model;
        $this->role   = $p_role;
        $this->params = $p_params;
    }

    /**
     * Get model
     *
     * @return string
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Get role
     *
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Get params
     *
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }
}
