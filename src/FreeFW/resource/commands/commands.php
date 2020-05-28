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
];

return $localCommands;