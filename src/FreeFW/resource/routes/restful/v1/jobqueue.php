<?php
$jobqueueRoutes = [
    /**
     * ########################################################################
     * Jobqueues
     * ########################################################################
     */
    'freefw.jobqueue.getall' => [
        'method'     => \FreeFW\Router\Route::METHOD_GET,
        'model'      => 'FreeFW::Model::Jobqueue',
        'url'        => '/v1/sys/jobqueue',
        'controller' => 'FreeFW::Controller::Jobqueue',
        'function'   => 'getAll',
        'auth'       => \FreeFW\Router\Route::AUTH_IN,
        'middleware' => [],
        'results' => [
            '200' => [
                'type'  => \FreeFW\Router\Route::RESULT_LIST,
                'model' => 'FreeFW::Model::Jobqueue'
            ]
        ]
    ],
    'freefw.jobqueue.getone' => [
        'method'     => \FreeFW\Router\Route::METHOD_GET,
        'model'      => 'FreeFW::Model::Jobqueue',
        'url'        => '/v1/sys/jobqueue/:jobq_id',
        'controller' => 'FreeFW::Controller::Jobqueue',
        'function'   => 'getOne',
        'auth'       => \FreeFW\Router\Route::AUTH_IN,
        'middleware' => [],
        'include'    => [
            'default' => ['user', 'group']
        ],
        'results' => [
            '200' => [
                'type'  => \FreeFW\Router\Route::RESULT_OBJECT,
                'model' => 'FreeFW::Model::Jobqueue'
            ]
        ]
    ],
    'freefw.jobqueue.createone' => [
        'method'     => \FreeFW\Router\Route::METHOD_POST,
        'model'      => 'FreeFW::Model::Jobqueue',
        'url'        => '/v1/sys/jobqueue',
        'controller' => 'FreeFW::Controller::Jobqueue',
        'function'   => 'createOne',
        'auth'       => \FreeFW\Router\Route::AUTH_IN,
        'middleware' => [],
        'include'    => [
            'default' => ['user', 'group']
        ],
        'results' => [
            '201' => [
                'type'  => \FreeFW\Router\Route::RESULT_OBJECT,
                'model' => 'FreeFW::Model::Jobqueue'
            ]
        ]
    ],
    'freefw.jobqueue.updateone' => [
        'method'     => \FreeFW\Router\Route::METHOD_PUT,
        'model'      => 'FreeFW::Model::Jobqueue',
        'url'        => '/v1/sys/jobqueue/:jobq_id',
        'controller' => 'FreeFW::Controller::Jobqueue',
        'function'   => 'updateOne',
        'auth'       => \FreeFW\Router\Route::AUTH_IN,
        'middleware' => ['user', 'group'],
        'results' => [
            '200' => [
                'type'  => \FreeFW\Router\Route::RESULT_OBJECT,
                'model' => 'FreeFW::Model::Jobqueue'
            ]
        ]
    ],
    'freefw.jobqueue.deleteone' => [
        'method'     => \FreeFW\Router\Route::METHOD_DELETE,
        'model'      => 'FreeFW::Model::Jobqueue',
        'url'        => '/v1/sys/jobqueue/:jobq_id',
        'controller' => 'FreeFW::Controller::Jobqueue',
        'function'   => 'removeOne',
        'auth'       => \FreeFW\Router\Route::AUTH_IN,
        'middleware' => [],
        'results' => [
            '204' => [
            ]
        ]
    ],
];
