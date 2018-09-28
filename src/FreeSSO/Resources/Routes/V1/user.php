<?php
$sso_user_routes = array(
    /**
     * Utilisateurs d'un groupe selon son identifiant
     */
    'user:get-by-group-id' => array(
        'package'    => 'FreeFW_Sso_User',
        'url'        => '/v1/sso/group/:grp_id/users',
        'version'    => 'v1',
        'controller' => '\FreeSSO\Controller\Micro\V1\User.getAllByGroup',
        'methods'    => \FreeFW\Router\Route::METHOD_GET,
        'type'       => \FreeFW\Router\Route::TYPE_API,
        'auth'       => \FreeFW\Router\Route::AUTH_AUTO,
        'secured'    => true,
        'help'       => array(
            'title'  => 'Retourne la liste des utilisateurs d\'un groupe',
            'params' => array(
                'grp_id' => array(
                    'description' => 'L\'identifiant du groupe',
                    'examples'    => [1],
                    'type'        => \FreeFW\Router\Param::TYPE_NUMBER,
                    'from'        => \FreeFW\Router\Param::FROM_URI,
                    'required'    => true
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
     * Retourne un utilisateur à partie de son identifiant
     */
    'user:get-by-id' => array(
        'package'    => 'FreeFW_Sso_User',
        'url'        => '/v1/sso/user/:user_id',
        'version'    => 'v1',
        'controller' => '\FreeSSO\Controller\Micro\V1\User.getById',
        'methods'    => \FreeFW\Router\Route::METHOD_GET,
        'type'       => \FreeFW\Router\Route::TYPE_API,
        'auth'       => \FreeFW\Router\Route::AUTH_AUTO,
        'role'       => \FreeFW\Router\Route::ROLE_GET,
        'secured'    => true,
        'help'       => array(
            'title'  => 'Retourne un utilisateur selon son idenfifiant',
            'params' => array(
                'user_id' => array(
                    'description' => 'L\'identifiant de l\'utilisateur',
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
     * Retourne un utilisateur à partie de son login
     */
    'user:get-by-login' => array(
        'package'    => 'FreeFW_Sso_User',
        'url'        => '/v1/sso/user/login/:user_login',
        'version'    => 'v1',
        'controller' => '\FreeSSO\Controller\Micro\V1\User.getByLogin',
        'methods'    => \FreeFW\Router\Route::METHOD_GET,
        'type'       => \FreeFW\Router\Route::TYPE_API,
        'auth'       => \FreeFW\Router\Route::AUTH_AUTO,
        'secured'    => true,
        'help'       => array(
            'title'  => 'Retourne un utilisateur selon son login',
            'params' => array(
                'user_login' => array(
                    'description' => 'Le login de l\'utilisateur',
                    'type'        => \FreeFW\Router\Param::TYPE_STRING,
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
     * Ajout d'un utilisateur dans un groupe
     */
    'user:add-for-group' => array(
        'package'    => 'FreeFW_Sso_User',
        'url'        => '/v1/sso/group/:grp_id/user',
        'version'    => 'v1',
        'controller' => '\FreeSSO\Controller\Micro\V1\User.addOneForGroup',
        'methods'    => \FreeFW\Router\Route::METHOD_POST,
        'type'       => \FreeFW\Router\Route::TYPE_API,
        'auth'       => \FreeFW\Router\Route::AUTH_AUTO,
        'secured'    => true,
        'help'       => array(
            'title'  => 'Demande de création d\'un utilisateur',
            'params' => array(
                'grp_id' => array(
                    'description' => 'L\'identifiant du groupe',
                    'type'        => \FreeFW\Router\Param::TYPE_NUMBER,
                    'from'        => \FreeFW\Router\Param::FROM_URI,
                    'required'    => true
                ),
                'user_login' => array(
                    'description' => 'Le login de l\'utilisateur',
                    'type'        => \FreeFW\Router\Param::TYPE_STRING,
                    'from'        => \FreeFW\Router\Param::FROM_BODY,
                    'required'    => false
                ),
                'user_key' => array(
                    'description' => 'La clef de l\'utilisateur si pas de login précisé',
                    'type'        => \FreeFW\Router\Param::TYPE_STRING,
                    'from'        => \FreeFW\Router\Param::FROM_BODY,
                    'required'    => true
                ),
                'user_last_name' => array(
                    'description' => 'Nom de l\'utilisateur',
                    'type'        => \FreeFW\Router\Param::TYPE_STRING,
                    'from'        => \FreeFW\Router\Param::FROM_BODY,
                    'required'    => false
                ),
                'user_first_name' => array(
                    'description' => 'Prénom de l\'utilisateur',
                    'type'        => \FreeFW\Router\Param::TYPE_STRING,
                    'from'        => \FreeFW\Router\Param::FROM_BODY,
                    'required'    => false
                ),
                'user_active' => array(
                    'description' => 'Utilisateuractif sur le web',
                    'type'        => \FreeFW\Router\Param::TYPE_STRING,
                    'from'        => \FreeFW\Router\Param::FROM_BODY,
                    'default'     => false,
                    'required'    => false
                ),
                'gru_infos' => array(
                    'description' => 'Tableau des comptes pour le groupe',
                    'type'        => 'array',
                    'from'        => \FreeFW\Router\Param::FROM_BODY,
                    'required'    => false
                )
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
     * Modification d'un utilisateur
     */
    'user:mod-for-group' => array(
        'package'    => 'FreeFW_Sso_User',
        'url'        => '/v1/sso/group/:grp_id/user/:user_id',
        'version'    => 'v1',
        'controller' => '\FreeSSO\Controller\Micro\V1\User.updateaOneForGroup',
        'methods'    => \FreeFW\Router\Route::METHOD_PUT,
        'type'       => \FreeFW\Router\Route::TYPE_API,
        'auth'       => \FreeFW\Router\Route::AUTH_AUTO,
        'secured'    => true,
        'help'       => array(
            'title'  => 'Demande de modification d\'un utilisateur',
            'params' => array(
                'grp_id' => array(
                    'description' => 'L\'identifiant du groupe',
                    'type'        => \FreeFW\Router\Param::TYPE_NUMBER,
                    'from'        => \FreeFW\Router\Param::FROM_URI,
                    'required'    => true
                ),
                'user_id' => array(
                    'description' => 'L\'identifiant de l\'utilisateur',
                    'type'        => \FreeFW\Router\Param::TYPE_NUMBER,
                    'from'        => \FreeFW\Router\Param::FROM_URI,
                    'required'    => true
                ),
                'user_login' => array(
                    'description' => 'Le login de l\'utilisateur',
                    'type'        => \FreeFW\Router\Param::TYPE_STRING,
                    'from'        => \FreeFW\Router\Param::FROM_BODY,
                    'required'    => false
                ),
                'user_key' => array(
                    'description' => 'La clef de l\'utilisateur si pas de login précisé',
                    'type'        => \FreeFW\Router\Param::TYPE_STRING,
                    'from'        => \FreeFW\Router\Param::FROM_BODY,
                    'required'    => true
                ),
                'user_last_name' => array(
                    'description' => 'Nom de l\'utilisateur',
                    'type'        => \FreeFW\Router\Param::TYPE_STRING,
                    'from'        => \FreeFW\Router\Param::FROM_BODY,
                    'required'    => false
                ),
                'user_first_name' => array(
                    'description' => 'Prénom de l\'utilisateur',
                    'type'        => \FreeFW\Router\Param::TYPE_STRING,
                    'from'        => \FreeFW\Router\Param::FROM_BODY,
                    'required'    => false
                ),
                'gru_infos' => array(
                    'description' => 'Tableau des comptes pour le groupe',
                    'type'        => 'array',
                    'from'        => \FreeFW\Router\Param::FROM_BODY,
                    'required'    => false
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
     * Modification d'un utilisateur
     */
    'user:update-current' => array(
        'package'    => 'FreeFW_Sso_User',
        'url'        => '/v1/sso/current-user',
        'version'    => 'v1',
        'controller' => '\FreeSSO\Controller\Micro\V1\User.updateCurrent',
        'methods'    => \FreeFW\Router\Route::METHOD_PUT,
        'type'       => \FreeFW\Router\Route::TYPE_API,
        'auth'       => \FreeFW\Router\Route::AUTH_AUTO,
        'role'       => \FreeFW\Router\Route::ROLE_OTHER,
        'secured'    => true,
        'help'       => array(
            'title'  => 'Modification d\'un utilisateur',
            'params' => array(
                'default_dashboard' => array(
                    'description' => 'Les informations du tableau de bord du FrontOffice',
                    'type'        => \FreeFW\Router\Param::TYPE_STRING,
                    'from'        => \FreeFW\Router\Param::FROM_BODY,
                    'required'    => false
                ),
            ),
            'results' => array(
                '200' => array()
            )
        )
    ),
    /**
     * Modification d'un utilisateur
     */
    'user:update' => array(
        'package'    => 'FreeFW_Sso_User',
        'url'        => '/v1/sso/user/:user_id',
        'version'    => 'v1',
        'controller' => '\FreeSSO\Controller\Micro\V1\User.updateById',
        'methods'    => \FreeFW\Router\Route::METHOD_PUT,
        'type'       => \FreeFW\Router\Route::TYPE_API,
        'auth'       => \FreeFW\Router\Route::AUTH_AUTO,
        'role'       => \FreeFW\Router\Route::ROLE_MAJ,
        'secured'    => true,
        'help'       => array(
            'title'  => 'Modification d\'un utilisateur',
            'params' => array(
                'user_id' => array(
                    'description' => 'L\'identifiant de l\'utilisateur',
                    'type'        => \FreeFW\Router\Param::TYPE_NUMBER,
                    'from'        => \FreeFW\Router\Param::FROM_URI,
                    'required'    => true
                )
            ),
            'results' => array(
                '200' => array()
            )
        )
    ),
    /**
     * Suppression d'un utilisateur
     */
    'user:del' => array(
        'package'    => 'FreeFW_Sso_User',
        'url'        => '/v1/sso/user/:user_id',
        'version'    => 'v1',
        'controller' => '\FreeSSO\Controller\Micro\V1\User.delById',
        'methods'    => \FreeFW\Router\Route::METHOD_DELETE,
        'type'       => \FreeFW\Router\Route::TYPE_API,
        'auth'       => \FreeFW\Router\Route::AUTH_AUTO,
        'role'       => \FreeFW\Router\Route::ROLE_DEL,
        'secured'    => true,
        'help'       => array(
            'title'  => 'Suppression d\'un utilisateur',
            'params' => array(
                'user_id' => array(
                    'description' => 'L\'identifiant de l\'utilisateur',
                    'type'        => \FreeFW\Router\Param::TYPE_NUMBER,
                    'from'        => \FreeFW\Router\Param::FROM_URI,
                    'required'    => true
                )
            ),
            'results' => array(
                '204' => array()
            )
        )
    )
);
