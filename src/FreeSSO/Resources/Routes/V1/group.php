<?php
$sso_group_routes = array(
    /**
     * Groupes
     */
    'group:get-all' => array(
        'package'    => 'FreeFW_Sso_Group',
        'url'        => '/v1/sso/group',
        'version'    => 'v1',
        'controller' => '\FreeSSO\Controller\Micro\V1\Group.getAll',
        'methods'    => \FreeFW\Router\Route::METHOD_GET,
        'type'       => \FreeFW\Router\Route::TYPE_API,
        'auth'       => \FreeFW\Router\Route::AUTH_AUTO,
        'role'       => \FreeFW\Router\Route::ROLE_LIST,
        'secured'    => true,
        'help'       => array(
            'title'   => 'Retourne la liste des groupes',
            'results' => array(
                '200' => array(
                    'object' => 'FreeFW_Sso_Group',
                    'type'   => \FreeFW\Router\Result::TYPE_ARRAY
                )
            )
        )
    ),
    /**
     * Utilisateurs du groupe selon sa clef de vérification
     */
    'group:get-all-users' => array(
        'package'    => 'FreeFW_Sso_User',
        'url'        => '/v1/sso/group/verif/:grp_verif_key/users',
        'version'    => 'v1',
        'controller' => '\FreeSSO\Controller\Micro\V1\group.getUsersByGroupVerifKey',
        'methods'    => \FreeFW\Router\Route::METHOD_GET,
        'type'       => \FreeFW\Router\Route::TYPE_API,
        'auth'       => \FreeFW\Router\Route::AUTH_AUTO,
        'secured'    => true,
        'help'       => array(
            'title'  => 'Retourne la liste des utilisateurs d\'un groupe avec sa clef de vérification',
            'params' => array(
                'grp_verif_key' => array(
                    'description' => 'La clef de vérification du groupe',
                    'type'        => \FreeFW\Router\Param::TYPE_STRING,
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
     * Retourne le groupe selon son identifiant
     */
    'group:get-by-id' => array(
        'package'    => 'FreeFW_Sso_Group',
        'url'        => '/v1/sso/group/:grp_id',
        'version'    => 'v1',
        'controller' => '\FreeSSO\Controller\Micro\V1\Group.getById',
        'methods'    => \FreeFW\Router\Route::METHOD_GET,
        'type'       => \FreeFW\Router\Route::TYPE_API,
        'auth'       => \FreeFW\Router\Route::AUTH_AUTO,
        'role'       => \FreeFW\Router\Route::ROLE_GET,
        'secured'    => true,
        'help'       => array(
            'title'   => 'Retourne un groupe selon son identifiant',
            'params'  => array(
                'grp_id' => array(
                    'description' => 'L\'identifiant du groupe',
                    'type'        => \FreeFW\Router\Param::TYPE_NUMBER,
                    'from'        => \FreeFW\Router\Param::FROM_URI,
                    'required'    => true
                )
            ),
            'results' => array(
                '200' => array(
                    'object' => 'FreeFW_Sso_Group',
                    'type'   => \FreeFW\Router\Result::TYPE_OBJECT
                )
            )
        )
    ),
    /**
     * Retourne le groupe selon sa clef interne
     */
    'group:get-by-key' => array(
        'package'    => 'FreeFW_Sso_Group',
        'url'        => '/v1/sso/group/key/:grp_key',
        'version'    => 'v1',
        'controller' => '\FreeSSO\Controller\Micro\V1\Group.getByKey',
        'methods'    => \FreeFW\Router\Route::METHOD_GET,
        'type'       => \FreeFW\Router\Route::TYPE_API,
        'auth'       => \FreeFW\Router\Route::AUTH_AUTO,
        'secured'    => true,
        'help'       => array(
            'title'   => 'Retourne un groupe selon sa clef Omega-WEB',
            'params'  => array(
                'grp_key' => array(
                    'description' => 'La clef omega-web',
                    'type'        => \FreeFW\Router\Param::TYPE_STRING,
                    'from'        => \FreeFW\Router\Param::FROM_URI,
                    'required'    => true
                )
            ),
            'results' => array(
                '200' => array(
                    'object' => 'FreeFW_Sso_Group',
                    'type'   => \FreeFW\Router\Result::TYPE_OBJECT
                )
            )
        )
    ),
    /**
     * Retourne le groupe selon sa clef de vérification
     */
    'group:get-by-verif-key' => array(
        'package'    => 'FreeFW_Sso_Group',
        'url'        => '/v1/sso/group/verif/:verif_key',
        'version'    => 'v1',
        'controller' => '\FreeSSO\Controller\Micro\V1\Group.getByVerifKey',
        'methods'    => \FreeFW\Router\Route::METHOD_GET,
        'type'       => \FreeFW\Router\Route::TYPE_API,
        'auth'       => \FreeFW\Router\Route::AUTH_AUTO,
        'secured'    => true,
        'help'       => array(
            'title'   => 'Retourne un groupe selon sa clef de vérification',
            'params'  => array(
                'verif_key' => array(
                    'description' => 'La clef de vérification',
                    'type'        => \FreeFW\Router\Param::TYPE_STRING,
                    'from'        => \FreeFW\Router\Param::FROM_URI,
                    'required'    => true
                )
            ),
            'results' => array(
                '200' => array(
                    'object' => 'FreeFW_Sso_Group',
                    'type'   => \FreeFW\Router\Result::TYPE_OBJECT
                )
            )
        )
    ),
    /**
     * Ajout d'un utilisateur sur le groupe
     * On utilisera la clef de vérification du groupe
     * On passera également la clef de l'utilisateur
     */
    'group:add-user-by-key' => array(
        'package'    => 'FreeFW_Sso_Group',
        'url'        => '/v1/sso/group/verif/:grp_verif_key/user/:usr_key',
        'version'    => 'v1',
        'controller' => '\FreeSSO\Controller\Micro\V1\Group.addUserByKey',
        'methods'    => \FreeFW\Router\Route::METHOD_POST,
        'type'       => \FreeFW\Router\Route::TYPE_API,
        'auth'       => \FreeFW\Router\Route::AUTH_AUTO,
        'secured'    => true,
        'help'       => array(
            'title'   => 'Ajoute un utilisateur pour un groupe avec les clefs',
            'params'  => array(
                'grp_verif_key' => array(
                    'description' => 'La clef de vérification',
                    'type'        => \FreeFW\Router\Param::TYPE_STRING,
                    'from'        => \FreeFW\Router\Param::FROM_URI,
                    'required'    => true
                ),
                'usr_key' => array(
                    'description' => 'La clef de l\'utilisateur',
                    'type'        => \FreeFW\Router\Param::TYPE_STRING,
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
     * Retourne un utilisateur d'un groupe selon la clef de vérification du groupe et la clef de l'utilisateur
     */
    'group:get-user-by-key' => array(
        'package'    => 'FreeFW_Sso_Group',
        'url'        => '/v1/sso/group/verif/:grp_verif_key/user/:usr_key',
        'version'    => 'v1',
        'controller' => '\FreeSSO\Controller\Micro\V1\Group.getUserByKey',
        'methods'    => \FreeFW\Router\Route::METHOD_GET,
        'type'       => \FreeFW\Router\Route::TYPE_API,
        'auth'       => \FreeFW\Router\Route::AUTH_AUTO,
        'secured'    => true,
        'help'       => array(
            'title'   => 'Retourne un utilisateur d\'un groupe avec les clefs',
            'params'  => array(
                'with_quota' => array(
                    'description' => 'Infos de quota SMS',
                    'type'        => \FreeFW\Router\Param::TYPE_BOOLEAN,
                    'from'        => \FreeFW\Router\Param::FROM_QUERY,
                    'default'     => 0,
                    'required'    => false
                ),
                'grp_verif_key' => array(
                    'description' => 'La clef de vérification',
                    'type'        => \FreeFW\Router\Param::TYPE_STRING,
                    'from'        => \FreeFW\Router\Param::FROM_URI,
                    'required'    => true
                ),
                'usr_key' => array(
                    'description' => 'La clef de l\'utilisateur',
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
     * Modification d'un utilisateur d'un groupe
     * Utilisation de la clef de vérification du groupe et de la clef de l'utilisateur
     */
    'group:update-user-by-key' => array(
        'package'    => 'FreeFW_Sso_Group',
        'url'        => '/v1/sso/group/verif/:grp_verif_key/user/:usr_key',
        'version'    => 'v1',
        'controller' => '\FreeSSO\Controller\Micro\V1\Group.updateUserByKey',
        'methods'    => \FreeFW\Router\Route::METHOD_PUT,
        'type'       => \FreeFW\Router\Route::TYPE_API,
        'auth'       => \FreeFW\Router\Route::AUTH_AUTO,
        'secured'    => true,
        'help'       => array(
            'title'   => 'Modifie un utilisateur d\'un groupe avec les clefs',
            'params'  => array(
                'grp_verif_key' => array(
                    'description' => 'La clef de vérification',
                    'type'        => \FreeFW\Router\Param::TYPE_STRING,
                    'from'        => \FreeFW\Router\Param::FROM_URI,
                    'required'    => true
                ),
                'usr_key' => array(
                    'description' => 'La clef de l\'utilisateur',
                    'type'        => \FreeFW\Router\Param::TYPE_STRING,
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
                '200' => array(
                    'object' => 'FreeFW_Sso_User',
                    'type'   => \FreeFW\Router\Result::TYPE_OBJECT
                )
            )
        )
    ),
    /**
     * Suppression d'un utilisateur d'un groupe
     * Utilisation de la clef de vérification du groupe et de la clef de l'utilisateur
     */
    'group:delete-user-by-key' => array(
        'package'    => 'FreeFW_Sso_Group',
        'url'        => '/v1/sso/group/verif/:grp_verif_key/user/:usr_key',
        'version'    => 'v1',
        'controller' => '\FreeSSO\Controller\Micro\V1\Group.deleteUserByKey',
        'methods'    => \FreeFW\Router\Route::METHOD_DELETE,
        'type'       => \FreeFW\Router\Route::TYPE_API,
        'auth'       => \FreeFW\Router\Route::AUTH_AUTO,
        'secured'    => true,
        'help'       => array(
            'title'   => 'Supprime un utilisateur d\'un groupe avec les clefs',
            'params'  => array(
                'grp_verif_key' => array(
                    'description' => 'La clef de vérification',
                    'type'        => \FreeFW\Router\Param::TYPE_STRING,
                    'from'        => \FreeFW\Router\Param::FROM_URI,
                    'required'    => true
                ),
                'usr_key' => array(
                    'description' => 'La clef de l\'utilisateur',
                    'type'        => \FreeFW\Router\Param::TYPE_STRING,
                    'from'        => \FreeFW\Router\Param::FROM_URI,
                    'required'    => true
                )
            ),
            'results' => array(
                '204' => array()
            )
        )
    ),
    /**
     * Envoi d'un SMS pour un utilisateur d'un groupe
     */
    'group:send-sms-user-by-key' => array(
        'package'    => 'FreeFW_Sso_Group',
        'url'        => '/v1/sso/group/verif/:grp_verif_key/user/:usr_key/sms',
        'version'    => 'v1',
        'controller' => '\FreeSSO\Controller\Micro\V1\Group.sendSmsByUserByKey',
        'methods'    => \FreeFW\Router\Route::METHOD_POST,
        'type'       => \FreeFW\Router\Route::TYPE_API,
        'auth'       => \FreeFW\Router\Route::AUTH_AUTO,
        'secured'    => true,
        'help'       => array(
            'title'   => 'Envoi SMS pour un utilisateur d\'un groupe avec les clefs',
            'params'  => array(
                'grp_verif_key' => array(
                    'description' => 'La clef de vérification',
                    'type'        => \FreeFW\Router\Param::TYPE_STRING,
                    'from'        => \FreeFW\Router\Param::FROM_URI,
                    'required'    => true
                ),
                'usr_key' => array(
                    'description' => 'La clef de l\'utilisateur',
                    'type'        => \FreeFW\Router\Param::TYPE_STRING,
                    'from'        => \FreeFW\Router\Param::FROM_URI,
                    'required'    => true
                ),
                'msg_dest' => array(
                    'description' => 'Les destinataires du SMS',
                    'type'        => \FreeFW\Router\Param::TYPE_STRING,
                    'from'        => \FreeFW\Router\Param::FROM_BODY,
                    'required'    => true
                ),
                'msg_text' => array(
                    'description' => 'Le texte du SMS',
                    'type'        => \FreeFW\Router\Param::TYPE_STRING,
                    'from'        => \FreeFW\Router\Param::FROM_BODY,
                    'required'    => true
                )
            ),
            'results' => array(
                '200' => array(
                    'object' => 'FreeFW_Sms_MessageResponse',
                    'type'   => \FreeFW\Router\Result::TYPE_OBJECT
                )
            )
        )
    ),
    /**
     * Ajout d'un groupe
     */
    'group:add' => array(
        'package'    => 'FreeFW_Sso_Group',
        'url'        => '/v1/sso/group',
        'version'    => 'v1',
        'controller' => '\FreeSSO\Controller\Micro\V1\Group.addOne',
        'methods'    => \FreeFW\Router\Route::METHOD_POST,
        'type'       => \FreeFW\Router\Route::TYPE_API,
        'auth'       => \FreeFW\Router\Route::AUTH_AUTO,
        'role'       => \FreeFW\Router\Route::ROLE_ADD,
        'secured'    => true,
        'help'       => array(
            'title'  => 'Création d\'un groupe selon sa clef Omega-Web',
            'params' => array(
                'grp_verif_key' => array(
                    'description' => 'La clef de vérification',
                    'type'        => \FreeFW\Router\Param::TYPE_STRING,
                    'from'        => \FreeFW\Router\Param::FROM_BODY,
                    'required'    => true
                ),
                'grp_name' => array(
                    'description' => 'Le nom du groupe',
                    'type'        => \FreeFW\Router\Param::TYPE_STRING,
                    'from'        => \FreeFW\Router\Param::FROM_BODY,
                    'required'    => false
                )
            ),
            'results' => array(
                '201' => array(
                    'object' => 'FreeFW_Sso_Group',
                    'type'   => \FreeFW\Router\Result::TYPE_OBJECT
                )
            )
        )
    ),
    /**
     * Suppression d'un groupe
     */
    'group:del' => array(
        'package'    => 'FreeFW_Sso_Group',
        'url'        => '/v1/sso/group/:grp_id',
        'version'    => 'v1',
        'controller' => '\FreeSSO\Controller\Micro\V1\Group.delOne',
        'methods'    => \FreeFW\Router\Route::METHOD_DELETE,
        'type'       => \FreeFW\Router\Route::TYPE_API,
        'auth'       => \FreeFW\Router\Route::AUTH_AUTO,
        'role'       => \FreeFW\Router\Route::ROLE_DEL,
        'secured'    => true,
        'help'       => array(
            'title'  => 'Suppression d\'un groupe selon son id',
            'params' => array(
                'grp_id' => array(
                    'description' => 'L\'identifiant du groupe',
                    'type'        => \FreeFW\Router\Param::TYPE_NUMBER,
                    'from'        => \FreeFW\Router\Param::FROM_URI,
                    'required'    => true
                ),
            ),
            'results' => array(
                '204' => array()
            )
        )
    )
);
