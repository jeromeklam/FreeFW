<?php
namespace FreeFW\Command;

/**
 * FreeFW Dev commands
 *
 * @author jeromeklam
 */
class Dev
{

    /**
     * Import des traductions
     *
     * @param \FreeFW\Console\Input\AbstractInput $p_input
     * @param \FreeFW\Console\Output\AbstractOutput $p_output
     */
    public function importTranslations(
        \FreeFW\Console\Input\AbstractInput $p_input,
        \FreeFW\Console\Output\AbstractOutput $p_output
    ) {
        $p_output->write("Import des traductions", true);
        /**
         * @var \FreeFW\Service\Translation $translationService
         */
        $translationService = \FreeFW\DI\DI::get('FreeFW::Service::Translation');
        $translationService->import([]);
        $p_output->write("Fin de l'import", true);
    }
}
