<?php
namespace FreeFW\Router;

/**
 * FreeFW routes
 *
 * @author jeromeklam
 */
class FreeFW
{

    /**
     * Retourne une liste de routes au format FreeFW
     *
     * @return \FreeFW\Router\RouteCollection
     */
    public static function getRoutes()
    {
        $routes  = new \FreeFW\Router\RouteCollection();
        $paths   = [];
        $paths[] = __DIR__ . '/../resource/routes/restful/v1/routes.php';
        foreach ($paths as $idx => $onePath) {
            $apiRoutes = @include($onePath);
            if (is_array($apiRoutes)) {
                foreach ($apiRoutes as $idx => $apiRoute) {
                    $myRoute = new \FreeFW\Router\Route();
                    $myRoute
                        ->setMethod($apiRoute['method'])
                        ->setUrl($apiRoute['url'])
                        ->setController($apiRoute['controller'])
                        ->setFunction($apiRoute['function'])
                        ->setSecured($apiRoute['secured'])
                    ;
                    $routes->addRoute($myRoute);
                }
            }
        }
        return $routes;
    }
}
