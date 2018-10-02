<?php
namespace FreeFW\Router;

/**
 * Route collection
 *
 * @author jeromeklam
 */
class RouteCollection
{

    /**
     * Routes
     * @var array
     */
    protected $routes = [];

    /**
     * Flush all routes
     *
     * @return \FreeFW\Router\RouteCollection
     */
    public function flush()
    {
        $this->routes = [];
        return $this;
    }

    /**
     * Get all routes
     *
     * @return array
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * Add one route
     *
     * @param \FreeFW\Router\Route $p_route
     *
     * @return \FreeFW\Router\RouteCollection
     */
    public function addRoute(\FreeFW\Router\Route $p_route)
    {
        $this->routes[] = $p_route;
        return $this;
    }

    /**
     * Add routes
     *
     * @param \FreeFW\Router\RouteCollection $p_collection
     *
     * @return \FreeFW\Router\RouteCollection
     */
    public function addRoutes(\FreeFW\Router\RouteCollection $p_collection)
    {
        $this->routes = array_merge($this->routes, $p_collection->getRoutes());
        return $this;
    }
}
