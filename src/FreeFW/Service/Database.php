<?php
namespace FreeFW\Service;

/**
 *
 * @author jeromeklam
 *
 */
class Database extends \FreeFW\Core\Service
{

    /**
     *
     */
    public function migrate()
    {
        /**
         * @var \FreeFW\Model\Query $query
         */
        $query = \FreeFW\DI\DI::get('FreeFW::Model::Query');
        $query
            ->setType(\FreeFW\Model\Query::QUERY_SELECT)
            ->setMainModel('FreeFW::Model::Version')
            ->setSort(['vers_install_date' => \FreeFW\Storage\Storage::SORT_ASC])
        ;
        /**
         * @var \FreeFW\Model\ResultSet $installedVersions
         */
        $installedVersions = new \FreeFW\Model\ResultSet();
        if ($query->execute()) {
            $installedVersions = $query->getResult();
        }
        if ($installedVersions->hasErrors()) {
            /**
             * @var \FreeFW\Storage\AbstractUpdater $versMigration
             */
            $versMigration = \FreeFW\DI\DI::get('FreeFW::Migration::Version');
            $versMigration->up();
            $installedVersions = new \FreeFW\Model\ResultSet();
            if ($query->execute()) {
                $installedVersions = $query->getResult();
            }
        }
        if ($installedVersions->hasErrors()) {
            // @todo
            die('Fatal error');
        }
        /**
         * @var \FreeFW\Storage\AbstractUpdater $updater
         */
        $updaters = \FreeFW\DI\DI::getUpdaters();
        $versions = [];
        foreach ($updaters as $updater) {
            $versions = array_merge($versions, $updater->getVersions());
        }
        ksort($versions);
        foreach ($versions as $versData) {
            $found = false;
            /**
             * @var \FreeFW\Model\Version $oneVersion
             */
            foreach ($installedVersions as $oneVersion) {
                if (!strpos($oneVersion->getVersVersion() . '.', $versData['vers']) === 0) {
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                echo 'Installing ' . $versData['vers'] . PHP_EOL;
                /**
                 * @var \FreeFW\Storage\AbstractUpdater $versMigration
                 */
                $versNewMigration = \FreeFW\DI\DI::get($versData['class']);
                $versNewMigration->up();
            }
        }
    }
}
