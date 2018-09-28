<?php
/**
 * Liste des routes
 *
 * @author jeromeklam
 * @package Routing
 * @category Collection
 */
namespace FreeFW\Router;

/**
 * Liste des routes
 * @author jeromeklam
 */
class RouteCollection extends \SplObjectStorage
{

    /**
     * Ajout d'une route
     *
     * @param \FreeFW\Router\Route $attachObject
     */
    public function attachRoute(\FreeFW\Router\Route $attachObject)
    {
        parent::attach($attachObject, null);
    }

    /**
     * Retourne la liste des routes
     *
     * @return array
     */
    public function getAll()
    {
        $temp = array();
        $urls = array();
        foreach ($this as $route) {
            $temp[] = $route;
            $urls[] = $route->getUrl();
        }
        array_multisort($urls, SORT_ASC, SORT_STRING, $temp);

        return $temp;
    }
}
