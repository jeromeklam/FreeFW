<?php
namespace FreeFW\Directory;

class SftpDirectory implements \FreeFW\Interfaces\Directory
{

    /**
     * Déplace le fichier en local
     *
     * @param unknown $p_file
     * @param unknown $pdestFile
     *
     * @return boolean
     */
    public function getFile($p_file, $pdestFile)
    {
        $fileName = basename($p_file);
        return copy($p_file, $pdestFile."/".$fileName);
    }

    /**
     * Retourne le contenu
     *
     * @return mixed
     */
    public function getContent($directory)
    {
        return scandir($directory);
    }

    /**
     * Supprime le fichier distant
     *
     * @param unknown $p_file
     * @return boolean
     */
    public function delFile($p_file)
    {
        return unlink($p_file);
    }
}
