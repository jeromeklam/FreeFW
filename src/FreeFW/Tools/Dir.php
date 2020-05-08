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
}
