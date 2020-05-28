<?php
namespace FreeFW\Storage;

/**
 *
 * @author jeromeklam
 *
 */
abstract class AbstractUpdater implements
    \Psr\Log\LoggerAwareInterface,
    \FreeFW\Interfaces\ConfigAwareTraitInterface,
    \FreeFW\Interfaces\StorageStrategyInterface
{

    /**
     * Behaviour
     */
    use \FreeFW\Behaviour\ConfigAwareTrait;
    use \FreeFW\Behaviour\EventManagerAwareTrait;
    use \Psr\Log\LoggerAwareTrait;

    /**
     * Storage strategy
     * @var \FreeFW\Interfaces\StorageInterface
     */
    protected $strategy = null;

    /**
     *
     * {@inheritDoc}
     * @see \FreeFW\Interfaces\StorageStrategyInterface::setStrategy()
     */
    public function setStrategy(\FreeFW\Interfaces\StorageInterface $p_strategy)
    {
        $this->strategy = $p_strategy;
        return $this;
    }

    /**
     * Return all versions
     */
    public function getVersions()
    {
        $cls = new \ReflectionClass(get_called_class());
        $dir = $cls->getFileName();
        //
        $path = rtrim(dirname($dir), '/') . '/Migrations';
        $directory = new \DirectoryIterator($path);
        $versions  = [];
        foreach ($directory as $fileinfo) {
            if ($fileinfo->isDir()) {
                $filename = $fileinfo->getFilename();
                if ($filename != '.' && $filename != '..' && strtoupper($filename) != 'VERSION') {
                    $ns   = explode ('\\', $cls->getNamespaceName());
                    $vers = strtolower($ns[0]) . '.' . strtolower($filename);
                    $versions[$vers] = [
                        'path'  => $path . '/' . $filename,
                        'ns'    => $ns[0],
                        'vers'  => $vers,
                        'class' => $ns[0] . '::Migration::' . $filename,
                    ];
                }
            }
        }
        return $versions;
    }
}
