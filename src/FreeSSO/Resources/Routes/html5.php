<?php
$routes = array(
    /**
     * routes standard de gestion des groupes
     */
    'group:all'     => array(
        'url'        => '/groups/:page',
        'controller' => '\FreeSSO\Controller\Html5\Group.getAll',
        'methods'    => 'GET',
        'secure'     => 'LOGGED',
        'menus'      => 'auth@groups'
    ),
    'group:add'        => array(
        'url'        => '/group',
        'controller' => '\FreeSSO\Controller\Html5\Group.addOne',
        'secure'     => 'LOGGED',
        'methods'    => 'GET'
    ),
    'group:edit'    => array(
        'url'        => '/group/:grp_id',
        'controller' => '\FreeSSO\Controller\Html5\Group.editOne',
        'secure'     => 'LOGGED',
        'methods'    => 'GET'
    ),
    'group:save' => array(
        'url'        => '/group/:grp_id',
        'controller' => '\FreeSSO\Controller\Html5\Group.saveOne',
        'secure'     => 'LOGGED',
        'methods'    => 'POST,UPDATE'
    ),
    'group:del' => array(
        'url'        => '/group/:grp_id',
        'controller' => '\FreeSSO\Controller\Html5\Group.deleteOne',
        'secure'     => 'LOGGED',
        'methods'    => 'DELETE'
    ),
    'group-user:by-group'    => array(
        'url'        => '/group-users/:grp_id',
        'controller' => '\FreeSSO\Controller\Html5\GroupUser.getAllByGroup',
        'secure'     => 'LOGGED',
        'methods'    => 'GET'
    ),
    'group-user:edit'    => array(
        'url'        => '/group-user/:gru_id',
        'controller' => '\FreeSSO\Controller\Html5\GroupUser.editOne',
        'secure'     => 'LOGGED',
        'methods'    => 'GET'
    ),
    'group-user:save' => array(
        'url'        => '/group-user/:gru_id',
        'controller' => '\FreeSSO\Controller\Html5\GroupUser.saveOne',
        'secure'     => 'LOGGED',
        'methods'    => 'POST,UPDATE'
    ),
    /**
     * routes standard de gestion des domaines
     */
    'domain:all'     => array(
        'url'        => '/domains/:page',
        'controller' => '\FreeSSO\Controller\Html5\Domain.getAll',
        'methods'    => 'GET',
        'secure'     => 'LOGGED',
        'menus'      => 'auth@domains'
    ),
    'domain:add'        => array(
        'url'        => '/domain',
        'controller' => '\FreeSSO\Controller\Html5\Domain.addOne',
        'secure'     => 'LOGGED',
        'methods'    => 'GET'
    ),
    'domain:edit'    => array(
        'url'        => '/domain/:dom_id',
        'controller' => '\FreeSSO\Controller\Html5\Domain.editOne',
        'secure'     => 'LOGGED',
        'methods'    => 'GET'
    ),
    'domain:save' => array(
        'url'        => '/domain/:dom_id',
        'controller' => '\FreeSSO\Controller\Html5\Domain.saveOne',
        'secure'     => 'LOGGED',
        'methods'    => 'POST,UPDATE'
    ),
    'domain:del' => array(
        'url'        => '/domain/:dom_id',
        'controller' => '\FreeSSO\Controller\Html5\Domain.deleteOne',
        'secure'     => 'LOGGED',
        'methods'    => 'DELETE'
    ),
    /**
     * Routes standard de gestion des brokers
     */
    'brokersession:all'     => array(
        'url'        => '/brokersessions/:page',
        'controller' => '\FreeSSO\Controller\Html5\BrokerSession.getAll',
        'methods'    => 'GET',
        'secure'     => 'LOGGED',
        'menus'      => 'auth@sessions'
    ),
    /**
     * Routes standard de gestion des brokers
     */
    'broker:all'     => array(
        'url'        => '/brokers/:page',
        'controller' => '\FreeSSO\Controller\Html5\Broker.getAll',
        'methods'    => 'GET',
        'secure'     => 'LOGGED',
        'menus'      => 'auth@brokers'
    ),
    'broker:add'        => array(
        'url'        => '/broker',
        'controller' => '\FreeSSO\Controller\Html5\Broker.addOne',
        'secure'     => 'LOGGED',
        'methods'    => 'GET'
    ),
    'broker:edit'    => array(
        'url'        => '/broker/:brk_id',
        'controller' => '\FreeSSO\Controller\Html5\Broker.editOne',
        'secure'     => 'LOGGED',
        'methods'    => 'GET'
    ),
    'broker:save' => array(
        'url'        => '/broker/:brk_id',
        'controller' => '\FreeSSO\Controller\Html5\Broker.saveOne',
        'secure'     => 'LOGGED',
        'methods'    => 'POST,UPDATE'
    ),
    'broker:del' => array(
        'url'        => '/broker/:brk_id',
        'controller' => '\FreeSSO\Controller\Html5\Broker.deleteOne',
        'secure'     => 'LOGGED',
        'methods'    => 'DELETE'
    ),
    /**
     * Utilisateurs
     */
    'user:login'    => array(
        'url'        => '/user/login',
        'controller' => '\FreeSSO\Controller\Html5\User.login',
        'methods'    => 'GET'
    ),
    'user:connected' => array(
        'url'        => '/users/connected',
        'controller' => '\FreeSSO\Controller\Html5\User.getAllConnected',
        'methods'    => 'GET'
    ),
    'user:all'      => array(
        'url'        => '/users/:page',
        'controller' => '\FreeSSO\Controller\Html5\User.getAll',
        'methods'    => 'GET',
        'secure'     => 'LOGGED',
        'menus'      => 'auth@users'
    ),
    'user:add'         => array(
        'url'        => '/user',
        'controller' => '\FreeSSO\Controller\Html5\User.addOne',
        'secure'     => 'LOGGED',
        'methods'    => 'GET'
    ),
    'user:edit'      => array(
        'url'        => '/user/:user_id',
        'controller' => '\FreeSSO\Controller\Html5\User.editOne',
        'secure'     => 'LOGGED',
        'methods'    => 'GET'
    ),
    'user:del'         => array(
        'url'        => '/user/:user_id',
        'controller' => '\FreeSSO\Controller\Html5\User.deleteOne',
        'secure'     => 'LOGGED',
        'methods'    => 'DELETE'
    ),
    'user:save'  => array(
        'url'        => '/user/:user_id',
        'controller' => '\FreeSSO\Controller\Html5\User.saveOne',
        'secure'     => 'LOGGED',
        'methods'    => 'POST,UPDATE'
    ),
    /**
     * SSO
     */
    'ssoAuth'     => array(
        'url'        => '/sso/auth',
        'controller' => '\FreeSSO\Controller\Sso.auth',
        'methods'    => 'GET,CMD',
        'response'   => 'JSON'
    ),
    'ssoLogin'    => array(
        'url'        => '/sso/login',
        'controller' => '\FreeSSO\Controller\Sso.login',
        'methods'    => 'GET'
    ),
    'ssoSignin'   => array(
        'url'        => '/sso/signin',
        'controller' => '\FreeSSO\Controller\Sso.signin',
        'methods'    => 'POST'
    ),
    'ssoLogout'   => array(
        'url'        => '/sso/logout',
        'controller' => '\FreeSSO\Controller\Sso.logout',
        'methods'    => 'GET,POST'
    ),
    'ssoRegister' => array(
        'url'        => '/sso/register',
        'controller' => '\FreeSSO\Controller\Sso.register',
        'methods'    => 'GET,POST'
    ),
    'ssoRegisterUser' => array(
        'url'        => '/sso/registerUser',
        'controller' => '\FreeSSO\Controller\Sso.registerUser',
        'methods'    => 'POST'
    ),
    'ssoEditUser' => array(
        'url'        => '/sso/editUser',
        'controller' => '\FreeSSO\Controller\Sso.editUser',
        'methods'    => 'GET',
        'secure'     => 'LOGGED'
    ),
    'ssoEditUserBase' => array(
        'url'        => '/sso/ssoEditUserBase',
        'controller' => '\FreeSSO\Controller\Sso.editUserBase',
        'methods'    => 'POST',
        'secure'     => 'LOGGED'
    ),
    'getSqlLiteDb' => array(
        'url'        => '/sso/db',
        'controller' => '\FreeSSO\Controller\Db.get',
        'methods'    => 'GET'
    )
);
