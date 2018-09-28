<?php
$sso_broker_routes = array(
    /**
     * Vérification du broker
     */
    'broker:verify' => array(
        'package'    => 'FreeFW_Sso_Broker',
        'url'        => '/v1/sso/broker/verify',
        'version'    => 'v1',
        'controller' => '\FreeSSO\Controller\Micro\V1\Broker.verify',
        'methods'    => \FreeFW\Router\Route::METHOD_GET,
        'type'       => \FreeFW\Router\Route::TYPE_API,
        'auth'       => \FreeFW\Router\Route::AUTH_AUTO,
        'secured'    => true,
        'help'       => array(
            'title'  => 'Vérifie le broker',
            'results' => array(
                '200' => array(
                    'object' => 'FreeFW_Sso_User',
                    'type'   => \FreeFW\Router\Result::TYPE_OBJECT
                )
            )
        )
    ),
    /**
     * Utilisateurs du broker
     */
    'broker:get-all-users' => array(
        'package'    => 'FreeFW_Sso_Broker',
        'url'        => '/v1/sso/broker/user',
        'version'    => 'v1',
        'controller' => '\FreeSSO\Controller\Micro\V1\Broker.getUsers',
        'methods'    => \FreeFW\Router\Route::METHOD_GET,
        'type'       => \FreeFW\Router\Route::TYPE_API,
        'auth'       => \FreeFW\Router\Route::AUTH_AUTO,
        'secured'    => true,
        'help'       => array(
            'title'  => 'Retourne la liste des utilisateurs du broker',
            'params'  => array(
                'from' => array(
                    'description' => 'L\'indice de départ',
                    'type'        => \FreeFW\Router\Param::TYPE_NUMBER,
                    'from'        => \FreeFW\Router\Param::FROM_QUERY,
                    'required'    => false,
                    'default'     => 1
                ),
                'len' => array(
                    'description' => 'Le nombre maximum d\'enregistrement à retourner',
                    'type'        => \FreeFW\Router\Param::TYPE_NUMBER,
                    'from'        => \FreeFW\Router\Param::FROM_QUERY,
                    'required'    => false,
                    'default'     => 20
                ),
                'include' => array(
                    'description' => 'Les éléments à inclure, séparés par une ,',
                    'type'        => \FreeFW\Router\Param::TYPE_INCLUDE,
                    'from'        => \FreeFW\Router\Param::FROM_QUERY,
                    'required'    => false,
                    'default'     => 'accounts,smsquota',
                    'comment'     => ''
                ),
                'fields' => array(
                    'description' => 'La liste des champs à retourner, une liste de champs séparés par une , par objet',
                    'type'        => \FreeFW\Router\Param::TYPE_FIELDS,
                    'from'        => \FreeFW\Router\Param::FROM_QUERY,
                    'required'    => false
                ),
                'sort' => array(
                    'description' => 'Le tri',
                    'type'        => \FreeFW\Router\Param::TYPE_SORT,
                    'from'        => \FreeFW\Router\Param::FROM_QUERY,
                    'required'    => false,
                    'default'     => 'contrat_id',
                    'extended'    => \FreeFW\Router\Param::EXTRA_ALL
                )
            ),
            'results' => array(
                '200' => array(
                    'object' => 'FreeFW_Sso_User',
                    'type'   => \FreeFW\Router\Result::TYPE_ARRAY
                )
            )
        )
    ),
    /**
     * Retourne un utilisateur spécifique du broker
     */
    'broker:get-user-by-key' => array(
        'package'    => 'FreeFW_Sso_Broker',
        'url'        => '/v1/sso/broker/user-key/:key',
        'version'    => 'v1',
        'controller' => '\FreeSSO\Controller\Micro\V1\Broker.getUserByKey',
        'methods'    => \FreeFW\Router\Route::METHOD_GET,
        'type'       => \FreeFW\Router\Route::TYPE_API,
        'auth'       => \FreeFW\Router\Route::AUTH_AUTO,
        'secured'    => true,
        'help'       => array(
            'title'  => 'Retourne un utilisateur du broker',
            'params' => array(
                'key' => array(
                    'description' => 'La clef de l\'utilisateur',
                    'type'        => \FreeFW\Router\Param::TYPE_STRING,
                    'from'        => \FreeFW\Router\Param::FROM_URI,
                    'required'    => true
                ),
                'from' => array(
                    'description' => 'L\'indice de départ',
                    'type'        => \FreeFW\Router\Param::TYPE_NUMBER,
                    'from'        => \FreeFW\Router\Param::FROM_QUERY,
                    'required'    => false,
                    'default'     => 1
                ),
                'len' => array(
                    'description' => 'Le nombre maximum d\'enregistrement à retourner',
                    'type'        => \FreeFW\Router\Param::TYPE_NUMBER,
                    'from'        => \FreeFW\Router\Param::FROM_QUERY,
                    'required'    => false,
                    'default'     => 20
                ),
                'include' => array(
                    'description' => 'Les éléments à inclure, séparés par une ,',
                    'type'        => \FreeFW\Router\Param::TYPE_INCLUDE,
                    'from'        => \FreeFW\Router\Param::FROM_QUERY,
                    'required'    => false,
                    'default'     => 'accounts,smsquota',
                    'comment'     => ''
                ),
                'fields' => array(
                    'description' => 'La liste des champs à retourner, une liste de champs séparés par une , par objet',
                    'type'        => \FreeFW\Router\Param::TYPE_FIELDS,
                    'from'        => \FreeFW\Router\Param::FROM_QUERY,
                    'required'    => false
                ),
                'sort' => array(
                    'description' => 'Le tri',
                    'type'        => \FreeFW\Router\Param::TYPE_SORT,
                    'from'        => \FreeFW\Router\Param::FROM_QUERY,
                    'required'    => false,
                    'default'     => 'contrat_id',
                    'extended'    => \FreeFW\Router\Param::EXTRA_ALL
                )
            ),
            'results' => array(
                '200' => array(
                    'object' => 'FreeFW_Sso_User',
                    'type'   => \FreeFW\Router\Result::TYPE_OBJECT
                )
            )
        )
    ),
    /**
     * Retourne un utilisateur spécifique du broker
     */
    'broker:get-user-by-id' => array(
        'package'    => 'FreeFW_Sso_Broker',
        'url'        => '/v1/sso/broker/user/:id',
        'version'    => 'v1',
        'controller' => '\FreeSSO\Controller\Micro\V1\Broker.getUserById',
        'methods'    => \FreeFW\Router\Route::METHOD_GET,
        'type'       => \FreeFW\Router\Route::TYPE_API,
        'auth'       => \FreeFW\Router\Route::AUTH_AUTO,
        'secured'    => true,
        'help'       => array(
            'title'  => 'Retourne un utilisateur du broker',
            'params' => array(
                'id' => array(
                    'description' => 'Id de l\'utilisateur',
                    'type'        => \FreeFW\Router\Param::TYPE_NUMBER,
                    'from'        => \FreeFW\Router\Param::FROM_URI,
                    'required'    => true
                ),
                'from' => array(
                    'description' => 'L\'indice de départ',
                    'type'        => \FreeFW\Router\Param::TYPE_NUMBER,
                    'from'        => \FreeFW\Router\Param::FROM_QUERY,
                    'required'    => false,
                    'default'     => 1
                ),
                'len' => array(
                    'description' => 'Le nombre maximum d\'enregistrement à retourner',
                    'type'        => \FreeFW\Router\Param::TYPE_NUMBER,
                    'from'        => \FreeFW\Router\Param::FROM_QUERY,
                    'required'    => false,
                    'default'     => 20
                ),
                'include' => array(
                    'description' => 'Les éléments à inclure, séparés par une ,',
                    'type'        => \FreeFW\Router\Param::TYPE_INCLUDE,
                    'from'        => \FreeFW\Router\Param::FROM_QUERY,
                    'required'    => false,
                    'default'     => 'accounts,smsquota',
                    'comment'     => ''
                ),
                'fields' => array(
                    'description' => 'La liste des champs à retourner, une liste de champs séparés par une , par objet',
                    'type'        => \FreeFW\Router\Param::TYPE_FIELDS,
                    'from'        => \FreeFW\Router\Param::FROM_QUERY,
                    'required'    => false
                ),
                'sort' => array(
                    'description' => 'Le tri',
                    'type'        => \FreeFW\Router\Param::TYPE_SORT,
                    'from'        => \FreeFW\Router\Param::FROM_QUERY,
                    'required'    => false,
                    'default'     => 'contrat_id',
                    'extended'    => \FreeFW\Router\Param::EXTRA_ALL
                )
            ),
            'results' => array(
                '200' => array(
                    'object' => 'FreeFW_Sso_User',
                    'type'   => \FreeFW\Router\Result::TYPE_OBJECT
                )
            )
        )
    ),
    /**
     * Créé un utilisateur spécifique du broker
     */
    'broker:create-user' => array(
        'package'    => 'FreeFW_Sso_Broker',
        'url'        => '/v1/sso/broker/user',
        'version'    => 'v1',
        'controller' => '\FreeSSO\Controller\Micro\V1\Broker.createUser',
        'methods'    => \FreeFW\Router\Route::METHOD_POST,
        'type'       => \FreeFW\Router\Route::TYPE_API,
        'auth'       => \FreeFW\Router\Route::AUTH_AUTO,
        'secured'    => true,
        'help'       => array(
            'title'  => 'Créé un utilisateur du broker',
            'params' => array(
            ),
            'results' => array(
                '201' => array(
                    'object' => 'FreeFW_Sso_User',
                    'type'   => \FreeFW\Router\Result::TYPE_OBJECT
                )
            )
        )
    ),
    /**
     * Modifie un utilisateur spécifique du broker
     */
    'broker:update-user' => array(
        'package'    => 'FreeFW_Sso_Broker',
        'url'        => '/v1/sso/broker/user/:id',
        'version'    => 'v1',
        'controller' => '\FreeSSO\Controller\Micro\V1\Broker.updateUser',
        'methods'    => \FreeFW\Router\Route::METHOD_PUT,
        'type'       => \FreeFW\Router\Route::TYPE_API,
        'auth'       => \FreeFW\Router\Route::AUTH_AUTO,
        'secured'    => true,
        'help'       => array(
            'title'  => 'Modifie un utilisateur du broker',
            'params' => array(
                'id' => array(
                    'description' => 'Id de l\'utilisateur',
                    'type'        => \FreeFW\Router\Param::TYPE_NUMBER,
                    'from'        => \FreeFW\Router\Param::FROM_URI,
                    'required'    => true
                )
            ),
            'results' => array(
                '200' => array(
                    'object' => 'FreeFW_Sso_User',
                    'type'   => \FreeFW\Router\Result::TYPE_OBJECT
                )
            )
        )
    ),
    /**
     * Supprime un utilisateur spécifique du broker
     */
    'broker:delete-user' => array(
        'package'    => 'FreeFW_Sso_Broker',
        'url'        => '/v1/sso/broker/user/:id',
        'version'    => 'v1',
        'controller' => '\FreeSSO\Controller\Micro\V1\Broker.deleteUser',
        'methods'    => \FreeFW\Router\Route::METHOD_DELETE,
        'type'       => \FreeFW\Router\Route::TYPE_API,
        'auth'       => \FreeFW\Router\Route::AUTH_AUTO,
        'secured'    => true,
        'help'       => array(
            'title'  => 'Supprime un utilisateur du broker',
            'params' => array(
                'id' => array(
                    'description' => 'Id de l\'utilisateur',
                    'type'        => \FreeFW\Router\Param::TYPE_NUMBER,
                    'from'        => \FreeFW\Router\Param::FROM_URI,
                    'required'    => true
                )
            ),
            'results' => array(
                '204' => array(
                )
            )
        )
    ),
    /**
     * Envoi un SMS pour un utilisateur spécifique du broker
     */
    'broker:sms-user' => array(
        'package'    => 'FreeFW_Sso_Broker',
        'url'        => '/v1/sso/broker/user-sms/:id',
        'version'    => 'v1',
        'controller' => '\FreeSSO\Controller\Micro\V1\Broker.sendSmsForUserId',
        'methods'    => \FreeFW\Router\Route::METHOD_POST,
        'type'       => \FreeFW\Router\Route::TYPE_API,
        'auth'       => \FreeFW\Router\Route::AUTH_AUTO,
        'secured'    => true,
        'help'       => array(
            'title'  => 'Envoi un SMS pour un utilisateur du broker',
            'params' => array(
                'id' => array(
                    'description' => 'Id de l\'utilisateur',
                    'type'        => \FreeFW\Router\Param::TYPE_NUMBER,
                    'from'        => \FreeFW\Router\Param::FROM_URI,
                    'required'    => true
                )
            ),
            'results' => array(
                '200' => array(
                    'object' => 'FreeFW_Sms_Message',
                    'type'   => \FreeFW\Router\Result::TYPE_OBJECT
                )
            )
        )
    )
);
