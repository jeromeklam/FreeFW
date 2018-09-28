<?php
$translations = array(
    'error' => array(
        '10300008' => array(
            'title' => 'Le login est obligatoire !'
        ),
        '10300009' => array(
            'title' => 'Le mot de passe est obligatoire !'
        )
    ),
    'home' => array (
        'option' => array(
            'login' => 'Se connecter'
        )
    ),
    'button' => array(
        'view' => array(
            'details' => 'D&eacute;tails &raquo;'
        )
    ),
    'dyn-menu' => array(
        'auth' => array(
            'title' => 'Authentification',
            'icon'  => 'fa-users'
        ),
        'auth@domains' => array(
            'title' => 'Domaines',
            'icon'  => 'fa-cloud'
        ),
        'auth@groups' => array(
            'title' => 'Groupes',
            'icon'  => 'fa-users'
        ),
        'auth@users' => array(
            'title' => 'Utilisateurs',
            'icon'  => 'fa-user'
        ),
        'auth@sessions' => array(
            'title' => 'Sessions',
            'icon'  => 'fa-rss'
        ),
        'auth@brokers' => array(
            'title' => 'Accès',
            'icon'  => 'fa-key'
        )
    ),
    'menu' => array(
        'options' => array(
            'signin'   => 'Connexion',
            'signout'  => 'Déconnexion',
            'register' => 'Cr&eacute;er un compte'
        )
    ),
    'form' => array(
        'login' => array (
            'title'       => 'Se connecter...',
            'inner'       => 'Indiquez votre nom d\'utilisateur et votre mot de passe',
            'description' => '',
            'username' => array(
                'title'       => 'Login',
                'placeholder' => 'Email...'
            ),
            'password' => array(
                'title'       => 'Mot de passe',
                'placeholder' => 'Mot de passe...'
            ),
            'connect' => array(
                'title'       => 'Me connecter...'
            )
        ),
        'edit' => array(
            'main-title' => 'Mes informations',
            'title' => array(
                'title'  => 'Titre',
                'MISS'   => 'Mademoiselle',
                'MISTER' => 'Monsieur',
                'MADAM'  => 'Madame',
                'OTHER'  => 'Autre'
            )
        ),
        'broker' => array(
            'title' => 'Gestion des accès'
        ),
        'domain' => array(
            'title' => 'Gestion des domaines'
        ),
        'user' => array(
            'title'  => 'Gestion des utilisateurs',
            'create' => 'Ajout d\'un utilisateur',
            'error'  => array(
                'passworddiffer' => 'Les mot de passe sont différents'
            )
        )
    ),
    'brokersession' => array(
        'grid' => array(
            'title' => 'Liste des sessions'
        ),
        'brk_key' => array(
            'title' => 'Accès'
        ),
        'brs_token' => array(
            'title' => 'Token'
        ),
        'brs_client_address' => array(
            'title' => 'IP'
        ),
        'brs_end' => array(
            'title' => 'Expire le'
        )
    ),
    'broker' => array(
        'grid' => array(
            'title' => 'Liste des accès'
        ),
        'create' => array(
            'title' => 'Création d\'un accès'
        ),
        'update' => array(
            'title' => 'Modification d\'un accès'
        ),
        'data' => array(
            'title' => 'Données générales'
        ),
        'config' => array(
            'title' => 'Droits'
        ),
        'config2' => array(
            'title' => 'Configuration'
        ),
        'ips' => array(
            'title' => 'Ips'
        ),
        'brk_name' => array(
            'label' => 'Nom',
            'title' => 'Nom'
        ),
        'dom_id' => array(
            'label' => 'Domaine',
            'title' => 'Domaine'
        ),
        'brk_key' => array(
            'label' => 'Clef',
            'title' => 'Clef'
        ),
        'brk_active' => array(
            'label' => 'Activé',
            'title' => 'Activé'
        ),
        'brk_api_scope' => array(
            'label' => 'Api',
            'title' => 'Api'
        ),
        'brk_users_scope' => array(
            'label' => 'Utilisateur',
            'title' => 'Utilisateur'
        ),
        'brk_certificate' => array(
            'label' => 'Certificat',
            'title' => 'Certificat'
        ),
        'brk_ips' => array(
            'label' => 'Ips',
            'title' => 'Ips'
        ),
        'brk_config' => array(
            'label' => 'Configuration',
            'title' => 'Configuration'
        )
    ),
    'domain' => array(
        'grid' => array(
            'title' => 'Liste des domaines'
        ),
        'create' => array(
            'title' => 'Création d\'un domaine'
        ),
        'update' => array(
            'title' => 'Modification d\'un domaine'
        ),
        'data' => array(
            'title' => 'Données générales'
        ),
        'config' => array(
            'title' => 'Paramétrage'
        ),
        'dom_name' => array(
            'label' => 'Nom',
            'title' => 'Nom'
        ),
        'dom_key' => array(
            'label' => 'Clef',
            'title' => 'Clef'
        ),
        'dom_concurrent_user' => array(
            'label' => 'Utilisateur concurrents',
            'help'  => '0 pour aucune limite'
        ),
        'dom_maintain_seconds' => array(
            'label' => 'Maintient automatique (sec.)',
            'help'  => '0 pour aucune limite'
        ),
        'dom_session_minutes' => array(
            'label' => 'Durée maximale de session (min.)',
            'help'  => '0 pour aucune limite'
        )
    ),
    'group' => array(
        'grid' => array(
            'title' => 'Liste des groupes'
        ),
        'create' => array(
            'title' => 'Création d\'un groupe'
        ),
        'update' => array(
            'title' => 'Modification d\'un groupe'
        ),
        'data' => array(
            'title' => 'Données générales'
        ),
        'config' => array(
            'title' => 'Paramétrage'
        ),
        'grp_id' => array(
            'label' => 'Identifiant',
            'title' => 'Identifiant'
        ),
        'grp_name' => array(
            'label' => 'Nom',
            'title' => 'Nom'
        ),
        'grp_key' => array(
            'label' => 'Clef',
            'title' => 'Clef'
        ),
        'grp_active' => array(
            'label' => 'Actif',
            'title' => 'Actif'
        ),
        'grp_ips' => array(
            'label'   => 'Ips',
            'title'   => 'Ips',
            'jsonapi' => 'Liste des ips/masques autorisés, séparés par ,'
        ),
        'grp_cnx' => array(
            'label' => 'Connexion',
            'title' => 'Connexion'
        ),
        'grp_verif_key' => array(
            'label' => 'Clef de vérification',
            'title' => 'Clef de vérification',
            'help'  => 'Clef de vérification fournie par le webservice = identification "client"'
        ),
        'grp_verif_prefix' => array(
            'label' => 'Préfixe de vérification',
            'title' => 'Préfixe de vérification',
            'help'  => 'Préfixe à fournir pour compléxifier la clef de vérification ' .
                       '(si l\'appellant ne dispose pas d\'un identifiant stable)'
        )
    ),
    'group-user' => array(
        'grid' => array(
            'title' => 'Utilisateurs du groupe [[:grp_name:]]'
        ),
        'create' => array(
            'title' => 'Création d\'un groupe'
        ),
        'update' => array(
            'title' => 'Modification d\'un groupe'
        ),
        'data' => array(
            'title' => 'Données générales'
        ),
        'infos' => array(
            'title' => 'Données du compte'
        ),
        'config' => array(
            'title' => 'Paramétrage'
        ),
        'grp_id' => array(
            'label' => 'Groupe',
            'title' => 'Groupe'
        ),
        'user_id' => array(
            'label' => 'Utilisateur',
            'title' => 'Utilisateur'
        ),
        'gru_key' => array(
            'label' => 'Code utilisateur',
            'title' => 'Code utilisateur'
        ),
        'user_email' => array(
            'label' => 'Email utilisateur',
            'title' => 'Email utilisateur'
        ),
        'user_login' => array(
            'label' => 'Login utilisateur',
            'title' => 'Login utilisateur'
        ),
        'gru_active' => array(
            'label' => 'Actif',
            'title' => 'Actif'
        ),
        'gru_infos' => array(
            'label' => 'Informations',
            'title' => 'Informations'
        )
    ),
    'user' => array(
        'actions' => array(
            'add' => array(
                'title' => 'Ajouter un utilisateur'
            ),
            'broker' => array(
                'title' => 'Accès de l\'utilisateur'
            ),
            'edit' => array(
                'title' => 'Modifier l\'utilisateur'
            ),
            'remove' => array(
                'title' => 'Supprimer l\'utilisateur'
            )
        ),
        'grid' => array(
            'title' => 'Liste des utilisateurs'
        ),
        'data' => array(
            'title' => 'Données générales'
        ),
        'cnx' => array(
            'title' => 'Paramètres de connexion'
        ),
        'email' => array(
            'label' => 'Email',
            'title' => 'Email'
        ),
        'add' => array(
            'title' => 'Ajouter un utilisateur'
        ),
        'broker' => array(
            'title' => 'Accès de l\'utilisateur'
        ),
        'create' => array(
            'title' => 'Ajout d\'un utilisateur'
        ),
        'update' => array(
            'title' => 'Modification d\'un utilisateur'
        ),
        'infos' => array(
            'title' => 'Informations'
        ),
        'user_email' => array(
            'title' => 'Email',
            'label' => 'Email'
        ),
        'user_login' => array(
            'title' => 'Login',
            'label' => 'Login'
        ),
        'user_password' => array(
            'label' => 'Mot de passe'
        ),
        'user_active' => array(
            'title' => 'Activé',
            'label' => 'Activé'
        ),
        'user_title' => array(
            'label'  => 'Titre',
            'other'  => 'Autre',
            'mister' => 'Monsieur',
            'madam'  => 'Madame',
            'miss'   => 'Mademoiselle'
        ),
        'user_last_name' => array(
            'label' => 'Nom'
        ),
        'user_first_name' => array(
            'label' => 'Prénom'
        ),
        'user_cnx' => array(
            'label' => 'Connexions',
            'title' => 'Connexions'
        ),
        'confirm' => array(
            'label'  => 'Confirmation du mot de passe',
            'remove' => 'Confirmez-vous la suppression de l\'utilisateur ?'
        )
    )
);

return $translations;
