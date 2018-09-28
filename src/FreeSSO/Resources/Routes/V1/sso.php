<?php
$sso_sso_routes = array(
    /**
     * SignUp
     */
    'sso:sign-up'   => array(
        'package'    => 'FreeFW_Sso_Auth',
        'url'        => '/v1/sso/signup',
        'version'    => 'v1',
        'controller' => '\FreeSSO\Controller\Micro\V1\Sso.signUp',
        'methods'    => \FreeFW\Router\Route::METHOD_POST,
        'type'       => \FreeFW\Router\Route::TYPE_API,
        'auth'       => \FreeFW\Router\Route::AUTH_AUTO,
        'role'       => \FreeFW\Router\Route::ROLE_OTHER,
        'secured'    => false,
        'help'       => array(
            'title'  => 'Inscription d\'un utilisateur',
            'params' => array(
                'login' => array(
                    'description' => 'Le login de l\'utilisateur',
                    'type'        => \FreeFW\Router\Param::TYPE_STRING,
                    'from'        => \FreeFW\Router\Param::FROM_BODY,
                    'required'    => true
                ),
                'password' => array(
                    'description' => 'Le mot de passe de l\'utilisateur',
                    'type'        => \FreeFW\Router\Param::TYPE_STRING,
                    'from'        => \FreeFW\Router\Param::FROM_BODY,
                    'required'    => true
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
     * ValidateAccount
     */
    'sso:validate-account'   => array(
        'package'    => 'FreeFW_Sso_Auth',
        'url'        => '/v1/sso/validate-account',
        'version'    => 'v1',
        'controller' => '\FreeSSO\Controller\Micro\V1\Sso.validateAccount',
        'methods'    => \FreeFW\Router\Route::METHOD_POST,
        'type'       => \FreeFW\Router\Route::TYPE_API,
        'auth'       => \FreeFW\Router\Route::AUTH_AUTO,
        'role'       => \FreeFW\Router\Route::ROLE_OTHER,
        'secured'    => false,
        'help'       => array(
            'title'  => 'Validation d\'un compte',
            'params' => array(
                'token' => array(
                    'description' => 'Le token de validation',
                    'type'        => \FreeFW\Router\Param::TYPE_STRING,
                    'from'        => \FreeFW\Router\Param::FROM_BODY,
                    'required'    => true
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
     * SignIn
     */
    'sso:sign-in'   => array(
        'package'    => 'FreeFW_Sso_Auth',
        'url'        => '/v1/sso/signin',
        'version'    => 'v1',
        'controller' => '\FreeSSO\Controller\Micro\V1\Sso.signIn',
        'methods'    => \FreeFW\Router\Route::METHOD_POST,
        'type'       => \FreeFW\Router\Route::TYPE_API,
        'auth'       => \FreeFW\Router\Route::AUTH_AUTO,
        'role'       => \FreeFW\Router\Route::ROLE_OTHER,
        'secured'    => false,
        'help'       => array(
            'title'  => 'Login d\'un utilisateur',
            'params' => array(
                'login' => array(
                    'description' => 'Le login de l\'utilisateur',
                    'type'        => \FreeFW\Router\Param::TYPE_STRING,
                    'from'        => \FreeFW\Router\Param::FROM_BODY,
                    'required'    => true
                ),
                'password' => array(
                    'description' => 'Le mot de passe de l\'utilisateur',
                    'type'        => \FreeFW\Router\Param::TYPE_STRING,
                    'from'        => \FreeFW\Router\Param::FROM_BODY,
                    'required'    => true
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
     * Logout
     */
    'sso:sign-out'   => array(
        'package'    => 'FreeFW_Sso_Auth',
        'url'        => '/v1/sso/signout',
        'version'    => 'v1',
        'controller' => '\FreeSSO\Controller\Micro\V1\Sso.signOut',
        'methods'    => \FreeFW\Router\Route::METHOD_DELETE,
        'type'       => \FreeFW\Router\Route::TYPE_API,
        'auth'       => \FreeFW\Router\Route::AUTH_AUTO,
        'role'       => \FreeFW\Router\Route::ROLE_DEL,
        'secured'    => true,
        'help'       => array(
            'title'  => 'Logout d\'un utilisateur',
            'results' => array(
                '204' => array()
            )
        )
    ),
    /**
     * Authenticated ??
     */
    'sso:auth'   => array(
        'package'    => 'FreeFW_Sso_Auth',
        'url'        => '/v1/sso/auth',
        'version'    => 'v1',
        'controller' => '\FreeSSO\Controller\Micro\V1\Sso.auth',
        'methods'    => \FreeFW\Router\Route::METHOD_GET,
        'type'       => \FreeFW\Router\Route::TYPE_API,
        'auth'       => \FreeFW\Router\Route::AUTH_AUTO,
        'role'       => \FreeFW\Router\Route::ROLE_OTHER,
        'secured'    => false,
        'help'       => array(
            'title'  => 'Utilisateur authentifié ?',
            'results' => array(
                '200' => array(
                    'object' => 'FreeFW_Sso_User',
                    'type'   => \FreeFW\Router\Result::TYPE_OBJECT
                )
            )
        )
    ),
    /**
     * Request new password ??
     */
    'sso:request-password'   => array(
        'package'    => 'FreeFW_Sso_Auth',
        'url'        => '/v1/sso/request-password',
        'version'    => 'v1',
        'controller' => '\FreeSSO\Controller\Micro\V1\Sso.requestNewPasswordToken',
        'methods'    => \FreeFW\Router\Route::METHOD_POST,
        'type'       => \FreeFW\Router\Route::TYPE_API,
        'auth'       => \FreeFW\Router\Route::AUTH_AUTO,
        'role'       => \FreeFW\Router\Route::ROLE_OTHER,
        'secured'    => false,
        'help'       => array(
            'title'  => 'Requête enregistrée ?',
            'results' => array(
                '201' => array()
            )
        )
    ),
    /**
     * Request new validation email
     */
    'sso:request-validation'   => array(
        'package'    => 'FreeFW_Sso_Auth',
        'url'        => '/v1/sso/request-validation',
        'version'    => 'v1',
        'controller' => '\FreeSSO\Controller\Micro\V1\Sso.requestValidationEmail',
        'methods'    => \FreeFW\Router\Route::METHOD_POST,
        'type'       => \FreeFW\Router\Route::TYPE_API,
        'auth'       => \FreeFW\Router\Route::AUTH_AUTO,
        'role'       => \FreeFW\Router\Route::ROLE_OTHER,
        'secured'    => false,
        'help'       => array(
            'title'  => 'Email de validation ?',
            'results' => array(
                '200' => array()
            )
        )
    ),
    /**
     * Change password ??
     */
    'sso:change-password-token'   => array(
        'package'    => 'FreeFW_Sso_Auth',
        'url'        => '/v1/sso/change-password-token',
        'version'    => 'v1',
        'controller' => '\FreeSSO\Controller\Micro\V1\Sso.changePasswordWithToken',
        'methods'    => \FreeFW\Router\Route::METHOD_PUT,
        'type'       => \FreeFW\Router\Route::TYPE_API,
        'auth'       => \FreeFW\Router\Route::AUTH_AUTO,
        'role'       => \FreeFW\Router\Route::ROLE_OTHER,
        'secured'    => false,
        'help'       => array(
            'title'  => 'Mot de passe modifié',
            'results' => array(
                '200' => array()
            )
        )
    ),
    /**
     * Change password ??
     */
    'sso:change-password'   => array(
        'package'    => 'FreeFW_Sso_Auth',
        'url'        => '/v1/sso/change-password',
        'version'    => 'v1',
        'controller' => '\FreeSSO\Controller\Micro\V1\Sso.changePassword',
        'methods'    => \FreeFW\Router\Route::METHOD_PUT,
        'type'       => \FreeFW\Router\Route::TYPE_API,
        'auth'       => \FreeFW\Router\Route::AUTH_AUTO,
        'role'       => \FreeFW\Router\Route::ROLE_OTHER,
        'secured'    => false,
        'help'       => array(
            'title'  => 'Mot de passe modifié',
            'results' => array(
                '200' => array()
            )
        )
    ),
    /**
     * Remove account
     */
    'sso:remove-account'   => array(
        'package'    => 'FreeFW_Sso_Auth',
        'url'        => '/v1/sso/remove-account',
        'version'    => 'v1',
        'controller' => '\FreeSSO\Controller\Micro\V1\Sso.remove',
        'methods'    => \FreeFW\Router\Route::METHOD_DELETE,
        'type'       => \FreeFW\Router\Route::TYPE_API,
        'auth'       => \FreeFW\Router\Route::AUTH_AUTO,
        'role'       => \FreeFW\Router\Route::ROLE_OTHER,
        'secured'    => true,
        'help'       => array(
            'title'  => 'Suppression du compte',
            'results' => array(
                '204' => array()
            )
        )
    )
);
