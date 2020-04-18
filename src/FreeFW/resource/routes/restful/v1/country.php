<?php
$countryRoutes = [
    /**
     * ########################################################################
     * Pays
     * ########################################################################
     */
    'freefw.country.getall' => [
        'method'     => \FreeFW\Router\Route::METHOD_GET,
        'model'      => 'FreeFW::Model::Country',
        'url'        => '/v1/core/country',
        'controller' => 'FreeFW::Controller::Country',
        'function'   => 'getAll',
        'auth'       => \FreeFW\Router\Route::AUTH_IN,
        'middleware' => [],
        'results' => [
            '200' => [
                'type'  => \FreeFW\Router\Route::RESULT_LIST,
                'model' => 'FreeFW::Model::Country'
            ]
        ]
    ]
];
