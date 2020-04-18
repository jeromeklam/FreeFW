<?php
$notificationRoutes = [
    /**
     * ########################################################################
     * Notifications
     * ########################################################################
     */
    'freefw.notification.getall' => [
        'method'     => \FreeFW\Router\Route::METHOD_GET,
        'model'      => 'FreeFW::Model::Notification',
        'url'        => '/v1/core/notification',
        'controller' => 'FreeFW::Controller::Notification',
        'function'   => 'getAll',
        'auth'       => \FreeFW\Router\Route::AUTH_IN,
        'middleware' => [],
        'results' => [
            '200' => [
                'type'  => \FreeFW\Router\Route::RESULT_LIST,
                'model' => 'FreeFW::Model::Notification'
            ]
        ]
    ],
    'freefw.notification.getone' => [
        'method'     => \FreeFW\Router\Route::METHOD_GET,
        'model'      => 'FreeFW::Model::Notification',
        'url'        => '/v1/core/notification/:notif_id',
        'controller' => 'FreeFW::Controller::Notification',
        'function'   => 'getOne',
        'auth'       => \FreeFW\Router\Route::AUTH_IN,
        'middleware' => [],
        'include'    => [
            'default' => ['user']
        ],
        'results' => [
            '200' => [
                'type'  => \FreeFW\Router\Route::RESULT_OBJECT,
                'model' => 'FreeFW::Model::Notification'
            ]
        ]
    ],
    'freefw.notification.createone' => [
        'method'     => \FreeFW\Router\Route::METHOD_POST,
        'model'      => 'FreeFW::Model::Notification',
        'url'        => '/v1/core/notification',
        'controller' => 'FreeFW::Controller::Notification',
        'function'   => 'createOne',
        'auth'       => \FreeFW\Router\Route::AUTH_IN,
        'middleware' => [],
        'include'    => [
            'default' => ['user']
        ],
        'results' => [
            '201' => [
                'type'  => \FreeFW\Router\Route::RESULT_OBJECT,
                'model' => 'FreeFW::Model::Notification'
            ]
        ]
    ],
    'freefw.notification.updateone' => [
        'method'     => \FreeFW\Router\Route::METHOD_PUT,
        'model'      => 'FreeFW::Model::Notification',
        'url'        => '/v1/core/notification/:notif_id',
        'controller' => 'FreeFW::Controller::Notification',
        'function'   => 'updateOne',
        'auth'       => \FreeFW\Router\Route::AUTH_IN,
        'middleware' => ['user'],
        'results' => [
            '200' => [
                'type'  => \FreeFW\Router\Route::RESULT_OBJECT,
                'model' => 'FreeFW::Model::Notification'
            ]
        ]
    ],
    'freefw.notification.deleteone' => [
        'method'     => \FreeFW\Router\Route::METHOD_DELETE,
        'model'      => 'FreeFW::Model::Notification',
        'url'        => '/v1/core/notification/:notif_id',
        'controller' => 'FreeFW::Controller::Notification',
        'function'   => 'removeOne',
        'auth'       => \FreeFW\Router\Route::AUTH_IN,
        'middleware' => [],
        'results' => [
            '204' => [
            ]
        ]
    ],
];
