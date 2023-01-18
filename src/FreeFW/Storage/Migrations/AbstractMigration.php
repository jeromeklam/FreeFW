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
     * methods
     * @var array
     */
    protected $methods = [];

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
    abstract public function up(): bool;

    /**
     * Down
     *
     * @return bool
     */
    abstract public function down(): bool;

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
     * Add a method to execute
     *
     * @param string $p_method
     * @param string $p_way
     *
     * @return \FreeFW\Storage\Migrations\AbstractMigration
     */
    protected function addMethod($p_method, $p_way = 'up')
    {
        if (!array_key_exists($p_way, $this->methods)) {
            $this->methods[$p_way] = [];
        }
        $this->methods[$p_way][] = $p_method;
        return $this;
    }

    /**
     * Get sql files
     *
     * @param string $p_way
     *
     * @return [string]
     */
    protected function getSqlFiles($p_way, $p_path)
    {
        if (!array_key_exists($p_way, $this->sql_files)) {
            $this->sql_files[$p_way] = [];
        }
        if (count($this->sql_files[$p_way]) <= 0) {
            if (is_file($p_path . '/' . $p_way . '.sql')) {
                $this->sql_files[$p_way][] = strtolower($p_way) . '.sql';
            } else {
                foreach (glob($p_path . '/' . $p_way . '*.sql') as $sql_file) {
                    $this->sql_files[$p_way][] = trim(str_replace($p_path, '', $sql_file), '/');
                }
            }
        }
        return $this->sql_files[$p_way];
    }

    /**
     * Get methods
     *
     * @param string $p_way
     *
     * @return [string]
     */
    protected function getMethods($p_way)
    {
        if (!array_key_exists($p_way, $this->methods)) {
            $this->methods[$p_way] = [];
        }
        return $this->methods[$p_way];
    }

    /**
     * @return bool
     */
    protected function scriptUp($p_desc = ''): bool
    {
        /**
         * @var \FreeFW\Model\Version $version
         */
        $version = \FreeFW\DI\DI::get('FreeFW::Model::Version');
        $version
            ->setVersInstallFile('script')
            ->setVersInstallStatus(\FreeFW\Model\Version::STATUS_OK)
            ->setVersInstallDate(\FreeFW\Tools\Date::getCurrentTimestamp())
            ->setVersVersion($this->getVersion() . '.' . $this->step)
            ->setVersInstallText($p_desc);

        return $version->create();
    }

    /**
     *
     * @return bool
     */
    protected function sqlUp(): bool
    {
        $run   = [];
        $ret   = true;
        $files = $this->getSqlFiles('up', $this->getBasePath());
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
                ->setVersVersion($this->getVersion() . '.' . $this->step);
            if (is_file($sqlFile)) {
                // A file with "full" in the name is a complete SQL
                if (strpos($sqlFile, 'full') === false) {
                    $sqls = \FreeFW\Tools\PBXString::splitSql(file_get_contents($sqlFile));
                } else {
                    $sqls   = [];
                    $sqls[] = file_get_contents($sqlFile);
                }
                foreach ($sqls as $oneSql) {
                    $run[] = $oneSql;
                    $version->setVersInstallContent(print_r($run, true));
                    $stmt  = $this->storage->getProvider()->prepare($oneSql);
                    if ($stmt) {
                        if ($stmt->execute()) {
                            $version
                                ->setVersInstallStatus(\FreeFW\Model\Version::STATUS_OK);
                        } else {
                            // @todo
                            $ret = false;
                            $version
                                ->setVersInstallStatus(\FreeFW\Model\Version::STATUS_ERROR)
                                ->setVersInstallText(print_r($stmt->errorInfo(), true));;
                            break;
                        }
                    } else {
                        // @todo
                        $ret = false;
                        $version
                            ->setVersInstallStatus(\FreeFW\Model\Version::STATUS_ERROR)
                            ->setVersInstallText(print_r($stmt->errorInfo(), true));;
                        break;
                    }
                }
            } else {
                // @todo
                $version
                    ->setVersInstallStatus(\FreeFW\Model\Version::STATUS_ERROR)
                    ->setVersInstallText('File ' . $sqlFile . ' not found !');;
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
    protected function sqlDown(): bool
    {
        return true;
    }

    /**
     * Upgrades via methods
     *
     * @return bool
     */
    protected function methodUp(): bool
    {
        $run     = [];
        $ret     = true;
        $methods = $this->getMethods('up');
        foreach ($methods as $oneMethod) {
            $this->step += 1;
            /**
             * @var \FreeFW\Model\Version $version
             */
            $version = \FreeFW\DI\DI::get('FreeFW::Model::Version');
            $version
                ->setVersInstallFile($oneMethod)
                ->setVersInstallStatus(\FreeFW\Model\Version::STATUS_PENDING)
                ->setVersInstallDate(\FreeFW\Tools\Date::getCurrentTimestamp())
                ->setVersVersion($this->getVersion() . '.' . $this->step);
            if (method_exists($this, $oneMethod)) {
                try {
                    if (!$this->$oneMethod()) {
                        $version
                            ->setVersInstallStatus(\FreeFW\Model\Version::STATUS_ERROR)
                            ->setVersInstallText('Method ' . $oneMethod . ' error !');;
                        $ret = false;
                        break;
                    }
                    $version
                        ->setVersInstallStatus(\FreeFW\Model\Version::STATUS_OK);
                } catch (\Exception $ex) {
                    // @todo
                    $version
                        ->setVersInstallStatus(\FreeFW\Model\Version::STATUS_ERROR)
                        ->setVersInstallText('Method ' . $oneMethod . ' error !');;
                    $ret = false;
                    break;
                }
            } else {
                // @todo
                $version
                    ->setVersInstallStatus(\FreeFW\Model\Version::STATUS_ERROR)
                    ->setVersInstallText('Method ' . $oneMethod . ' not found !');;
                $ret = false;
                break;
            }
            $version->create();
        }
        return $ret;
    }

    /**
     * Downgrade via mathods
     *
     * @return bool
     */
    protected function methodDown(): bool
    {
        return true;
    }
}
