<?php
$localCommands = [
    /**
     * ########################################################################
     * Routes FreeFW
     * ########################################################################
     */
    'freefw.database.migrate' => [
        'command'    => 'database::migrate',
        'controller' => 'FreeFW::Command::Database',
        'function'   => 'migrate'
    ],
    /**
     * ########################################################################
     * Vérification de la file d'attente
     * ########################################################################
     */
    'freefw.cron.checkjobqueue' => [
        'command'    => 'jobqueue::check',
        'controller' => 'FreeFW::Command::Cron',
        'function'   => 'checkJobqueue'
    ]
];

return $localCommands;