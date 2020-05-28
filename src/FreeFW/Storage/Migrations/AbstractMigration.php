<?php
namespace FreeFW\Storage\Migrations;

abstract class AbstractMigration implements
    \Psr\Log\LoggerAwareInterface,
    \FreeFW\Interfaces\ConfigAwareTraitInterface
{

    /**
     * Behaviour
     */
    use \FreeFW\Behaviour\ConfigAwareTrait;
    use \FreeFW\Behaviour\EventManagerAwareTrait;
    use \Psr\Log\LoggerAwareTrait;

    /**
     * Storage
     * @var \FreeFW\Interfaces\StorageInterface
     */
    protected $storage = null;

    /**
     * Base path
     * @var string
     */
    protected $base_path = null;

    /**
     * Version
     * @var string
     */
    protected $version = null;

    /**
     * Sql files without path
     * @var array
     */
    protected $sql_files = [];

    /**
     * Intern step
     * @var integer
     */
    protected $step = 0;

    /**
     * Constructor
     *
     * @param \FreeFW\Interfaces\StorageInterface $p_provider
     */
    public function __construct(\FreeFW\Interfaces\StorageInterface $p_storage)
    {
        $this->storage = $p_storage;
        $this->init();
    }

    /**
     * Init
     *
     * @return bool
     */
    public function init()
    {
        $cls = new \ReflectionClass(get_called_class());
        $dir = $cls->getFileName();
        //
        $this->base_path = dirname($dir);
        $this->version   = str_replace('\\', '.', strtolower(get_called_class()));
        $this->version   = str_replace('storage.migrations.', '', $this->version);
        $this->version   = str_replace('.database', '', $this->version);
    }

    /**
     * Up
     *
     * @return bool
     */
    abstract public function up() : bool;

    /**
     * Down
     *
     * @return bool
     */
    abstract public function down() : bool;

    /**
     * Get base path
     *
     * @return string
     */
    protected function getBasePath()
    {
        return rtrim($this->base_path, '/') . '/';
    }

    /**
     * Get version
     *
     * @return string
     */
    protected function getVersion()
    {
        return $this->version;
    }

    /**
     * Add sql file
     *
     * @param string $p_file
     * @param string $p_way
     *
     * @return \FreeFW\Storage\Migrations\AbstractMigration
     */
    protected function addSqlFile($p_file, $p_way = 'up')
    {
        if (!array_key_exists($p_way, $this->sql_files)) {
            $this->sql_files[$p_way] = [];
        }
        $this->sql_files[$p_way][] = $p_file;
        return $this;
    }

    /**
     * Get sql files
     *
     * @param string $p_way
     *
     * @return [string]
     */
    protected function getSqlFiles($p_way)
    {
        if (!array_key_exists($p_way, $this->sql_files)) {
            $this->sql_files[$p_way] = [];
        }
        if (count($this->sql_files[$p_way]) <= 0) {
            $this->sql_files[$p_way][] = strtolower($p_way) . '.sql';
        }
        return $this->sql_files[$p_way];
    }

    /**
     *
     * @return bool
     */
    protected function sqlUp() : bool
    {
        $run   = [];
        $ret   = true;
        $files = $this->getSqlFiles('up');
        foreach ($files as $oneFile) {
            $sqlFile = $this->getBasePath() . $oneFile;
            $this->step += 1;
            /**
             * @var \FreeFW\Model\Version $version
             */
            $version = \FreeFW\DI\DI::get('FreeFW::Model::Version');
            $version
                ->setVersInstallFile($sqlFile)
                ->setVersInstallStatus(\FreeFW\Model\Version::STATUS_PENDING)
                ->setVersInstallDate(\FreeFW\Tools\Date::getCurrentTimestamp())
                ->setVersVersion($this->getVersion() . '.' . $this->step)
            ;
            if (is_file($sqlFile)) {
                $sqls = \FreeFW\Tools\PBXString::splitSql(file_get_contents($sqlFile));
                foreach ($sqls as $oneSql) {
                    $run[] = $oneSql;
                    $version->setVersInstallContent(print_r($run, true));
                    $stmt  = $this->storage->getProvider()->prepare($oneSql);
                    if ($stmt) {
                        if ($stmt->execute()) {
                            $version
                                ->setVersInstallStatus(\FreeFW\Model\Version::STATUS_OK)
                            ;
                        } else {
                            // @todo
                            $ret = false;
                            $version
                                ->setVersInstallStatus(\FreeFW\Model\Version::STATUS_ERROR)
                                ->setVersInstallText(print_r($stmt->errorInfo(), true));
                            ;
                            break;
                        }
                    } else {
                        // @todo
                        $ret = false;
                        $version
                            ->setVersInstallStatus(\FreeFW\Model\Version::STATUS_ERROR)
                            ->setVersInstallText(print_r($stmt->errorInfo(), true));
                        ;
                        break;
                    }
                }
            } else {
                // @todo
                $version
                    ->setVersInstallStatus(\FreeFW\Model\Version::STATUS_ERROR)
                    ->setVersInstallText('File ' . $sqlFile . ' not found !');
                ;
                $ret = false;
                break;
            }
            $version->create();
        }
        return $ret;
    }

    /**
     *
     * @return bool
     */
    protected function sqlDown() : bool
    {
        return true;
    }
}
