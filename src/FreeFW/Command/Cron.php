<?php
namespace FreeFW\Command;

/**
 * FreeFW commands
 *
 * @author jeromeklam
 */
class Cron
{

    /**
     * Vérification des files d'attente
     *
     * @param \FreeFW\Console\Input\AbstractInput $p_input
     * @param \FreeFW\Console\Output\AbstractOutput $p_output
     */
    public function checkJobqueue(
        \FreeFW\Console\Input\AbstractInput $p_input,
        \FreeFW\Console\Output\AbstractOutput $p_output
    ) {
        $p_output->write("Gestion de la file d'attente", true);
        $sso      = \FreeFW\DI\DI::getShared('sso');
        $brokerId = $sso->getBrokerId();
        $p_output->write("Broker : " . $brokerId, true);
        $jobqueueService = \FreeFW\DI\DI::get('FreeFW::Service::Jobqueue');
        $jobqueueService->handle();
        $p_output->write("Fin de la gestion", true);
    }
}
