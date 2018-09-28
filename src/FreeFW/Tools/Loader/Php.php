<?php
/**
 * Loader de fichier php
 *
 * @author jeromeklam
 * @package Tools
 * @category Loader
 */
namespace FreeFW\Tools\Loader;

/**
 * Loader de configuration dans un fichier php
 * @author jeromeklam
 */
class Php extends \FreeFW\Tools\Loader\AbstractLoader
{

    /**
     * Lecture des données depuis le fichier
     *
     * @return array
     */
    protected function readDatas()
    {
        if (is_file($this->file)) {
            try {
                $datas = include $this->file;
                if (!is_array($datas)) {
                    $datas = array();
                }
            } catch (\Exception $ex) {
                $datas = array();
            }
        } else {
            throw new \InvalidArgumentException(sprintf('File %s not found !', $this->file));
        }

        return $datas;
    }
}
