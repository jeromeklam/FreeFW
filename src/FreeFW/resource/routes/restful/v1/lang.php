<?php
$langRoutes = [
    /**
     * ########################################################################
     * Langues
     * ########################################################################
     */
    'freefw.lang.getall' => [
        'method'     => \FreeFW\Router\Route::METHOD_GET,
        'model'      => 'FreeFW::Model::Lang',
        'url'        => '/v1/core/lang',
        'controller' => 'FreeFW::Controller::Lang',
        'function'   => 'getAll',
        'auth'       => \FreeFW\Router\Route::AUTH_IN,
        'middleware' => [],
        'results' => [
            '200' => [
                'type'  => \FreeFW\Router\Route::RESULT_LIST,
                'model' => 'FreeFW::Model::Lang'
            ]
        ]
    ]
];
