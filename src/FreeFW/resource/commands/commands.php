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
    ],
    /**
     * ########################################################################
     * Import des traductions
     * ########################################################################
     */
    'freefw.dev.importtranslations' => [
        'command'    => 'translation::import',
        'controller' => 'FreeFW::Command::Dev',
        'function'   => 'importTranslations'
    ],
    /**
     * ########################################################################
     * Vérification des payx
     * ########################################################################
     */
    'freefw.opendata.countries' => [
        'command'    => 'opendata::countries',
        'controller' => 'FreeFW::Command::OpenData',
        'function'   => 'checkCountries'
    ]
];

return $localCommands;