<?php
return [
    'name'        => 'SSO',
    'description' => 'API de gestion de la partie Single sign-on',
    'base'        => 'sso',
    'versions'    => [
        'v1' => [
            'path' => 'V1/micro.php',
            'date' => '2017-07-31'
        ]
    ],
    'packages' => [
        'FreeFW_Sso_Broker'   => [
            'name'        => 'broker',
            'description' => 'Gestion des brokers',
            'model'       => 'Broker'
        ],
        'FreeFW_Sso_Group'   => [
            'name'        => 'group',
            'description' => 'Gestion des groupes/clients',
            'model'       => 'Group'
        ],
        'FreeFW_Sso_User'    => [
            'name'        => 'user',
            'description' => 'Gestion des utilisateurs',
            'model'       => 'User'
        ],
        'FreeFW_Sso_UserAccount'    => [
            'name'        => 'user_account',
            'description' => 'Gestion des comptes des utilisateurs',
            'model'       => 'UserAccount'
        ],
        'FreeFW_Sso_UserQuota'    => [
            'name'        => 'user_quota',
            'description' => 'Gestion des quotas des utilisateurs',
            'model'       => 'UserQuota'
        ],
        'FreeFW_Sso_Auth' => [
            'name'        => 'sso',
            'description' => 'Gestion de l\'authentification'
        ]
    ]
];
