<?php
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
    ],
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
    ],
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
    ],
    /**
     * ########################################################################
     * Emails
     * ########################################################################
     */
    'freefw.email.getall' => [
        'method'     => \FreeFW\Router\Route::METHOD_GET,
        'model'      => 'FreeFW::Model::Email',
        'url'        => '/v1/core/email',
        'controller' => 'FreeFW::Controller::Email',
        'function'   => 'getAll',
        'auth'       => \FreeFW\Router\Route::AUTH_IN,
        'middleware' => [],
        'results' => [
            '200' => [
                'type'  => \FreeFW\Router\Route::RESULT_LIST,
                'model' => 'FreeFW::Model::Email'
            ]
        ]
    ],
    'freefw.email.getone' => [
        'method'     => \FreeFW\Router\Route::METHOD_GET,
        'model'      => 'FreeFW::Model::Email',
        'url'        => '/v1/core/email/:email_id',
        'controller' => 'FreeFW::Controller::Email',
        'function'   => 'getOne',
        'auth'       => \FreeFW\Router\Route::AUTH_IN,
        'middleware' => [],
        'include'    => [
            'default' => ['lang']
        ],
        'results' => [
            '200' => [
                'type'  => \FreeFW\Router\Route::RESULT_OBJECT,
                'model' => 'FreeFW::Model::Email'
            ]
        ]
    ],
    'freefw.email.createone' => [
        'method'     => \FreeFW\Router\Route::METHOD_POST,
        'model'      => 'FreeFW::Model::Email',
        'url'        => '/v1/core/email',
        'controller' => 'FreeFW::Controller::Email',
        'function'   => 'createOne',
        'auth'       => \FreeFW\Router\Route::AUTH_IN,
        'middleware' => [],
        'results' => [
            '201' => [
                'type'  => \FreeFW\Router\Route::RESULT_OBJECT,
                'model' => 'FreeFW::Model::Email'
            ]
        ]
    ],
    'freefw.email.updateone' => [
        'method'     => \FreeFW\Router\Route::METHOD_PUT,
        'model'      => 'FreeFW::Model::Email',
        'url'        => '/v1/core/email/:email_id',
        'controller' => 'FreeFW::Controller::Email',
        'function'   => 'updateOne',
        'auth'       => \FreeFW\Router\Route::AUTH_IN,
        'middleware' => [],
        'results' => [
            '200' => [
                'type'  => \FreeFW\Router\Route::RESULT_OBJECT,
                'model' => 'FreeFW::Model::Email'
            ]
        ]
    ],
    'freefw.email.deleteone' => [
        'method'     => \FreeFW\Router\Route::METHOD_DELETE,
        'model'      => 'FreeFW::Model::Email',
        'url'        => '/v1/core/email/:email_id',
        'controller' => 'FreeFW::Controller::Email',
        'function'   => 'removeOne',
        'auth'       => \FreeFW\Router\Route::AUTH_IN,
        'middleware' => [],
        'results' => [
            '204' => [
            ]
        ]
    ],
];
return $localRoutes;
