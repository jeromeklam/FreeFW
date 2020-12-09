<?php
use \FreeFW\Constants as FFCST;
use \FreeFW\Router\Route as FFCSTRT;

/**
 * Routes for Export
 *
 * @author jeromeklam
 */
$routes_export = [
    'free_f_w.export.calc' => [
        FFCSTRT::ROUTE_COLLECTION => 'FreeFW/Core/Export',
        FFCSTRT::ROUTE_COMMENT    => 'Export calc',
        FFCSTRT::ROUTE_METHOD     => FFCSTRT::METHOD_POST,
        FFCSTRT::ROUTE_MODEL      => 'FreeFW::Model::Edition',
        FFCSTRT::ROUTE_URL        => '/v1/core/export/calc',
        FFCSTRT::ROUTE_CONTROLLER => 'FreeFW::Controller::Export',
        FFCSTRT::ROUTE_FUNCTION   => 'calc',
        FFCSTRT::ROUTE_ROLE       => \FreeFW\Router\Route::ROLE_OTHER,
        FFCSTRT::ROUTE_AUTH       => FFCSTRT::AUTH_IN,
        FFCSTRT::ROUTE_MIDDLEWARE => [],
        FFCSTRT::ROUTE_INCLUDE    => [],
        FFCSTRT::ROUTE_SCOPE      => [],
        FFCSTRT::ROUTE_PARAMETERS => [],
        FFCSTRT::ROUTE_RESULTS    => [
            '200' => [
                FFCSTRT::ROUTE_RESULTS_TYPE    => FFCSTRT::RESULT_DATA,
                FFCSTRT::ROUTE_RESULTS_COMMENT => 'Réponse ok',
            ],
        ]
    ],
];
