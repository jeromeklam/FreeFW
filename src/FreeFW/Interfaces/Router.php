<?php
/**
 * Interface d'un router
 *
 * @author jeromeklam
 * @package Routing
 * @category Interface
 */
namespace FreeFW\Interfaces;

/**
 * Interface Router
 * @author jeromeklam
 */
interface Router
{

    /**
     * Reprise des commandes depuis un fichier de config
     * array['commands'] = array(
     *     'test' => array('test', '\Yzynet\Controller\Client'
     *     ),
     *     ...
     * );
     *
     * @param array $config
     *
     * @return \FreeFW\Router\RouterInterface
     */
    public static function getInstance(array $config);

    /**
     * Préparation de la recherche de la route
     *
     * @return mixed (boolean | \FreeFW\Router\Route)
     */
    public function matchCurrentRequest();

    /**
     * Ajout d'un module
     *
     * @param array $p_config
     *
     * @return \FreeFW\Http\Router
     */
    public function addModule($p_config);

    /**
     * Retourne une route en fonction de son nom
     *
     * @param string $p_name
     *
     * @return \FreeFW\Router\Route|false
     */
    public function getRouteByName($p_name);
}
