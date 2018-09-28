<?php
/**
 * Interface de gestion d'un dossier
 *
 * @package Demat\DocXMLSerialize
 */
namespace FreeFW\Interfaces;

/**
 * Classe de gestion d'un document XML serialise
 */
interface Directory
{
    /**
     * Retourne le contenu
     */
    public function getContent();

    /**
     * Récupère le fichier en local
     *
     * @param unknown $p_file
     * @param unknown $pdestFile
     */
    public function getFile($p_file, $pdestFile);

    /**
     * Supprime le fichier distant
     *
     * @param unknown $p_file
     */
    public function delFile($p_file);
}
