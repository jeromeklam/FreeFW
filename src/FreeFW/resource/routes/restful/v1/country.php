<?php
$countryRoutes = [
    /**
     * ########################################################################
     * Pays
     * ########################################################################
     */
    'freefw.country.getall' => [
        \FreeFW\Router\Route::ROUTE_COLLECTION => 'FreeFW/Core/Country',
        \FreeFW\Router\Route::ROUTE_COMMENT    => 'Retourne une liste paginée et filtrée de pays',
        \FreeFW\Router\Route::ROUTE_METHOD     => \FreeFW\Router\Route::METHOD_GET,
        \FreeFW\Router\Route::ROUTE_MODEL      => 'FreeFW::Model::Country',
        \FreeFW\Router\Route::ROUTE_URL        => '/v1/core/country',
        \FreeFW\Router\Route::ROUTE_CONTROLLER => 'FreeFW::Controller::Country',
        \FreeFW\Router\Route::ROUTE_FUNCTION   => 'getAll',
        \FreeFW\Router\Route::ROUTE_AUTH       => \FreeFW\Router\Route::AUTH_IN,
        \FreeFW\Router\Route::ROUTE_MIDDLEWARE => [],
        \FreeFW\Router\Route::ROUTE_RESULTS    => [
            '200' => [
                \FreeFW\Router\Route::ROUTE_RESULTS_TYPE  => \FreeFW\Router\Route::RESULT_LIST,
                \FreeFW\Router\Route::ROUTE_RESULTS_MODEL => 'FreeFW::Model::Country'
            ]
        ]
    ],
    'freefw.email.getone' => [
        \FreeFW\Router\Route::ROUTE_COLLECTION => 'FreeFW/Core/Country',
        \FreeFW\Router\Route::ROUTE_COMMENT    => 'Retourne un pays',
        \FreeFW\Router\Route::ROUTE_METHOD     => \FreeFW\Router\Route::METHOD_GET,
        \FreeFW\Router\Route::ROUTE_MODEL      => 'FreeFW::Model::Country',
        \FreeFW\Router\Route::ROUTE_URL        => '/v1/core/country/:cnty_id',
        \FreeFW\Router\Route::ROUTE_CONTROLLER => 'FreeFW::Controller::Country',
        \FreeFW\Router\Route::ROUTE_FUNCTION   => 'getOne',
        \FreeFW\Router\Route::ROUTE_AUTH       => \FreeFW\Router\Route::AUTH_IN,
        \FreeFW\Router\Route::ROUTE_MIDDLEWARE => [],
        \FreeFW\Router\Route::ROUTE_RESULTS    => [
            '200' => [
                \FreeFW\Router\Route::ROUTE_RESULTS_TYPE  => \FreeFW\Router\Route::RESULT_OBJECT,
                \FreeFW\Router\Route::ROUTE_RESULTS_MODEL => 'FreeFW::Model::Country'
            ]
        ]
    ],
    'freefw.email.createone' => [
        \FreeFW\Router\Route::ROUTE_COLLECTION => 'FreeFW/Core/Country',
        \FreeFW\Router\Route::ROUTE_COMMENT    => 'Créé un pays',
        \FreeFW\Router\Route::ROUTE_METHOD     => \FreeFW\Router\Route::METHOD_POST,
        \FreeFW\Router\Route::ROUTE_MODEL      => 'FreeFW::Model::Country',
        \FreeFW\Router\Route::ROUTE_URL        => '/v1/core/country',
        \FreeFW\Router\Route::ROUTE_CONTROLLER => 'FreeFW::Controller::Country',
        \FreeFW\Router\Route::ROUTE_FUNCTION   => 'createOne',
        \FreeFW\Router\Route::ROUTE_AUTH       => \FreeFW\Router\Route::AUTH_IN,
        \FreeFW\Router\Route::ROUTE_MIDDLEWARE => [],
        \FreeFW\Router\Route::ROUTE_RESULTS    => [
            '201' => [
                \FreeFW\Router\Route::ROUTE_RESULTS_TYPE  => \FreeFW\Router\Route::RESULT_OBJECT,
                \FreeFW\Router\Route::ROUTE_RESULTS_MODEL => 'FreeFW::Model::Country'
            ]
        ]
    ],
    'freefw.email.updateone' => [
        \FreeFW\Router\Route::ROUTE_COLLECTION => 'FreeFW/Core/Country',
        \FreeFW\Router\Route::ROUTE_COMMENT    => 'Modifie un pays',
        \FreeFW\Router\Route::ROUTE_METHOD     => \FreeFW\Router\Route::METHOD_PUT,
        \FreeFW\Router\Route::ROUTE_MODEL      => 'FreeFW::Model::Country',
        \FreeFW\Router\Route::ROUTE_URL        => '/v1/core/country/:cnty_id',
        \FreeFW\Router\Route::ROUTE_CONTROLLER => 'FreeFW::Controller::Country',
        \FreeFW\Router\Route::ROUTE_FUNCTION   => 'updateOne',
        \FreeFW\Router\Route::ROUTE_AUTH       => \FreeFW\Router\Route::AUTH_IN,
        \FreeFW\Router\Route::ROUTE_MIDDLEWARE => [],
        \FreeFW\Router\Route::ROUTE_RESULTS    => [
            '200' => [
                \FreeFW\Router\Route::ROUTE_RESULTS_TYPE  => \FreeFW\Router\Route::RESULT_OBJECT,
                \FreeFW\Router\Route::ROUTE_RESULTS_MODEL => 'FreeFW::Model::Country'
            ]
        ]
    ],
    'freefw.email.deleteone' => [
        \FreeFW\Router\Route::ROUTE_COLLECTION => 'FreeFW/Core/Country',
        \FreeFW\Router\Route::ROUTE_COMMENT    => 'Supprime un pays',
        \FreeFW\Router\Route::ROUTE_METHOD     => \FreeFW\Router\Route::METHOD_DELETE,
        \FreeFW\Router\Route::ROUTE_MODEL      => 'FreeFW::Model::Country',
        \FreeFW\Router\Route::ROUTE_URL        => '/v1/core/country/:cnty_id',
        \FreeFW\Router\Route::ROUTE_CONTROLLER => 'FreeFW::Controller::Country',
        \FreeFW\Router\Route::ROUTE_FUNCTION   => 'removeOne',
        \FreeFW\Router\Route::ROUTE_AUTH       => \FreeFW\Router\Route::AUTH_IN,
        \FreeFW\Router\Route::ROUTE_MIDDLEWARE => [],
        \FreeFW\Router\Route::ROUTE_RESULTS    => [
            '204' => [
            ]
        ]
    ],
];
