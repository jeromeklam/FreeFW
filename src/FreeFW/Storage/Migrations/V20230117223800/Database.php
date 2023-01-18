<?php

namespace FreeFW\Storage\Migrations\V20230117223800;

/**
 *
 * @author jeromeklam
 *
 */
class Database extends \FreeFW\Storage\Migrations\AbstractMigration
{

    /**
     *
     * @return bool
     */
    public function up(): bool
    {
        ini_set('memory_limit', '4096M');
        $config = \FreeFW\DI\Di::getShared('config');
        $dir = $config->get('ged:dir', '/tmp');
        $dir = rtrim($dir, '/') . '/inbox/';
        \FreeFW\Tools\Dir::mkpath($dir);
        $query = \FreeFW\Model\Inbox::getQuery();
        $query->execute([], null, [], true);
        foreach ($query->getResult() as $oneInbox) {
            $temp = \FreeFW\Model\Inbox::findFirst(['inbox_id' => $oneInbox->getInboxId()]);
            file_put_contents($dir . $oneInbox->getInboxId() . '.data', $temp->getInboxContent());
            /*$oneInbox
                ->setInboxContent(null)
                ->save()
            ;*/
            // must clean
        }
        return $this->scriptUp('Stockage sur disque des PJs');
    }

    /**
     *
     * @return bool
     */
    public function down(): bool
    {
        return true;
    }
}
