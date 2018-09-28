<?php
/**
 * Interface des classes de gestion d'un document XML serialise
 *
 * @package Demat\DocXMLSerialize
 */
namespace FreeFW\Interfaces;

/**
 * Classe de gestion d'un document XML serialise
 */
interface DocXMLSerialize
{
    /**
     * Methode qui charge un fichier xml et le stock dans un objet PHP
     *
     * @param string $xml
     */
    public function chargement($xml);

    /**
     * Methode de creation du fichier JSON
     *
     * @param string $obj
     */
    public function creationJSON($xml, $obj);
}
