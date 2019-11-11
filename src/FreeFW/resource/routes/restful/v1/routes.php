<?php
$localRoutes = [
    /**
     * Création d'un modèle
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
return $localRoutes;
