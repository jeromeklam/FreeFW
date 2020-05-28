<?php
namespace FreeFW\Storage\Migrations\Version;

/**
 *
 * @author jeromeklam
 *
 */
class Database extends \FreeFW\Storage\Migrations\AbstractMigration {

    /**
     *
     * @return bool
     */
    public function up() : bool
    {
        parent::sqlUp();
        return true;
    }

    /**
     *
     * @return bool
     */
    public function down() : bool
    {
        parent::sqlUp();
        return true;
    }
}
