<?php
require_once(__DIR__ . '/country.php');
require_once(__DIR__ . '/email.php');
require_once(__DIR__ . '/jobqueue.php');
require_once(__DIR__ . '/lang.php');
require_once(__DIR__ . '/notification.php');

$localRoutes = [
    /**
     * ########################################################################
     * Création d'un modèle
     * ########################################################################
     */
    'freefw.model.create' => [
        'method'     => \FreeFW\Router\Route::METHOD_POST,
        'url'        => '/v1/dev/model',
        'controller' => 'FreeFW::Controller::Model',
        'function'   => 'createModel',
        'auth'       => \FreeFW\Router\Route::AUTH_NONE,
        'middleware' => []
    ]
];
$localRoutes = array_merge(
    $localRoutes,
    $countryRoutes,
    $emailRoutes,
    $jobqueueRoutes,
    $langRoutes,
    $notificationRoutes
);
return $localRoutes;
