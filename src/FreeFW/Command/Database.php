<?php
namespace FreeFW\Command;

/**
 *
 * @author jeromeklam
 *
 */
class Database
{

    /**
     * Migrate database
     *
     * @param \FreeFW\Console\Input\Input $p_input
     */
    public function migrate(
        \FreeFW\Console\Input\AbstractInput $p_input,
        \FreeFW\Console\Output\AbstractOutput $p_output
    ) {
        ini_set('memory_limit', '4096M');
        set_time_limit(3600);
        $p_output->write("Début de la migration", true);
        $dbService = \FreeFW\DI\DI::get('FreeFW::Service::Database');
        $dbService->migrate();
        $p_output->write("Fin de la migration", true);
    }
}
