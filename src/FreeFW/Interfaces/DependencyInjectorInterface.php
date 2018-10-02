<?php
namespace FreeFW\Interfaces;

/**
 * DependencyInjector interface
 *
 * @author jeromeklam
 */
interface DependencyInjectorInterface
{

    /**
     * Get controller
     *
     * @param string $p_name
     *
     * @return \FreeFW\Core\Controller
     */
    public function getController($p_name);

    /**
     * Get service
     *
     * @param string $p_name
     *
     * @return \FreeFW\Core\Service
     */
    public function getService($p_name);

    /**
     * Get model
     *
     * @param string $p_name
     *
     * @return \FreeFW\Core\Model
     */
    public function getModel($p_name);

    /**
     * Get manager
     *
     * @param string $p_name
     *
     * @return \FreeFW\Core\Manager
     */
    public function getManager($p_name);

    /**
     * Get middleware
     *
     * @param string $p_name
     *
     * @return \Psr\Http\Server\MiddlewareInterface
     */
    public function getMiddleware($p_name);
}
