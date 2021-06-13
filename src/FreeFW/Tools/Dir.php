<?php
/**
 * Fonctions utilitaires sur les répertoires
 *
 * @author jeromeklam
 * @package System
 * @category Tools
 */
namespace FreeFW\Tools;

/**
 * Fonctions utilitaires sur les répertoires
 * @author jeromeklam
 */
class Dir
{

    /**
     * Get standard file path
     *
     * @param string $p_base_dir
     *
     * @return string
     */
    public static function mkStdFolder($p_base_dir = '/tmp')
    {
        $dir = rtrim($p_base_dir, '/') . '/' . date('Y') . '/' . date('m') . '/' . date('d') . '/';
        if (self::mkpath($dir)) {
            return $dir;
        }
        return '/tmp/';
    }

    /**
     * Création récursive d'un chemin
     *
     * @param string $path
     *
     * @return boolean
     */
    public static function mkpath($path)
    {
        if (is_dir($path)) {
            return true;
        } else {
            if (@mkdir($path)) {
                return true;
            } else {
                self::mkpath(dirname($path));
                return @mkdir($path);
            }
        }
        return true;
    }

    /**
     * Suppression récursive d'un chemin
     *
     * @param string $target
     *
     * @todo return
     */
    public static function remove($target)
    {
        if (is_dir($target)) {
            $files = glob($target . '*', GLOB_MARK);
            foreach ($files as $file) {
                self::remove($file);
            }
            @rmdir($target);
        } else {
            if (is_file($target)) {
                @unlink($target);
            }
        }
    }

    /**
     * Recursive copy
     *
     * @param string $source
     * @param string $dest
     * @param number $permissions
     *
     * @return boolean
     */
    public static function copy($source, $dest, $permissions = 0775)
    {
        // Check for symlinks
        if (is_link($source)) {
            return symlink(readlink($source), $dest);
        }
        // Simple copy for a file
        if (is_file($source)) {
            return copy($source, $dest);
        }
        // Make destination directory
        if (!is_dir($dest)) {
            mkdir($dest, $permissions);
        }
        if (is_dir($source)) {
            // Loop through the folder
            $dir = dir($source);
            while (false !== $entry = $dir->read()) {
                // Skip pointers
                if ($entry == '.' || $entry == '..') {
                    continue;
                }
                // Deep copy directories
                self::copy("$source/$entry", "$dest/$entry", $permissions);
            }
            // Clean up
            $dir->close();
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get all files from directory
     *
     * @param string $p_directory
     * @param array  $p_files
     *
     * @return array
     */
    public static function recursiveDirectoryIterator($p_directory = null, $p_files = [])
    {
        $iterator = new \DirectoryIterator($p_directory);
        foreach ($iterator as $info) {
            if ($info->isFile()) {
                $p_files[$info->__toString()] = clone($info);
            } else {
                if (!$info->isDot()) {
                    $p_files[$info->__toString()] = self::recursiveDirectoryIterator(
                        $p_directory . DIRECTORY_SEPARATOR . $info->__toString()
                    );
                }
            }
        }
        return $p_files;
    }
}
