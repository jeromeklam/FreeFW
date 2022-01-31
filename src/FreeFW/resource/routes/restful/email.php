<?php
use \FreeFW\Constants as FFCST;
use \FreeFW\Router\Route as FFCSTRT;

/**
 * Routes for Email
 *
 * @author jeromeklam
 */
$routes_email = [
    'free_f_w.email.sendone' => [
        FFCSTRT::ROUTE_COLLECTION => 'FreeFW/Core/Email',
        FFCSTRT::ROUTE_COMMENT    => 'Expédition un objet',
        FFCSTRT::ROUTE_METHOD     => FFCSTRT::METHOD_POST,
        FFCSTRT::ROUTE_MODEL      => 'FreeFW::Model::Email',
        FFCSTRT::ROUTE_URL        => '/v1/core/email/test/:email_id/:lang_code',
        FFCSTRT::ROUTE_CONTROLLER => 'FreeFW::Controller::Email',
        FFCSTRT::ROUTE_FUNCTION   => 'sendOne',
        FFCSTRT::ROUTE_AUTH       => FFCSTRT::AUTH_IN,
        FFCSTRT::ROUTE_MIDDLEWARE => [],
        FFCSTRT::ROUTE_INCLUDE    => [
            FFCSTRT::ROUTE_INCLUDE_DEFAULT => ['lang']
        ],
        FFCSTRT::ROUTE_SCOPE      => [],
        FFCSTRT::ROUTE_PARAMETERS => [
            'email_id' => [
                FFCSTRT::ROUTE_PARAMETER_ORIGIN   => FFCSTRT::ROUTE_PARAMETER_ORIGIN_PATH,
                FFCSTRT::ROUTE_PARAMETER_TYPE     => FFCST::TYPE_BIGINT,
                FFCSTRT::ROUTE_PARAMETER_REQUIRED => true,
                FFCSTRT::ROUTE_PARAMETER_COMMENT  => 'Identifiant de l\'objet'
            ],
            'lang_code' => [
                FFCSTRT::ROUTE_PARAMETER_ORIGIN   => FFCSTRT::ROUTE_PARAMETER_ORIGIN_PATH,
                FFCSTRT::ROUTE_PARAMETER_TYPE     => FFCST::TYPE_STRING,
                FFCSTRT::ROUTE_PARAMETER_REQUIRED => true,
                FFCSTRT::ROUTE_PARAMETER_COMMENT  => 'La langue à utiliser'
            ],
        ],
        FFCSTRT::ROUTE_RESULTS    => [
            '201' => [
                FFCSTRT::ROUTE_RESULTS_TYPE    => FFCSTRT::RESULT_OBJECT,
                FFCSTRT::ROUTE_RESULTS_MODEL   => 'FreeFW::Model::Email',
                FFCSTRT::ROUTE_RESULTS_COMMENT => 'Objet créé',
            ],
        ]
    ],
    'free_f_w.email.autocomplete' => [
        FFCSTRT::ROUTE_COLLECTION => 'FreeFW/Core/Email',
        FFCSTRT::ROUTE_COMMENT    => 'Autocomplete.',
        FFCSTRT::ROUTE_METHOD     => FFCSTRT::METHOD_GET,
        FFCSTRT::ROUTE_MODEL      => 'FreeFW::Model::Email',
        FFCSTRT::ROUTE_URL        => '/v1/core/email/autocomplete/:search',
        FFCSTRT::ROUTE_CONTROLLER => 'FreeFW::Controller::Email',
        FFCSTRT::ROUTE_FUNCTION   => 'autocomplete',
        FFCSTRT::ROUTE_AUTH       => FFCSTRT::AUTH_IN,
        FFCSTRT::ROUTE_MIDDLEWARE => [],
        FFCSTRT::ROUTE_INCLUDE    => [],
        FFCSTRT::ROUTE_SCOPE      => [],
        FFCSTRT::ROUTE_PARAMETERS => [
            'search' => [
                FFCSTRT::ROUTE_PARAMETER_ORIGIN   => FFCSTRT::ROUTE_PARAMETER_ORIGIN_PATH,
                FFCSTRT::ROUTE_PARAMETER_TYPE     => FFCST::TYPE_STRING,
                FFCSTRT::ROUTE_PARAMETER_REQUIRED => true,
                FFCSTRT::ROUTE_PARAMETER_COMMENT  => 'Chaine de recherche'
            ],
        ],
        FFCSTRT::ROUTE_RESULTS    => [
            '200' => [
                FFCSTRT::ROUTE_RESULTS_TYPE    => FFCSTRT::RESULT_LIST,
                FFCSTRT::ROUTE_RESULTS_MODEL   => 'FreeFW::Model::Email',
                FFCSTRT::ROUTE_RESULTS_COMMENT => 'Réponse ok',
            ],
        ]
    ],
    'free_f_w.email.getall' => [
        FFCSTRT::ROUTE_COLLECTION => 'FreeFW/Core/Email',
        FFCSTRT::ROUTE_COMMENT    => 'Retourne une liste filtrée, triée et paginée.',
        FFCSTRT::ROUTE_METHOD     => FFCSTRT::METHOD_GET,
        FFCSTRT::ROUTE_MODEL      => 'FreeFW::Model::Email',
        FFCSTRT::ROUTE_URL        => '/v1/core/email',
        FFCSTRT::ROUTE_CONTROLLER => 'FreeFW::Controller::Email',
        FFCSTRT::ROUTE_FUNCTION   => 'getAll',
        FFCSTRT::ROUTE_AUTH       => FFCSTRT::AUTH_IN,
        FFCSTRT::ROUTE_MIDDLEWARE => [],
        FFCSTRT::ROUTE_INCLUDE    => [
            FFCSTRT::ROUTE_INCLUDE_DEFAULT => ['lang']
        ],
        FFCSTRT::ROUTE_SCOPE      => [],
        FFCSTRT::ROUTE_RESULTS    => [
            '200' => [
                FFCSTRT::ROUTE_RESULTS_TYPE    => FFCSTRT::RESULT_LIST,
                FFCSTRT::ROUTE_RESULTS_MODEL   => 'FreeFW::Model::Email',
                FFCSTRT::ROUTE_RESULTS_COMMENT => 'Réponse ok',
            ],
        ]
    ],
    'free_f_w.email.getone' => [
        FFCSTRT::ROUTE_COLLECTION => 'FreeFW/Core/Email',
        FFCSTRT::ROUTE_COMMENT    => 'Retourne un objet selon son identifiant',
        FFCSTRT::ROUTE_METHOD     => FFCSTRT::METHOD_GET,
        FFCSTRT::ROUTE_MODEL      => 'FreeFW::Model::Email',
        FFCSTRT::ROUTE_URL        => '/v1/core/email/:email_id',
        FFCSTRT::ROUTE_CONTROLLER => 'FreeFW::Controller::Email',
        FFCSTRT::ROUTE_FUNCTION   => 'getOne',
        FFCSTRT::ROUTE_AUTH       => FFCSTRT::AUTH_IN,
        FFCSTRT::ROUTE_MIDDLEWARE => [],
        FFCSTRT::ROUTE_INCLUDE    => [
            FFCSTRT::ROUTE_INCLUDE_DEFAULT => ['lang', 'versions']
        ],
        FFCSTRT::ROUTE_SCOPE      => [],
        FFCSTRT::ROUTE_PARAMETERS => [
            'email_id' => [
                FFCSTRT::ROUTE_PARAMETER_ORIGIN   => FFCSTRT::ROUTE_PARAMETER_ORIGIN_PATH,
                FFCSTRT::ROUTE_PARAMETER_TYPE     => FFCST::TYPE_BIGINT,
                FFCSTRT::ROUTE_PARAMETER_REQUIRED => true,
                FFCSTRT::ROUTE_PARAMETER_COMMENT  => 'Identifiant de l\'objet'
            ],
        ],
        FFCSTRT::ROUTE_RESULTS    => [
            '200' => [
                FFCSTRT::ROUTE_RESULTS_TYPE    => FFCSTRT::RESULT_OBJECT,
                FFCSTRT::ROUTE_RESULTS_MODEL   => 'FreeFW::Model::Email',
                FFCSTRT::ROUTE_RESULTS_COMMENT => 'Réponse ok',
            ],
        ]
    ],
    'free_f_w.email.createone' => [
        FFCSTRT::ROUTE_COLLECTION => 'FreeFW/Core/Email',
        FFCSTRT::ROUTE_COMMENT    => 'Créé un objet',
        FFCSTRT::ROUTE_METHOD     => FFCSTRT::METHOD_POST,
        FFCSTRT::ROUTE_MODEL      => 'FreeFW::Model::Email',
        FFCSTRT::ROUTE_URL        => '/v1/core/email',
        FFCSTRT::ROUTE_CONTROLLER => 'FreeFW::Controller::Email',
        FFCSTRT::ROUTE_FUNCTION   => 'createOne',
        FFCSTRT::ROUTE_AUTH       => FFCSTRT::AUTH_IN,
        FFCSTRT::ROUTE_MIDDLEWARE => [],
        FFCSTRT::ROUTE_INCLUDE    => [
            FFCSTRT::ROUTE_INCLUDE_DEFAULT => ['lang']
        ],
        FFCSTRT::ROUTE_SCOPE      => [],
        FFCSTRT::ROUTE_RESULTS    => [
            '201' => [
                FFCSTRT::ROUTE_RESULTS_TYPE    => FFCSTRT::RESULT_OBJECT,
                FFCSTRT::ROUTE_RESULTS_MODEL   => 'FreeFW::Model::Email',
                FFCSTRT::ROUTE_RESULTS_COMMENT => 'Objet créé',
            ],
        ]
    ],
    'free_f_w.email.updateone' => [
        FFCSTRT::ROUTE_COLLECTION => 'FreeFW/Core/Email',
        FFCSTRT::ROUTE_COMMENT    => 'Modifie un objet',
        FFCSTRT::ROUTE_METHOD     => FFCSTRT::METHOD_PUT,
        FFCSTRT::ROUTE_MODEL      => 'FreeFW::Model::Email',
        FFCSTRT::ROUTE_URL        => '/v1/core/email/:email_id',
        FFCSTRT::ROUTE_CONTROLLER => 'FreeFW::Controller::Email',
        FFCSTRT::ROUTE_FUNCTION   => 'updateOne',
        FFCSTRT::ROUTE_AUTH       => FFCSTRT::AUTH_IN,
        FFCSTRT::ROUTE_MIDDLEWARE => [],
        FFCSTRT::ROUTE_INCLUDE    => [
            FFCSTRT::ROUTE_INCLUDE_DEFAULT => ['lang']
        ],
        FFCSTRT::ROUTE_SCOPE      => [],
        FFCSTRT::ROUTE_PARAMETERS => [
            'email_id' => [
                FFCSTRT::ROUTE_PARAMETER_ORIGIN   => FFCSTRT::ROUTE_PARAMETER_ORIGIN_PATH,
                FFCSTRT::ROUTE_PARAMETER_TYPE     => FFCST::TYPE_BIGINT,
                FFCSTRT::ROUTE_PARAMETER_REQUIRED => true,
                FFCSTRT::ROUTE_PARAMETER_COMMENT  => 'Identifiant de l\'objet'
            ],
        ],
        FFCSTRT::ROUTE_RESULTS    => [
            '200' => [
                FFCSTRT::ROUTE_RESULTS_TYPE    => FFCSTRT::RESULT_OBJECT,
                FFCSTRT::ROUTE_RESULTS_MODEL   => 'FreeFW::Model::Email',
                FFCSTRT::ROUTE_RESULTS_COMMENT => 'Objet modifié',
            ],
        ]
    ],
    'free_f_w.email.removeone' => [
        FFCSTRT::ROUTE_COLLECTION => 'FreeFW/Core/Email',
        FFCSTRT::ROUTE_COMMENT    => 'Supprime un objet',
        FFCSTRT::ROUTE_METHOD     => FFCSTRT::METHOD_DELETE,
        FFCSTRT::ROUTE_MODEL      => 'FreeFW::Model::Email',
        FFCSTRT::ROUTE_URL        => '/v1/core/email/:email_id',
        FFCSTRT::ROUTE_CONTROLLER => 'FreeFW::Controller::Email',
        FFCSTRT::ROUTE_FUNCTION   => 'removeOne',
        FFCSTRT::ROUTE_AUTH       => FFCSTRT::AUTH_IN,
        FFCSTRT::ROUTE_MIDDLEWARE => [],
        FFCSTRT::ROUTE_INCLUDE    => [],
        FFCSTRT::ROUTE_SCOPE      => [],
        FFCSTRT::ROUTE_PARAMETERS => [
            'email_id' => [
                FFCSTRT::ROUTE_PARAMETER_ORIGIN   => FFCSTRT::ROUTE_PARAMETER_ORIGIN_PATH,
                FFCSTRT::ROUTE_PARAMETER_TYPE     => FFCST::TYPE_BIGINT,
                FFCSTRT::ROUTE_PARAMETER_REQUIRED => true,
                FFCSTRT::ROUTE_PARAMETER_COMMENT  => 'Identifiant de l\'objet'
            ],
        ],
        FFCSTRT::ROUTE_RESULTS    => [
            '204' => [
                FFCSTRT::ROUTE_RESULTS_COMMENT => 'Objet supprimé',
            ]
        ]
    ],
];
