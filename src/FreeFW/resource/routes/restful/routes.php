<?php
require_once(__DIR__ . '/alert.php');
require_once(__DIR__ . '/alert_category.php');
require_once(__DIR__ . '/automate.php');
require_once(__DIR__ . '/country.php');
require_once(__DIR__ . '/edition_lang.php');
require_once(__DIR__ . '/edition.php');
require_once(__DIR__ . '/email_lang.php');
require_once(__DIR__ . '/email.php');
require_once(__DIR__ . '/export.php');
require_once(__DIR__ . '/help.php');
require_once(__DIR__ . '/inbox.php');
require_once(__DIR__ . '/jobqueue.php');
require_once(__DIR__ . '/history.php');
require_once(__DIR__ . '/lang.php');
require_once(__DIR__ . '/message.php');
require_once(__DIR__ . '/notification.php');
require_once(__DIR__ . '/rate.php');
require_once(__DIR__ . '/taxonomy.php');
require_once(__DIR__ . '/taxonomy_lang.php');
require_once(__DIR__ . '/translation.php');

use \FreeFW\Router\Route as FFCSTRT;

$localRoutes = [
    'free_f_w.app.document' => [
        FFCSTRT::ROUTE_COLLECTION => 'FreeFW/Core/Application',
        FFCSTRT::ROUTE_COMMENT    => 'Génération complète de la documentation de l\'application',
        FFCSTRT::ROUTE_METHOD     => \FreeFW\Router\Route::METHOD_POST,
        FFCSTRT::ROUTE_URL        => '/v1/app/documentation',
        FFCSTRT::ROUTE_CONTROLLER => 'FreeFW::Controller::Application',
        FFCSTRT::ROUTE_FUNCTION   => 'documentAll',
        FFCSTRT::ROUTE_AUTH       => \FreeFW\Router\Route::AUTH_NONE,
        FFCSTRT::ROUTE_MIDDLEWARE => [],
        FFCSTRT::ROUTE_INCLUDE    => [],
        FFCSTRT::ROUTE_SCOPE      => ['ROOT'],
    ],
    'free_f_w.model.generate' => [
        FFCSTRT::ROUTE_COLLECTION => 'FreeFW/Core/Model',
        FFCSTRT::ROUTE_COMMENT    => 'Génération des fichiers d\'un modèle',
        FFCSTRT::ROUTE_METHOD     => \FreeFW\Router\Route::METHOD_POST,
        FFCSTRT::ROUTE_URL        => '/v1/dev/model/generate',
        FFCSTRT::ROUTE_CONTROLLER => 'FreeFW::Controller::Model',
        FFCSTRT::ROUTE_FUNCTION   => 'createModel',
        FFCSTRT::ROUTE_AUTH       => \FreeFW\Router\Route::AUTH_NONE,
        FFCSTRT::ROUTE_MIDDLEWARE => [],
        FFCSTRT::ROUTE_INCLUDE    => [],
        FFCSTRT::ROUTE_SCOPE      => ['ROOT'],
    ],
    'free_f_w.model.document' => [
        FFCSTRT::ROUTE_COLLECTION => 'FreeFW/Core/Model',
        FFCSTRT::ROUTE_COMMENT    => 'Génération de la documentation d\'un modèle',
        FFCSTRT::ROUTE_METHOD     => \FreeFW\Router\Route::METHOD_POST,
        FFCSTRT::ROUTE_URL        => '/v1/dev/model/document',
        FFCSTRT::ROUTE_CONTROLLER => 'FreeFW::Controller::Model',
        FFCSTRT::ROUTE_FUNCTION   => 'documentModel',
        FFCSTRT::ROUTE_AUTH       => \FreeFW\Router\Route::AUTH_NONE,
        FFCSTRT::ROUTE_MIDDLEWARE => [],
        FFCSTRT::ROUTE_INCLUDE    => [],
        FFCSTRT::ROUTE_SCOPE      => ['ROOT'],
    ],
    'free_f_w.model.reactjs' => [
        FFCSTRT::ROUTE_COLLECTION => 'FreeFW/Core/Model',
        FFCSTRT::ROUTE_COMMENT    => 'Génération de la feature ReactJS',
        FFCSTRT::ROUTE_METHOD     => \FreeFW\Router\Route::METHOD_POST,
        FFCSTRT::ROUTE_URL        => '/v1/dev/model/reactjs',
        FFCSTRT::ROUTE_CONTROLLER => 'FreeFW::Controller::Model',
        FFCSTRT::ROUTE_FUNCTION   => 'reactjsModel',
        FFCSTRT::ROUTE_AUTH       => \FreeFW\Router\Route::AUTH_NONE,
        FFCSTRT::ROUTE_MIDDLEWARE => [],
        FFCSTRT::ROUTE_INCLUDE    => [],
        FFCSTRT::ROUTE_SCOPE      => ['ROOT'],
    ],
];
$localRoutes = array_merge(
    $localRoutes,
    $routes_alert,
    $routes_alert_category,
    $routes_automate,
    $routes_country,
    $routes_edition_lang,
    $routes_edition,
    $routes_email_lang,
    $routes_email,
    $routes_export,
    $routes_help,
    $routes_inbox,
    $routes_jobqueue,
    $routes_history,
    $routes_lang,
    $routes_message,
    $routes_notification,
    $routes_rate,
    $routes_taxonomy,
    $routes_taxonomy_lang,
    $routes_translation
);
return $localRoutes;
